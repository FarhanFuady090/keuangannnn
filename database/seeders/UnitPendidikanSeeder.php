<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class UnitPendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('unitpendidikan')->insert([
            'kategori' =>  '-',
            'namaUnit' => '-',
            'status' => 'Aktif',
        ]);

        DB::table('unitpendidikan')->insert([
          'kategori' =>  'formal',
          'namaUnit' => 'TK',
          'status' => 'Aktif',
      ]);

      DB::table('unitpendidikan')->insert([
        'kategori' =>  'formal',
        'namaUnit' => 'SD',
        'status' => 'Aktif',
    ]);

    DB::table('unitpendidikan')->insert([
      'kategori' =>  'formal',
      'namaUnit' => 'SMP',
      'status' => 'Aktif',
  ]);

  DB::table('unitpendidikan')->insert([
    'kategori' =>  'formal',
    'namaUnit' => 'SMA',
    'status' => 'Aktif',
]);

DB::table('unitpendidikan')->insert([
  'kategori' =>  'Informal',
  'namaUnit' => 'MADIN',
  'status' => 'Aktif',
]);

DB::table('unitpendidikan')->insert([
  'kategori' =>  'Informal',
  'namaUnit' => 'TPQ',
  'status' => 'Aktif',
]);

DB::table('unitpendidikan')->insert([
  'kategori' =>  'Pondok',
  'namaUnit' => 'YA PONDOK',
  'status' => 'Aktif',
]);

DB::table('unitpendidikan')->insert([
  'kategori' =>  'Pondok',
  'namaUnit' => 'TIDAK PONDOK',
  'status' => 'Aktif',
]);
    }
}