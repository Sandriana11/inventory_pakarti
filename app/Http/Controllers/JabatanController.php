<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use DataTables;

class JabatanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Jabatan::select('jabatans.*')->withCount('pegawai')->orderBy('kode', 'ASC')->latest()->get();
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="dropdown">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdown-default-outline-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-outline-primary" style="">';
                        $btn .= '<a class="dropdown-item" href="'. route('jabatan.edit', $row->id).'"><i class="si si-note me-1"></i>Ubah</a>';
                        $btn .= '<a class="dropdown-item" href="javascript:void(0)" onclick="hapus('. $row->id.')"><i class="si si-trash me-1"></i>Hapus</a>';
                    $btn .= '</div></div>';
                    return $btn;
                })
                ->editColumn('created_at', function ($row) {
                    return Carbon::parse($row->created_at)->translatedFormat('d F Y');
                })
                ->rawColumns(['action']) 
                ->make(true);
        }
        return view('jabatan.index');
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
            'kode' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Jabatan Wajib Diisi!',
            'kode.required' => 'Kode Wajib Diisi!',
        ];


        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = new Jabatan();
                $data->nama = $request->nama;
                $data->kode = $request->kode;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();
            return redirect()->route('jabatan.index');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Jabatan::where('id', $id)->first();

        return view('jabatan.edit',[
            'data' => $data
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
            'nama' => 'required',
            'kode' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Jabatan Wajib Diisi!',
            'kode.required' => 'Kode Wajib Diisi!',
        ];


        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = Jabatan::where('id', $id)->first();
                $data->nama = $request->nama;
                $data->kode = $request->kode;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                dd($e);
            }

            DB::commit();
            return redirect()->route('jabatan.index');
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

            $data = Jabatan::where('id', $id)->first();
            $data->delete();

        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'errors' => $e,
                'pesan' => 'Data Gagal Dihapus!',
            ]);
        }

        DB::commit();
        return response()->json([
            'fail' => false,
            'pesan' => 'Data Berhasil Dihapus!',
        ]);
    }
}
