<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\Bidang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Carbon\Carbon;

use App\Models\Kerusakan;

class InventarisController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $lokasi_id = $request->lokasi_id;
            $kategori_id = $request->kategori_id;
            
            $kerusakan = Kerusakan::where('status', '!=', 'selesai')->get()->pluck('barang_id');

            $data = Barang::select('barang.*')->with(['kategori', 'lokasi'])
            ->when(isset($lokasi_id), function ($q) use ($lokasi_id) {
                return $q->where('lokasi_id', $lokasi_id);
            })
            ->when(isset($kategori_id), function ($q) use ($kategori_id) {
                return $q->where('kategori_id', $kategori_id);
            })
            ->whereNotIn('id', $kerusakan)
            ->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="dropdown">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdown-default-outline-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-outline-primary" style="">';
                        $btn .= '<a class="dropdown-item" href="'. route('inventaris.edit', $row->id).'"><i class="si si-note me-1"></i>Ubah</a>';
                        $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="hapus('. $row->id.')"><i class="si si-trash me-1"></i>Hapus</a>';
                    $btn .= '</div></div>';
                    return $btn;
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->translatedFormat('d F Y');
                })
                ->editColumn('status', function ($row) {
                    if($row->status == 'tersedia'){
                        return '<span class="badge bg-primary">Tersedia</span>';
                    }else if($row->status == 'rusak'){
                        return '<span class="badge bg-danger">Rusak</span>';
                    }else if($row->status == 'diperbaiki'){
                        return '<span class="badge bg-warning">Diperbaiki</span>';
                    }
                })
                ->rawColumns(['action', 'status']) 
                ->make(true);
        }
        
        $kategori = Kategori::orderBy('nama', 'ASC')->get();
        $lokasi = Lokasi::orderBy('nama', 'ASC')->get();
        return view('barang.index',[
            'kategori' => $kategori,
            'lokasi' => $lokasi,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kategori = Kategori::orderBy('nama', 'ASC')->get();
        $lokasi = Lokasi::orderBy('nama', 'ASC')->get();
        $bidang = Bidang::orderBy('nama', 'ASC')->get();

        return view('barang.form',[
            'kategori' => $kategori,
            'lokasi' => $lokasi,
            'bidang' => $bidang
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'lokasi_id' => 'required',
            'kategori_id' => 'required',
            'nama' => 'required',
        ];

        $pesan = [
            'lokasi_id.required' => 'Lokasi Wajib Diisi!',
            'kategori_id.required' => 'Kategori Wajib Diisi!',
            'nama.required' => 'Nama Barang Wajib Diisi!',
        ];


        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                $nomor = '19.11.';
                $kategori = Kategori::where('id', $request->kategori_id)->first();
                $nomor .= $kategori->kode;
                
                $q = Barang::select(DB::raw('MAX(RIGHT(nomor,2)) AS kd_max'));
                $no = 1;
                if($q->count() > 0){
                    foreach($q->get() as $k){
                        $nomor .= '.'.sprintf("%02s", abs(((int)$k->kd_max) + 1));
                    }
                }else{
                    $nomor .= '.'. sprintf("%02s", $no);
                }

                $data = new Barang();
                $data->nomor = $nomor;
                $data->nama = $request->nama;
                $data->kategori_id = $request->kategori_id;
                $data->lokasi_id = $request->lokasi_id;
                $data->deskripsi = $request->deskripsi;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                // return back()->withInput()->withErrors($e);
                dd($e);
            }

            DB::commit();
            return redirect()->route('inventaris.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function json($id)
    {
        $data = Barang::with('lokasi')->where('id', $id)->first();

        return  response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Barang::where('id', $id)->first();
        $kategori = Kategori::orderBy('nama', 'ASC')->get();
        $lokasi = Lokasi::orderBy('nama', 'ASC')->get();
        $bidang = Bidang::orderBy('nama', 'ASC')->get();

        return view('barang.edit',[
            'data' => $data,
            'kategori' => $kategori,
            'lokasi' => $lokasi,
            'bidang' => $bidang
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'lokasi_id' => 'required',
            'kategori_id' => 'required',
            'nama' => 'required',
        ];

        $pesan = [
            'lokasi_id.required' => 'Lokasi Wajib Diisi!',
            'kategori_id.required' => 'Kategori Wajib Diisi!',
            'nama.required' => 'Nama Barang Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = Barang::where('id', $id)->first();
                $data->nama = $request->nama;
                $data->kategori_id = $request->kategori_id;
                $data->lokasi_id = $request->lokasi_id;
                $data->deskripsi = $request->deskripsi;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();
            return redirect()->route('inventaris.index');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{

            $data = Barang::where('id', $id)->first();
            $data->delete();

        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'errors' => $e,
                'pesan' => 'Gagal Mengubah Status Perbaikan!',
            ]);
        }

        DB::commit();
        return response()->json([
            'fail' => false,
            'pesan' => 'Status Perbaikan Berhasil Diperbaharui!',
        ]);
    }

    public function select(Request $request){
        $lokasi_id = $request->id;
        $kategori_id = $request->kategori;
        $search = $request->search;

        $data = Barang::select('id', DB::raw('CONCAT(nomor," : ",nama) as text'))
        ->when(isset($lokasi_id), function ($q) use ($lokasi_id) {
            return $q->where('lokasi_id', $lokasi_id);
        })
        ->when(isset($kategori_id), function ($q) use ($kategori_id) {
            return $q->where('kategori_id', $kategori_id);
        })
        ->when(isset($search), function ($q) use ($search) {
            return $q->where('nama', 'LIKE', '%'.$search.'%');
        })
        ->latest()->get();


        return json_encode($data);
    }
}
