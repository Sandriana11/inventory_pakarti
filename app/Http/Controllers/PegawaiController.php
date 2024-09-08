<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\PegawaiDataTable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use App\Models\Pegawai;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PegawaiDataTable $dataTable)
    {

        return $dataTable->render('pegawai.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('pegawai.form');
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
            'nip' => 'required|numeric|max_digits:18|unique:pegawai,nip',
            'nama' => 'required|string',
            'bidang' => 'required',
            'hp' => 'required',
        ];

        $pesan = [
            'nip.required' => 'NIP Wajib Diisi!',
            'nip.numeric' => 'NIP Hanya Boleh Angka!',
            'nip.unique' => 'NIP Sudah Terdaftar!',
            'nip.max_digits' => 'NIP Maksimal 18 Angka!',
            'nama.required' => 'Nama Lengkap Wajib Diisi!',
            'bidang.required' => 'Bidang Wajib Diisi!',
            'hp.required' => 'No Handphone Wajib Diisi!',
        ];


        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = new Pegawai();
                $data->nip = $request->nip;
                $data->nama = $request->nama;
                $data->bidang = $request->bidang;
                $data->hp = $request->hp;
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
            if($request->level_id > 2){
                return redirect()->route('user.dukungan.relawan');
            }

            return redirect()->route('pegawai.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        $data = Pegawai::where('nip', $id)->first();
        if ($request->ajax()) {
            return response()->json($data);
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
        $data = Pegawai::where('nip', $id)->first();

        return view('pegawai.form',[
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
            'nip' => 'required|unique:pegawai,nip',
            'nama' => 'required|string',
            'bidang' => 'required',
            'hp' => 'required',
        ];

        $pesan = [
            'nip.required' => 'NIP Wajib Diisi!',
            'nip.unique' => 'NIP Sudah Terdaftar!',
            'nama.required' => 'Nama Lengkap Wajib Diisi!',
            'bidang.required' => 'Bidang Wajib Diisi!',
            'hp.required' => 'No Handphone Wajib Diisi!',
        ];


        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = Pegawai::where('nip', $id)->first();
                $data->nip = $request->nip;
                $data->nama = $request->nama;
                $data->bidang = $request->bidang;
                $data->hp = $request->hp;
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
            if($request->level_id > 2){
                return redirect()->route('user.dukungan.relawan');
            }

            return redirect()->route('pegawai.index');
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

            $data = Pegawai::where('nip', $id)->first();
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

    public function data(Request $request)
    {
        $data = Pegawai::orderBy('nama', 'ASC')->get();

        return response()->json($data);
    }
}
