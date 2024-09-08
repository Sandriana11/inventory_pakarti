<?php

namespace App\Http\Controllers;

use App\Models\Maintener;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\DataTables\MaintenerDatatable;

class MaintenerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(MaintenerDatatable $dataTable)
    {
        return $dataTable->render('maintener.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pegawai = Pegawai::orderBy('nama', 'ASC')->get();

        return view('maintener.form',[
            'pegawai' => $pegawai
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
            'hp' => 'required',
            'alamat' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Lengkap Wajib Diisi!',
            'hp.required' => 'No Handphone Wajib Diisi!',
            'alamat.required' => 'Alamat Wajib Diisi!',
        ];

        if($request->tipe == 'internal'){
            $rules['nip'] = 'required|unique:maintener,nip';
            $pesan['nip.required'] = 'Pegawai Wajib Diisi!';
            $pesan['nip.unique'] = 'Pegawai Sudah Terdaftar!';
        }


        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = new Maintener();
                $data->tipe = $request->tipe;
                $data->nip = $request->nip;
                $data->nama = $request->nama;
                $data->hp = $request->hp;
                $data->alamat = $request->alamat;

                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'errors' => $e,
                    'pesan' => 'Error Menyimpan Data Anggota',
                ]);
            }

            DB::commit();
            return redirect()->route('maintener.index');
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Maintener::where('id', $id)->first();
        $pegawai = Pegawai::orderBy('nama', 'ASC')->get();

        return view('maintener.form',[
            'pegawai' => $pegawai,
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
            'hp' => 'required',
            'alamat' => 'required',
        ];

        $pesan = [
            'nama.required' => 'Nama Lengkap Wajib Diisi!',
            'hp.required' => 'No Handphone Wajib Diisi!',
            'alamat.required' => 'Alamat Wajib Diisi!',
        ];

        if($request->tipe == 'internal'){
            $rules['nip'] = 'required|unique:maintener,nip';
            $pesan['nip.required'] = 'Pegawai Wajib Diisi!';
            $pesan['nip.unique'] = 'Pegawai Sudah Terdaftar!';
        }


        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = Maintener::where('id', $id)->first();
                $data->tipe = $request->tipe;
                $data->nip = $request->nip;
                $data->nama = $request->nama;
                $data->hp = $request->hp;
                $data->alamat = $request->alamat;
                $data->save();

            }catch(\QueryException $e){
                DB::rollback();
                return response()->json([
                    'fail' => true,
                    'errors' => $e,
                    'pesan' => 'Error Menyimpan Data Anggota',
                ]);
            }

            DB::commit();
            return redirect()->route('maintener.index');
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

            $data = Maintener::where('id', $id)->first();
            $data->delete();

        }catch(\QueryException $e){
            DB::rollback();
            return response()->json([
                'fail' => true,
                'errors' => $e,
                'pesan' => 'Data Maintainer Gagal Dihapus!',
            ]);
        }

        DB::commit();
        return response()->json([
            'fail' => false,
            'pesan' => 'Data Maintainer Berhasil Dihapus!',
        ]);
    }
}
