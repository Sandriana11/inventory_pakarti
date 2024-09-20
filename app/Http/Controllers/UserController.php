<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\DataTables\UsersDataTable;
use App\Models\Jabatan;
use App\Models\Lokasi;
use App\Models\Pegawai;
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

            $lokasi_id = $request->lokasi_id;
            $data = User::select('users.*')
            ->when(isset($lokasi_id), function ($q) use ($lokasi_id) {
                return $q->where('lokasi_id', $lokasi_id);
            })
            ->with(['jabatan', 'lokasi'])->latest()->get();

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
        $lokasi = Lokasi::orderBy('nama', 'ASC')->get();
        return view('users.index',[
            'lokasi' => $lokasi
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
        $jabatan = Jabatan::orderBy('nama', 'ASC')->get();
        $lokasi = Lokasi::orderBy('nama', 'ASC')->get();

        return view('users.form',[
            'jabatan' => $jabatan,
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
        $rules = [
            'nip' => 'required|numeric|max_digits:18|unique:users,nip',
            'nama' => 'required|string',
            'jabatan_id' => 'required',
            'lokasi_id' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email', // Menambahkan aturan validasi email
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
            'jabatan_id.required' => 'Jabatan Wajib Diisi!',
            'lokasi_id.required' => 'Departement Wajib Diisi!',
            'email.required' => 'Email Wajib Diisi!',
            'email.email' => 'Format Email Tidak Valid!',
            'email.unique' => 'Email Sudah Terdaftar!',
            'password.required' => 'Password Wajib Diisi!',
            'password.same' => 'Konfirmasi Password Tidak Sama!',
            'password_confirmation.required' => 'Konfirmasi Password Wajib Diisi',
            'level.required' => 'Level Wajib Diisi!',
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
                $data->email = $request->email; 
                $data->jabatan_id = $request->jabatan_id;
                $data->lokasi_id = $request->lokasi_id;
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
        $jabatan = Jabatan::orderBy('nama', 'ASC')->get();
        $lokasi = Lokasi::orderBy('nama', 'ASC')->get();

        return view('users.edit',[
            'data' => $data,
            'jabatan' => $jabatan,
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
    // Aturan validasi
    $rules = [
        'nip' => 'required|numeric|max_digits:18|unique:users,nip,' . $id, // Unik kecuali untuk user yang sedang diupdate
        'nama' => 'required|string',
        'jabatan_id' => 'required',
        'lokasi_id' => 'required',
        'username' => 'required|unique:users,username,' . $id, // Unik kecuali untuk user yang sedang diupdate
        'email' => 'required|email|unique:users,email,' . $id, // Menambahkan aturan validasi email, unik kecuali user yang sedang diupdate
        'password' => 'nullable|same:password_confirmation', // Password bisa kosong jika tidak ingin diubah
        'password_confirmation' => 'nullable',
        'level' => 'required'
    ];

    // Pesan error
    $pesan = [
        'nip.required' => 'NIP Wajib Diisi!',
        'nip.numeric' => 'NIP Hanya Boleh Angka!',
        'nip.unique' => 'NIP Sudah Terdaftar!',
        'nip.max_digits' => 'NIP Maksimal 18 Angka!',
        'username.required' => 'Username Wajib Diisi!',
        'username.unique' => 'Username Sudah Terdaftar!',
        'nama.required' => 'Nama Lengkap Wajib Diisi!',
        'jabatan_id.required' => 'Jabatan Wajib Diisi!',
        'lokasi_id.required' => 'Departemen Wajib Diisi!',
        'email.required' => 'Email Wajib Diisi!',
        'email.email' => 'Format Email Tidak Valid!',
        'email.unique' => 'Email Sudah Terdaftar!',
        'password.same' => 'Konfirmasi Password Tidak Sama!',
        'level.required' => 'Level Wajib Diisi!',
    ];

    // Validasi input
    $validator = Validator::make($request->all(), $rules, $pesan);
    if ($validator->fails()) {
        return back()->withInput()->withErrors($validator->errors());
    } else {
        DB::beginTransaction();
        try {
            // Temukan user berdasarkan ID
            $data = User::findOrFail($id);
            $data->nip = $request->nip;
            $data->name = $request->nama;
            $data->username = $request->username;
            $data->email = $request->email;
            $data->jabatan_id = $request->jabatan_id;
            $data->lokasi_id = $request->lokasi_id;
            // Hanya update password jika diisi
            if ($request->password) {
                $data->password = Hash::make($request->password);
            }
            $data->level = $request->level;
            $data->save();

        } catch (\QueryException $e) {
            DB::rollback();
            return response()->json([
                'fail' => true,
                'errors' => $e,
                'pesan' => 'Error Memperbarui Data Pengguna',
            ]);
        }

        DB::commit();

        return redirect()->route('user.index');
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

    public function getUsersByLokasi($lokasi_id)
    {
        // Ambil user yang sesuai dengan lokasi_id yang dipilih
        $users = User::where('lokasi_id', $lokasi_id)->with('jabatan')->get();

        // Kembalikan response JSON untuk diakses oleh AJAX
        return response()->json($users);
    }

}
