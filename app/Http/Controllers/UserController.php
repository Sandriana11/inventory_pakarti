<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;
use App\Models\Bidang;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use DataTables;
use Carbon\Carbon;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $bidang_id = $request->bidang_id;
            $data = User::select('users.*')
            ->when(isset($bidang_id), function ($q) use ($bidang_id) {
                return $q->where('bidang_id', $bidang_id);
            })
            ->with(['bidang'])->latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<div class="dropdown">
                        <button type="button" class="btn btn-outline-primary btn-sm dropdown-toggle" id="dropdown-default-outline-primary" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Aksi
                        </button>
                        <div class="dropdown-menu fs-sm" aria-labelledby="dropdown-default-outline-primary" style="">';
                        $btn .= '<a class="dropdown-item" href="'. route('user.edit', $row->id).'"><i class="si si-note me-1"></i>Ubah</a>';
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
        $bidang = Bidang::orderBy('nama', 'ASC')->get();
        return view('users.index',[
            'bidang' => $bidang
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = User::latest()->get()->pluck('nip');
        $bidang = Bidang::orderBy('nama', 'ASC')->get();

        return view('users.form',[
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
        // dd($request->all());
        $rules = [
            'nip' => 'required|numeric|max_digits:18|unique:users,nip',
            'nama' => 'required|string',
            'bidang_id' => 'required',
            'username' => 'required|unique:users,username',
            'password' => 'required|same:password_confirmation',
            'password_confirmation' => 'required',
            'level' => 'required'
        ];

        $pesan = [
            'nip.required' => 'NIP Wajib Diisi!',
            'nip.numeric' => 'NIP Hanya Boleh Angka!',
            'nip.unique' => 'NIP Sudah Terdaftar!',
            'nip.max_digits' => 'NIP Maksimal 18 Angka!',
            'username.required' => 'Username Wajib Diisi!',
            'username.unique' => 'Username Sudah Terdaftar!',
            'nama.required' => 'Nama Lengkap Wajib Diisi!',
            'bidang_id.required' => 'Bidang Wajib Diisi!',
            'password.required' => 'Password Wajib Diisi!',
            'password.same' => 'Konfirmasi Password Tidak Sama!',
            'password_confirmation' => 'Konfirmasi Password Wajib Diisi',
            'level' => 'Konfirmasi Password Wajib Diisi',
        ];


        $validator = Validator::make($request->all(), $rules, $pesan);
        if ($validator->fails()){
            return back()->withInput()->withErrors($validator->errors());
        }else{
            DB::beginTransaction();
            try{

                $data = new User();
                $data->nip = $request->nip;
                $data->name = $request->nama;
                $data->username = $request->username;
                $data->bidang_id = $request->bidang_id;
                $data->password = Hash::make($request->password);
                $data->level = $request->level;
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

            return redirect()->route('user.index');
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
        $data = User::where('id', $id)->first();
        $bidang = Bidang::orderBy('nama', 'ASC')->get();

        return view('users.edit',[
            'data' => $data,
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
            'nip' => 'required',
            'nama' => 'required|string',
            'bidang' => 'required',
            'hp' => 'required',
        ];

        $pesan = [
            'nip.required' => 'NIP Wajib Diisi!',
            'nip.exists' => 'NIP Sudah Terdaftar!',
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

            $data = User::where('id', $id)->first();
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
}
