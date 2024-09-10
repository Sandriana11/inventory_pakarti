<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;
use App\Models\Kategori;
use App\Models\Barang;
use App\Models\Lokasi;
use App\Models\Bidang;
use App\Models\Pengadaan;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Carbon\Carbon;

class PengadaanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $data = Pengadaan::select('pengadaan.*')->with(['kategori', 'lokasi'])->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="dropdown">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdown-default-outline-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-outline-primary" style="">';
                        $btn .= '<a class="dropdown-item" href="'. route('pengadaan.show', $row->id).'"><i class="si si-eye me-1"></i>Detail</a>';
                        $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="hapus('. $row->id.')"><i class="si si-trash me-1"></i>Hapus</a>';
                    $btn .= '</div></div>';
                    return $btn;
                })
                ->editColumn('tgl', function ($row) {
                    return Carbon::parse($row->tgl)->translatedFormat('d F Y');
                })
                ->editColumn('status', function ($row) {
                    if($row->status == 'draft'){
                        return '<span class="badge bg-warning">Pending</span>';
                    }else if($row->status == 'setuju'){
                        return '<span class="badge bg-primary">Setuju</span>';
                    }else if($row->status == 'tolak'){
                        return '<span class="badge bg-danger">Ditolak</span>';
                    }else{
                        return '<span class="badge bg-secondary">Batal</span>';
                    }
                })
                ->rawColumns(['action', 'status']) 
                ->make(true);
        }
        return view('pengadaan.index');
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

        return view('pengadaan.form',[
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
            'tgl' => 'required',
            'supplier' => 'required',
            'tipe' => 'required',
            'jumlah' => 'required',
            'lokasi_id' => 'required',
            'kategori_id' => 'required',
            'nama' => 'required',
        ];

        $pesan = [
            'lokasi_id.required' => 'Lokasi Wajib Diisi!',
            'supplier.required' => 'Supplier Wajib Diisi!',
            'tipe.required' => 'Tipe Wajib Diisi!',
            'kategori_id.required' => 'Kategori Wajib Diisi!',
            'nama.required' => 'Nama Barang Wajib Diisi!',
            'tgl.required' => 'Tanggal Wajib Diisi!',
            'jumlah.required' => 'Jumlah Barang Wajib Diisi!',
        ];


        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = new Pengadaan();
                $data->nomor = $this->getNumber();
                $data->tgl = $request->tgl;
                $data->supplier = $request->supplier;
                $data->tipe = $request->tipe;
                $data->nama = $request->nama;
                $data->kategori_id = $request->kategori_id;
                $data->lokasi_id = $request->lokasi_id;
                $data->harga = $request->harga;
                $data->qty = $request->jumlah;
                $data->total = $request->total;
                $data->deskripsi = $request->deskripsi;
                $data->status = 'draft';
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                // return back()->withInput()->withErrors($e);
                dd($e);
            }

            DB::commit();
            return redirect()->route('pengadaan.index');
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
        $data = Pengadaan::where('id', $id)->first();
        $kategori = Kategori::orderBy('nama', 'ASC')->get();
        $lokasi = Lokasi::orderBy('nama', 'ASC')->get();
        $bidang = Bidang::orderBy('nama', 'ASC')->get();

        return view('pengadaan.show',[
            'data' => $data,
            'kategori' => $kategori,
            'lokasi' => $lokasi,
            'bidang' => $bidang
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

        return view('pengadaan.edit',[
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

            $data = Pengadaan::where('id', $id)->first();
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

        try {
            // Ambil data pengadaan berdasarkan ID
            $data = Pengadaan::findOrFail($id);

            // Ubah status berdasarkan input
            $data->status = $request->input('status');

            if ($request->status == 'setuju') {
                for ($i = 1; $i <= $data->qty; $i++) {
                    // Cari Lokasi dan Kategori berdasarkan ID
                    $lokasi = Lokasi::findOrFail($data->lokasi_id);
                    $kategori = Kategori::findOrFail($data->kategori_id);

                    // Buat nomor baru
                    $kode_lokasi = $lokasi->kode;
                    $kode_kategori = $kategori->kode;
                    $tahun = Carbon::parse($data->tgl)->year;
                    $nomor_urut = Barang::whereYear('created_at', $tahun)
                                        ->max(DB::raw('RIGHT(nomor, 3)')) + 1;

                    $nomor_urut_terformat = sprintf("%03s", $nomor_urut);
                    $nomor = "{$kode_lokasi}.{$kode_kategori}.{$tahun}.{$nomor_urut_terformat}";

                    // Simpan data barang baru
                    Barang::create([
                        'nomor' => $nomor,
                        'nama' => $data->nama,
                        'tahun' => $tahun,
                        'kategori_id' => $data->kategori_id,
                        'lokasi_id' => $data->lokasi_id,
                        'deskripsi' => $data->deskripsi,
                    ]);
                }
            }

            // Simpan perubahan status data pengadaan
            $data->save();
            DB::commit();

            // Tambahkan flash message sukses
            return redirect()->back()->with('success', 'Status berhasil diperbarui!');

        } catch (\Exception $e) {
            DB::rollback();
            // Tambahkan flash message error
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    private function getNumber()
    {
        $q = Pengadaan::select(DB::raw('MAX(RIGHT(nomor,5)) AS kd_max'));

        $code = 'PGN/';
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
