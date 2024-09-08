<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kerusakan;
use App\Models\Maintener;
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



        return view('dashboard',[
            'ovr' => $ovr,
        ]);
    }

    
    public function notifikasi(Request $req)
    {
        $data = Kerusakan::with('pelapor:id,name')->where('status', 'pending')->orderBy('created_at', 'DESC')->get();
        return response()->json($data);
    }

}

