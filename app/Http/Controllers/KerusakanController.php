<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\KerusakanDataTable;
use App\Models\Kerusakan;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use DataTables;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use PDF;

class KerusakanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $tgl = $request->tgl;


            $data = Kerusakan::select('crashes.*')->with(['barang', 'pelapor', 'perbaikan'])
            ->when(auth()->user()->level == 'eksekutor', function ($q){
                $q->withWhereHas('perbaikan', function ($query){
                    return $query->where('eksekutor_id', auth()->user()->id);
                });
            })
            ->when(isset($tgl), function ($q) use ($tgl) {
                return $q->whereBetween('crashes.tgl', $tgl);
            })
            ->when(auth()->user()->level == 'pegawai', function ($q){
                return $q->where('crashes.user_id', auth()->user()->id);
            })
            ->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="dropdown">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdown-default-outline-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-outline-primary" style="">';
                    $btn .= '<a class="dropdown-item" href="'. route('crash.show', $row->id).'"><i class="si si-eye me-1"></i>Detail</a>';
                    if($row->status == 'pending'){
                        $btn .= '<a class="dropdown-item" href="'. route('crash.edit', $row->id).'"><i class="si si-note me-1"></i>Ubah</a>';
                        $btn .= '<a class="dropdown-item" href="javascript:void(0)"><i class="si si-trash me-1"></i>Hapus</a>';
                    }
                    $btn .= '</div></div>';
                    return $btn;
                })
                ->editColumn('tgl', function ($row) {
                    return Carbon::parse($row->tgl)->translatedFormat('d F Y');
                })
                ->editColumn('status', function ($row) {
                    if($row->status == 'pending'){
                        return '<span class="badge bg-warning">Pending</span>';
                    }else if($row->status == 'proses'){
                        return '<span class="badge bg-primary">Proses</span>';
                    }else if($row->status == 'selesai'){
                        return '<span class="badge bg-success">Selesai</span>';
                    }
                })
                ->rawColumns(['action', 'status']) 
                ->make(true);
        }

        return view('kerusakan.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $barang = Barang::orderBy('nama', 'ASC')->get();
        $kategori = Kategori::orderBy('nama', 'ASC')->get();
        $lokasi = Lokasi::orderBy('nama', 'ASC')->get();

        return view('kerusakan.form',[
            'barang' => $barang,
            'kategori' => $kategori,
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
            'barang_id' => 'required',
            'kategori_id' => 'required',
            'tgl' => 'required|string',
            'keterangan' => 'required',
        ];

        $pesan = [
            'barang_id.required' => 'Barang Wajib Diisi!',
            'kategori_id.required' => 'Kategori Wajib Diisi!',
            'tgl.required' => 'Tanggal Laporan Wajib Diisi!',
            'keterangan.required' => 'Keterangan Barang Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{
                // Ambil user yang sedang login
                $user = auth()->user();
                
                // Cek apakah level user bukan 'admin', lalu ambil lokasi_id dari user
                $lokasi_id = $user->level != 'admin' ? $user->lokasi_id : $request->lokasi_id;
                
                $data = new Kerusakan();
                $data->nomor = $this->getNumber();
                $data->tgl = $request->tgl;
                $data->lokasi_id = $lokasi_id;
                $data->kategori_id = $request->kategori_id;
                $data->barang_id = $request->barang_id;
                $data->keterangan = $request->keterangan;
                $data->user_id = auth()->user()->id;
                $data->save();

                $barang = Barang::where('id', $request->barang_id)->first();
                $barang->status = 'rusak';
                $barang->save();

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'errors' => $e,
                    'pesan' => 'Error Menyimpan Data Anggota',
                ]);
            }

            DB::commit();

            return redirect()->route('crash.index');
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
        $data = Kerusakan::select('crashes.*')->with(['barang', 'pelapor'])->where('id', $id)->first();

        return view('kerusakan.show',[
            'data' => $data
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
        $data = Kerusakan::where('id', $id)->first();
        $barang = Barang::orderBy('nama', 'ASC')->get();
        $lokasi = Lokasi::orderBy('nama', 'ASC')->get();

        return view('kerusakan.edit',[
            'barang' => $barang,
            'data' => $data,
            'lokasi' => $lokasi
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
            'barang_id' => 'required',
            'lokasi_id' => 'required',
            'kategori_id' => 'required',
            'tgl' => 'required|string',
            'keterangan' => 'required',
        ];

        $pesan = [
            'barang_id.required' => 'Barang Wajib Diisi!',
            'lokasi_id.required' => 'Lokasi Wajib Diisi!',
            'kategori_id.required' => 'Kategori Wajib Diisi!',
            'tgl.required' => 'Tanggal Laporan Wajib Diisi!',
            'keterangan.required' => 'Keterangan Barang Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = Kerusakan::where('id', $id)->first();
                $data->tgl = $request->tgl;
                $data->lokasi_id = $request->lokasi_id;
                $data->kategori_id = $request->kategori_id;
                $data->barang_id = $request->barang_id;
                $data->keterangan = $request->keterangan;
                $data->user_id = auth()->user()->id;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();
            return redirect()->route('crash.index');
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

            $data = Kerusakan::where('id', $id)->first();
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
    
    private function getNumber()
    {
        $q = Kerusakan::select(DB::raw('MAX(RIGHT(nomor,5)) AS kd_max'));

        $code = 'KSN/';
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

    public function export(Request $request)
{
    $tgl = explode(" - ", $request->tgl);
    $status = $request->status;
    $user = Auth::user(); // Mendapatkan user yang sedang login
    
    $data = Kerusakan::select('crashes.*')->with(['barang', 'pelapor'])
        ->when(isset($tgl), function ($q) use ($tgl) {
            return $q->whereBetween('crashes.tgl', $tgl);
        })->when(isset($status), function ($q) use ($status) {
            return $q->where('crashes.status', $status);
        })
        // Tambahkan kondisi ini untuk user level "pegawai"
        ->when($user->level == 'pegawai', function ($q) use ($user) {
            return $q->where('crashes.user_id', $user->id); // Asumsi 'user_id' adalah ID pemilik kerusakan
        })
        ->latest()->get();

    $pdf = FacadePdf::loadView('kerusakan.export', [
        'data' => $data,
        'tgl' => $tgl,
        'status' => $status,
    ]);

    return $pdf->stream('Laporan Kerusakan.pdf');
}

}
