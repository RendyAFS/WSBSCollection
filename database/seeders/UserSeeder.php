<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'level' => 'Super Admin',
                'status' => 'Aktif',
                'name' => 'Super Admin',
                'nik' => '123456',
                'email' => 's@s',
                'password' => bcrypt('qawsedrf'),
            ],
            [
                'level' => 'Admin Billper',
                'status' => 'Aktif',
                'name' => 'Admin Billper',
                'nik' => '123456',
                'email' => 'ab@ab',
                'password' => bcrypt('qawsedrf'),
            ],
            [
                'level' => 'Admin PraNPC',
                'status' => 'Aktif',
                'name' => 'Admin Pra NPC',
                'nik' => '123456',
                'email' => 'ap@ap',
                'password' => bcrypt('qawsedrf'),
            ],
            [
                'level' => 'User',
                'status' => 'Belum Aktif',
                'name' => 'User',
                'nik' => '123456',
                'email' => 'u@u',
                'password' => bcrypt('qawsedrf'),
            ],
        ]);
    }
}
