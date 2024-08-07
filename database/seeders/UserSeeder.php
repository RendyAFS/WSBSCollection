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
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'level' => 'Super Admin',
                'status' => 'Aktif',
                'name' => 'Super Admin',
                'nik' => '123456',
                'no_hp' => '089966851111',
                'email' => 's@s',
                'password' => bcrypt('qawsedrf'),
            ],
            [
                'level' => 'Admin Billper',
                'status' => 'Aktif',
                'name' => 'Admin Billper',
                'nik' => '123456',
                'no_hp' => '089966851111',
                'email' => 'ab@ab',
                'password' => bcrypt('qawsedrf'),
            ],
            [
                'level' => 'Admin Pranpc',
                'status' => 'Aktif',
                'name' => 'Admin Pranpc',
                'nik' => '123456',
                'no_hp' => '089966851111',
                'email' => 'ap@ap',
                'password' => bcrypt('qawsedrf'),
            ],
        ]);
        // Tambah user kedua dan seterusnya
        for ($i = 1; $i <= 10; $i++) {
            DB::table('users')->insert([
                'level' => 'Sales',
                'status' => 'Aktif',
                'name' => 'Sales' . $i,
                'nik' => '123456',
                'no_hp' => '089966851111',
                'email' => 'u' . $i . '@u' . $i,
                'password' => Hash::make('qawsedrf'),
            ]);
        }
    }
}
