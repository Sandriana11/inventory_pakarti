<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LokasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $lokasiData = [
            ['nama' => 'SE', 'kode' => '25'],
            ['nama' => 'TIMAS FPU', 'kode' => '123'],
            ['nama' => 'DM MOPU', 'kode' => '120'],
            ['nama' => 'HANOCHEM', 'kode' => '126'],
            ['nama' => 'RPE', 'kode' => '75'],
            ['nama' => 'MKN', 'kode' => '127'],
            ['nama' => 'BTE', 'kode' => '14'],
            ['nama' => 'PSB', 'kode' => '30'],
            ['nama' => 'OPS', 'kode' => '40'],
            ['nama' => 'BD', 'kode' => '50'],
            ['nama' => 'FIN', 'kode' => '60'],
            ['nama' => 'GA', 'kode' => '70'],
            ['nama' => 'IT', 'kode' => '34'],
        ];

        foreach ($lokasiData as $lokasi) {
            DB::table('lokasis')->insert([
                'nama' => $lokasi['nama'],
                'kode' => $lokasi['kode'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
