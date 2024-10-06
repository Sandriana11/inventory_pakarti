<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Kerusakan;
use App\Models\Lokasi;
use App\Models\Maintener;
use DataTables;
use Carbon\Carbon;

class DashboardController extends Controller
{

    public function index(Request $req)
    {
        $start = isset($req->start) ? $req->start : Carbon::today()->subDay(7)->format('Y-m-d');
        $end = isset($req->end) ? $req->end : Carbon::today()->format('Y-m-d');

        $ovr = Collect([
            'crash' => Kerusakan::where('status', 'pending')->whereBetween('tgl',[$start,$end])->get()->count(),
            'proses' => Kerusakan::where('status', 'proses')->whereBetween('tgl',[$start,$end])->get()->count(),
            'selesai' => Kerusakan::where('status', 'selesai')->whereBetween('tgl',[$start,$end])->get()->count(),
            'eksekutor' => Maintener::get()->count(),
        ]);

        if ($req->ajax()) {

            $lokasi_id = $req->lokasi_id;
            $kategori_id = $req->kategori_id;
            $tahun = $req->tahun;
            
            $kerusakan = Kerusakan::where('status', '!=', 'selesai')->get()->pluck('barang_id');

            $user = auth()->user();

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
                ->where('user_id', $user->id)
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

        return view('dashboard',[
            'ovr' => $ovr,
            'kategori' => $kategori,
            'lokasi' => $lokasi,
            'tahun' => $tahun,
        ]);
    }

    
    public function notifikasi(Request $req)
    {
        $data = Kerusakan::with('pelapor:id,name')->where('status', 'pending')->orderBy('created_at', 'DESC')->get();
        return response()->json($data);
    }

}

