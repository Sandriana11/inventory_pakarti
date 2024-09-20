<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Ambil ID bidang dari tabel 'bidang' untuk digunakan sebagai foreign key
        $lokasiId = DB::table('lokasis')->where('kode', '34')->first()->id;
        $jabatanId = DB::table('jabatans')->where('kode', '001')->first()->id;

        // Tambahkan akun admin
        DB::table('users')->insert([
            'nip' => '1234567890', 
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), 
            'level' => 'admin',
            'hp' => '081615649784', 
            'jabatan_id' => $jabatanId, 
            'lokasi_id' => $lokasiId, 
            'alamat' => 'Jl. Mangga', 
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
