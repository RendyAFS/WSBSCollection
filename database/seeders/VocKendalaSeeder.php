<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VocKendalaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('voc_kendalas')->insert([
            [
                'voc_kendala' => 'Produk',
            ],
            [
                'voc_kendala' => 'Price',
            ],
            [
                'voc_kendala' => 'Customer',
            ],
            [
                'voc_kendala' => 'Bukan Keluarga',
            ],
            [
                'voc_kendala' => 'Rumah Tidak Berpenghuni',
            ],
            [
                'voc_kendala' => 'Alamat Tidak Ditemukan',
            ],
            [
                'voc_kendala' => 'Pindah Alamat',
            ],

        ]);
    }
}
