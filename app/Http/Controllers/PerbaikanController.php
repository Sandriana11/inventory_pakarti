<?php

namespace App\Http\Controllers;

use App\Models\Kerusakan;
use App\Models\Perbaikan;
use App\Models\User;
use App\Models\Barang;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;
use PDF;
use App\DataTables\PerbaikanDataTable;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class PerbaikanController extends Controller
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

            $data = Perbaikan::select('maintenance.*')->with(['eksekutor','kerusakan'])
            ->when(auth()->user()->level == 'pegawai', function ($q){
                $q->withWhereHas('kerusakan', function ($query){
                    return $query->where('user_id', auth()->user()->id);
                });
            })
            ->when(isset($tgl), function ($q) use ($tgl) {
                return $q->whereBetween('maintenance.tgl', $tgl);
            })->when(auth()->user()->level == 'eksekutor', function ($q){
                return $q->where('eksekutor_id', auth()->user()->id);
            })->latest()->get();

            return Datatables::of($data)->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="dropdown">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdown-default-outline-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-outline-primary" style="">';
                    $btn .= '<a class="dropdown-item" href="'. route('maintenance.show', $row->id).'"><i class="si si-eye me-1"></i>Detail</a>';
                    if($row->status == 0 && in_array(auth()->user()->level , ['admin'])){
                        $btn .= '<a class="dropdown-item" href="'. route('maintenance.edit', $row->id).'"><i class="si si-note me-1"></i>Ubah</a>';
                        $btn .= '<a class="dropdown-item" href="javascript:void(0)"><i class="si si-trash me-1"></i>Hapus</a>';
                    }
                    $btn .= '</div></div>';
                    return $btn;
                })
                ->editColumn('tgl', function ($row) {
                    return Carbon::parse($row->tgl)->translatedFormat('d F Y');
                })
                ->editColumn('target', function ($row) {
                    return Carbon::parse($row->target)->translatedFormat('d F Y');
                })
                ->editColumn('status', function ($row) {
                    if($row->status == 0){
                        return '<span class="badge bg-warning">Pending</span>';
                    }else{
                        return '<span class="badge bg-success">Selesai</span>';
                    }
                })
                ->rawColumns(['status','action'])
                ->make(true);
        }

        return view('perbaikan.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kerusakan = Kerusakan::where('status', 'pending')->orderBy('tgl', 'ASC')->get();
        $eksekutor = User::where('level', 'eksekutor')->orderBy('name', 'ASC')->get();

        return view('perbaikan.form',[
            'kerusakan' => $kerusakan,
            'eksekutor' => $eksekutor
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
            'crash_id' => 'required',
            'eksekutor_id' => 'required',
            'tgl' => 'required',
            'target' => 'required',
        ];

        $pesan = [
            'crash_id.required' => 'Laporan Kerusakan Wajib Diisi!',
            'eksekutor_id.required' => 'Eksekutor Wajib Diisi!',
            'tgl.required' => 'Tanggal Wajib Diisi!',
            'target.required' => 'Tanggal Selesai Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = new Perbaikan();
                $data->nomor = $this->getNumber();
                $data->crash_id = $request->crash_id;
                $data->eksekutor_id = $request->eksekutor_id;
                $data->tgl = $request->tgl;
                $data->target = $request->target;
                $data->user_id = auth()->user()->id;
                $data->save();

                $c = Kerusakan::where('id', $request->crash_id)->first();
                $c->status = 'proses';
                $c->save();

                $barang = Barang::where('id', $c->barang_id)->first();
                $barang->status = 'diperbaiki';
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
        $data = Perbaikan::select('maintenance.*')->with(['eksekutor','kerusakan'])
        ->where('maintenance.id', $id)->first();

        return view('perbaikan.show',[
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
        $data = Perbaikan::where('id', $id)->first();
        $kerusakan = Kerusakan::orderBy('tgl', 'ASC')->get();
        $eksekutor = User::orderBy('name', 'ASC')->get();

        return view('perbaikan.form',[
            'data' => $data,
            'kerusakan' => $kerusakan,
            'eksekutor' => $eksekutor
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
            'crash_id' => 'required',
            'eksekutor_id' => 'required',
            'tgl' => 'required',
            'target' => 'required',
        ];

        $pesan = [
            'crash_id.required' => 'Laporan Kerusakan Wajib Diisi!',
            'eksekutor_id.required' => 'Eksekutor Wajib Diisi!',
            'tgl.required' => 'Tanggal Wajib Diisi!',
            'target.required' => 'Tanggal Selesai Wajib Diisi!',
        ];

        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = Perbaikan::where('id', $id)->first();
                $data->crash_id = $request->crash_id;
                $data->eksekutor_id = $request->eksekutor_id;
                $data->tgl = $request->tgl;
                $data->target = $request->target;
                $data->user_id = auth()->user()->id;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();

            return redirect()->route('maintenance.show', $id);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Perbaikan  $perbaikan
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try{

            $data = Perbaikan::where('id', $id)->first();
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
        // dd($request->all());
        DB::beginTransaction();
        try{

            $data = Perbaikan::where('id', $id)->first();
            $data->status = 1;
            $data->tgl_selesai = Carbon::now();
            $data->save();
            
            $c = Kerusakan::where('id', $data->crash_id)->first();
            $c->status = 'selesai';
            $c->save();

            
            $b = Barang::where('id', $c->barang_id)->first();
            if($request->status == 'rusak'){
                $b->status = 'tersedia';
            }else{
                $b->status = 'rusak';
            }
            $b->save();

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
        $q = Perbaikan::select(DB::raw('MAX(RIGHT(nomor,5)) AS kd_max'));

        $code = 'PBN/';
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
        $tgl = explode(" - ",$request->tgl);
        $status = $request->status;
        // dd($tgl);
        $data = Perbaikan::select('maintenance.*')->with(['kerusakan', 'eksekutor'])
        ->when(isset($tgl), function ($q) use ($tgl) {
            return $q->whereBetween('maintenance.tgl', $tgl);
        })->when(isset($status), function ($q) use ($status) {
            return $q->where('maintenance.status', $status);
        })->latest()->get();

        $pdf = FacadePdf::loadView('perbaikan.export', [
            'data' => $data,
            'tgl' => $tgl,
            'status' => $status,
        ]);

        return $pdf->download('Laporan Perbaikan.pdf');
        
    }
}
