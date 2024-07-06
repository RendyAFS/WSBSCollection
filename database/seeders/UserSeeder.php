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
                'email' => 's@s',
                'password' => bcrypt('qawsedrf'),
            ],
            [
                'level' => 'Admin',
                'status' => 'Belum Aktif',
                'name' => 'Admin',
                'email' => 'a@a',
                'password' => bcrypt('qawsedrf'),
            ],
            [
                'level' => 'User',
                'status' => 'Belum Aktif',
                'name' => 'User',
                'email' => 'u@u',
                'password' => bcrypt('qawsedrf'),
            ],
            [
                'level' => 'Admin',
                'status' => 'Belum Aktif',
                'name' => 'Admin2',
                'email' => 'a@a2',
                'password' => bcrypt('qawsedrf'),
            ],
            [
                'level' => 'User',
                'status' => 'Belum Aktif',
                'name' => 'User2',
                'email' => 'u@u2',
                'password' => bcrypt('qawsedrf'),
            ],
            [
                'level' => 'Admin',
                'status' => 'Belum Aktif',
                'name' => 'Admin3',
                'email' => 'a@a3',
                'password' => bcrypt('qawsedrf'),
            ],
            [
                'level' => 'User',
                'status' => 'Belum Aktif',
                'name' => 'User3',
                'email' => 'u@u3',
                'password' => bcrypt('qawsedrf'),
            ],
            [
                'level' => 'Admin',
                'status' => 'Belum Aktif',
                'name' => 'Admin4',
                'email' => 'a@a4',
                'password' => bcrypt('qawsedrf'),
            ],
            [
                'level' => 'User',
                'status' => 'Belum Aktif',
                'name' => 'User4',
                'email' => 'u@u4',
                'password' => bcrypt('qawsedrf'),
            ],
        ]);
    }
}
