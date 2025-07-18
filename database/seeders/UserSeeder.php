<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'name' => 'admin',
            'email' =>  'admin@admin.com',
            'username' =>  'admin',
            'password' => Hash::make('admin1234'),
            'role' =>  'admin',
            'no_telp' => '081233416780',
        ]);
    }
}
