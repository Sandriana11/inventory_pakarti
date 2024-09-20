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
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;

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
            $tahun = $request->tahun;
            
            $kerusakan = Kerusakan::where('status', '!=', 'selesai')->get()->pluck('barang_id');

            $data = Barang::select('barangs.*')->with(['kategori', 'lokasi', 'user'])
            ->when(isset($lokasi_id), function ($q) use ($lokasi_id) {
                return $q->where('lokasi_id', $lokasi_id);
            })
            ->when(isset($kategori_id), function ($q) use ($kategori_id) {
                return $q->where('kategori_id', $kategori_id);
            })
            ->when(isset($tahun), function ($q) use ($tahun) {
                return $q->where('tahun', $tahun);
            })
            ->whereNotIn('id', $kerusakan)
            ->orderBy('tahun', 'DESC')
            ->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('user', function($row) {
                    return $row->user->name . ' - ' . $row->user->jabatan->nama; // Menampilkan nama user dan jabatan
                })
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
        
        $tahun = Barang::orderBy('tahun', 'ASC')->pluck('tahun')->unique(); // Ambil tahun unik
        $kategori = Kategori::orderBy('nama', 'ASC')->get();
        $lokasi = Lokasi::orderBy('nama', 'ASC')->get();
        return view('barang.index',[
            'kategori' => $kategori,
            'lokasi' => $lokasi,
            'tahun' => $tahun,
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
        $user = User::orderBy('name', 'ASC')->get();

        return view('barang.form',[
            'kategori' => $kategori,
            'lokasi' => $lokasi,
            'user' => $user
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
            'nama' => 'required',
            'kategori_id' => 'required',
            'lokasi_id' => 'required',
            'user_id' => 'required',
            'tahun' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Barang Wajib Diisi!',
            'lokasi_id.required' => 'Lokasi Wajib Diisi!',
            'kategori_id.required' => 'Kategori Wajib Diisi!',
            'user_id.required' => 'Penanggung Jawab Wajib Diisi!',
            'tahun.required' => 'Tahun Barang Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()) {
            return back()->withInput()->withErrors($validator->errors());
        } else {
            DB::beginTransaction();
            try {
                // Ambil kode lokasi dan kode kategori
                $lokasi = Lokasi::where('id', $request->lokasi_id)->first();
                $kategori = Kategori::where('id', $request->kategori_id)->first();
                
                // Kode lokasi dan kode kategori
                $kode_lokasi = $lokasi->kode;
                $kode_kategori = $kategori->kode;
                
                // Tahun saat ini
                $tahun = $request->tahun;
                
                // Inisialisasi nomor urut default
                $nomor_urut = 1;
                
                // Ambil nomor urut terakhir dari database
                $q = Barang::whereYear('created_at', $tahun)
                            ->select(DB::raw('MAX(RIGHT(nomor, 3)) AS kd_max'));
                
                if ($q->count() > 0) {
                    foreach ($q->get() as $k) {
                        // Menentukan nomor urut berikutnya
                        $nomor_urut = (int)$k->kd_max + 1;
                    }
                }
                
                // Format nomor urut menjadi 3 digit
                $nomor_urut_terformat = sprintf("%03s", $nomor_urut);
                
                // Gabungkan semua bagian
                $nomor = "{$kode_lokasi}.{$kode_kategori}.{$tahun}.{$nomor_urut_terformat}";
        
                // Simpan data barang
                $data = new Barang();
                $data->nomor = $nomor;
                $data->tahun = $request->tahun;
                $data->nama = $request->nama;
                $data->kategori_id = $request->kategori_id;
                $data->lokasi_id = $request->lokasi_id;
                $data->user_id = $request->user_id;
                $data->deskripsi = $request->deskripsi;
                $data->save();
                
            } catch (\QueryException $e) {
                DB::rollback();
                // Tangani kesalahan jika terjadi
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
        $user = User::orderBy('name', 'ASC')->get();

        return view('barang.edit',[
            'data' => $data,
            'kategori' => $kategori,
            'lokasi' => $lokasi,
            'user' => $user
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
            'tahun' => 'required',
        ];

        $pesan = [
            'lokasi_id.required' => 'Lokasi Wajib Diisi!',
            'kategori_id.required' => 'Kategori Wajib Diisi!',
            'nama.required' => 'Nama Barang Wajib Diisi!',
            'tahun.required' => 'Tahun Barang Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = Barang::where('id', $id)->first();
                $data->nama = $request->nama;
                $data->tahun = $request->tahun;
                $data->kategori_id = $request->kategori_id;
                $data->lokasi_id = $request->lokasi_id;
                $data->user_id = $request->user_id;
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

    public function select(Request $request)
{
    $lokasi_id = $request->id;
    $kategori_id = $request->kategori;
    $search = $request->search;
    $tahun = $request->tahun; // Tambahkan parameter tahun

    $data = Barang::select('id', DB::raw('CONCAT(nomor, " : ", nama) as text'))
        ->when(isset($lokasi_id), function ($q) use ($lokasi_id) {
            return $q->where('lokasi_id', $lokasi_id);
        })
        ->when(isset($kategori_id), function ($q) use ($kategori_id) {
            return $q->where('kategori_id', $kategori_id);
        })
        ->when(isset($tahun), function ($q) use ($tahun) {
            // Asumsikan bahwa tahun disimpan di kolom 'created_at'
            return $q->where('tahun', $tahun);
        })
        ->when(isset($search), function ($q) use ($search) {
            return $q->where('nama', 'LIKE', '%'.$search.'%');
        })
        ->latest()
        ->get();

    return response()->json($data);
}


public function export(Request $request)
{
    $lokasiId = $request->lokasi_id;
    $kategoriId = $request->kategori_id; 
    $tahun = $request->tahun; 
    $status = $request->status;
    $tgl = $request->tgl;

    // Ambil data berdasarkan filter
    $data = Barang::select('nomor', 'nama', 'deskripsi', 'kategori_id', 'lokasi_id', 'tahun', 'user_id')
        ->with(['kategori', 'lokasi', 'user.jabatan']) // Pastikan relasi sudah ada di model Barang
        ->when($lokasiId, function ($query) use ($lokasiId) {
            return $query->where('lokasi_id', $lokasiId);
        })
        ->when($kategoriId, function ($query) use ($kategoriId) {
            return $query->where('kategori_id', $kategoriId);
        })
        ->when($tahun, function ($query) use ($tahun) {
            return $query->where('tahun', $tahun);
        })
        ->when($status, function ($query) use ($status) {
            return $query->where('status', $status);
        })
        ->get();
        
    // Format data untuk view PDF
    $pdf = Pdf::loadView('barang.export', [
        'data' => $data,
        'tgl' => $tgl,
        'status' => $status
    ]);

    return $pdf->stream('Laporan Inventaris.pdf');
}
}
