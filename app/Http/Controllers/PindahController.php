<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\Pindah;
use App\Models\PindahDetail;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Carbon\Carbon;

class PindahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Pindah::select('pindah.*')->with(['lokasi'])->withCount('lines')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="dropdown">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdown-default-outline-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-outline-primary" style="">';
                        $btn .= '<a class="dropdown-item" href="'. route('pindah.show', $row->id).'"><i class="si si-eye me-1"></i>Detail</a>';
                        $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="hapus('. $row->id.')"><i class="si si-trash me-1"></i>Hapus</a>';
                    $btn .= '</div></div>';
                    return $btn;
                })
                ->editColumn('tgl', function ($row) {
                    return Carbon::parse($row->tgl)->translatedFormat('d F Y');
                })
                ->rawColumns(['action', 'status']) 
                ->make(true);
        }
        return view('pindah.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lokasi = Lokasi::orderBy('nama', 'ASC')->get();
        $barang = Barang::orderBy('nama', 'ASC')->get();

        return view('pindah.form',[
            'barang' => $barang,
            'lokasi' => $lokasi
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
        // dd($request->all());
        $rules = [
            'tgl' => 'required',
            'lokasi_id' => 'required',
        ];

        $pesan = [
            'lokasi_id.required' => 'Lokasi Wajib Diisi!',
            'tgl.required' => 'Tanggal Wajib Diisi!',
        ];


        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = new Pindah();
                $data->nomor = $this->getNumber();
                $data->tgl = $request->tgl;
                $data->lokasi_id = $request->lokasi_id;
                $data->save();

                if(count($request->line)){
                    foreach($request->line as $l){
                        $ln = new PindahDetail();
                        $ln->barang_id = $l['barang_id'];
                        $ln->lokasi_id = $l['lokasi_id'];
                        $data->lines()->save($ln);

                        $brg = Barang::where('id', $l['barang_id'])->first();
                        $brg->lokasi_id = $request->lokasi_id;
                        $brg->save();
                    }
                }

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();
            return redirect()->route('pindah.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Pindah::with(['lokasi', 'lines'])->where('id', $id)->first();
        $lines = PindahDetail::with(['barang', 'lokasi'])->where('pindah_id', $id)->get();

        return view('pindah.show',[
            'data' => $data,
            'lines' => $lines
        ]);
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

        return view('pindahedit',[
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

            $data = Pindah::where('id', $id)->first();
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

    
    public function confirm($id, Request $request)
    {
        DB::beginTransaction();
        try{

            $data = Pengadaan::where('id', $id)->first();
            $data->status = $request->status;

            if($request->status == 'setuju'){
                for($i = 1; $i <= $data->qty; $i++){
                    $nomor = '19.11.';
                    $kategori = Kategori::where('id', $data->kategori_id)->first();
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

                    $brg = new Barang();
                    $brg->nomor = $nomor;
                    $brg->nama = $data->nama;
                    $brg->kategori_id = $data->kategori_id;
                    $brg->lokasi_id = $data->lokasi_id;
                    $brg->deskripsi = $data->deskripsi;
                    $brg->save();
                }
            }
            $data->save();

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


    
    
    private function getNumber()
    {
        $q = Pindah::select(DB::raw('MAX(RIGHT(nomor,5)) AS kd_max'));

        $code = 'MV/';
        $no = 1;
        date_default_timezone_set('Asia/Jakarta');

        if($q->count() > 0){
            foreach($q->get() as $k){
                return $code . date('ym') .'/'.sprintf("%05s", abs(((int)$k->kd_max) + 1));
            }
        }else{
            return $code . date('ym') .'/'. sprintf("%05s", $no);
        }
    }
}
