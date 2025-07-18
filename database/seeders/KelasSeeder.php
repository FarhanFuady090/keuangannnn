<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('kelas')->insert([
            'unitpendidikan_id' => '1',
            'nama_kelas' => '-',
            'status' => 'AKTIF',
            'grade' => '-',
            'keterangan' => 'Data Siswa Tidak Ada Kelas',
        ]);

        DB::table('kelas')->insert([
          'unitpendidikan_id' => '2',
          'nama_kelas' => 'TK A',
          'status' => 'AKTIF',
          'grade' => 'A',
          'keterangan' => 'Data Siswa TK A',
      ]);

      DB::table('kelas')->insert([
        'unitpendidikan_id' => '2',
        'nama_kelas' => 'TK B',
        'status' => 'AKTIF',
        'grade' => 'B',
        'keterangan' => 'Data Siswa TK B',
    ]);

    DB::table('kelas')->insert([
      'unitpendidikan_id' => '3',
      'nama_kelas' => 'SD 1A',
      'status' => 'AKTIF',
      'grade' => 'A',
      'keterangan' => 'Data Siswa SD 1A',
  ]);

  DB::table('kelas')->insert([
    'unitpendidikan_id' => '3',
    'nama_kelas' => 'SD 1B',
    'status' => 'AKTIF',
    'grade' => 'B',
    'keterangan' => 'Data Siswa SD 1B',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 1C',
  'status' => 'AKTIF',
  'grade' => 'C',
  'keterangan' => 'Data Siswa SD 1C',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 2A',
  'status' => 'AKTIF',
  'grade' => 'A',
  'keterangan' => 'Data Siswa SD 2A',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 2B',
  'status' => 'AKTIF',
  'grade' => 'B',
  'keterangan' => 'Data Siswa SD 2B',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 2C',
  'status' => 'AKTIF',
  'grade' => 'C',
  'keterangan' => 'Data Siswa SD 2C',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 3A',
  'status' => 'AKTIF',
  'grade' => 'A',
  'keterangan' => 'Data Siswa SD 3A',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 3B',
  'status' => 'AKTIF',
  'grade' => 'B',
  'keterangan' => 'Data Siswa SD 3B',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 3C',
  'status' => 'AKTIF',
  'grade' => 'C',
  'keterangan' => 'Data Siswa SD 3C',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 4A',
  'status' => 'AKTIF',
  'grade' => 'A',
  'keterangan' => 'Data Siswa SD 4A',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 4B',
  'status' => 'AKTIF',
  'grade' => 'B',
  'keterangan' => 'Data Siswa SD 4B',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 4C',
  'status' => 'AKTIF',
  'grade' => 'C',
  'keterangan' => 'Data Siswa SD 4C',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 5A',
  'status' => 'AKTIF',
  'grade' => 'A',
  'keterangan' => 'Data Siswa SD 5A',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 5B',
  'status' => 'AKTIF',
  'grade' => 'B',
  'keterangan' => 'Data Siswa SD 5B',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 5C',
  'status' => 'AKTIF',
  'grade' => 'C',
  'keterangan' => 'Data Siswa SD 5C',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 6A',
  'status' => 'AKTIF',
  'grade' => 'A',
  'keterangan' => 'Data Siswa SD 6A',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 6B',
  'status' => 'AKTIF',
  'grade' => 'B',
  'keterangan' => 'Data Siswa SD 6B',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '3',
  'nama_kelas' => 'SD 6C',
  'status' => 'AKTIF',
  'grade' => 'C',
  'keterangan' => 'Data Siswa SD 6C',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '4',
  'nama_kelas' => 'SMP 7A',
  'status' => 'AKTIF',
  'grade' => 'A',
  'keterangan' => 'Data Siswa SMP 7A',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '4',
  'nama_kelas' => 'SMP 7B',
  'status' => 'AKTIF',
  'grade' => 'B',
  'keterangan' => 'Data Siswa SMP 7B',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '4',
  'nama_kelas' => 'SMP 7C',
  'status' => 'AKTIF',
  'grade' => 'C',
  'keterangan' => 'Data Siswa SMP 7C',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '4',
  'nama_kelas' => 'SMP 8A',
  'status' => 'AKTIF',
  'grade' => 'A',
  'keterangan' => 'Data Siswa SMP 8A',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '4',
  'nama_kelas' => 'SMP 8B',
  'status' => 'AKTIF',
  'grade' => 'B',
  'keterangan' => 'Data Siswa SMP 8B',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '4',
  'nama_kelas' => 'SMP 8C',
  'status' => 'AKTIF',
  'grade' => 'C',
  'keterangan' => 'Data Siswa SMP 8C',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '4',
  'nama_kelas' => 'SMP 9A',
  'status' => 'AKTIF',
  'grade' => 'A',
  'keterangan' => 'Data Siswa SMP 9A',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '4',
  'nama_kelas' => 'SMP 9B',
  'status' => 'AKTIF',
  'grade' => 'B',
  'keterangan' => 'Data Siswa SMP 9B',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '4',
  'nama_kelas' => 'SMP 9C',
  'status' => 'AKTIF',
  'grade' => 'C',
  'keterangan' => 'Data Siswa SMP 9C',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '5',
  'nama_kelas' => 'SMA 10A',
  'status' => 'AKTIF',
  'grade' => 'A',
  'keterangan' => 'Data Siswa SMA 10A',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '5',
  'nama_kelas' => 'SMA 10B',
  'status' => 'AKTIF',
  'grade' => 'B',
  'keterangan' => 'Data Siswa SMA 10B',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '5',
  'nama_kelas' => 'SMA 10C',
  'status' => 'AKTIF',
  'grade' => 'C',
  'keterangan' => 'Data Siswa SMA 10C',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '5',
  'nama_kelas' => 'SMA 11A',
  'status' => 'AKTIF',
  'grade' => 'A',
  'keterangan' => 'Data Siswa SMA 11A',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '5',
  'nama_kelas' => 'SMA 11B',
  'status' => 'AKTIF',
  'grade' => 'B',
  'keterangan' => 'Data Siswa SMA 11B',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '5',
  'nama_kelas' => 'SMA 11C',
  'status' => 'AKTIF',
  'grade' => 'C',
  'keterangan' => 'Data Siswa SMA 11C',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '5',
  'nama_kelas' => 'SMA 12A',
  'status' => 'AKTIF',
  'grade' => 'A',
  'keterangan' => 'Data Siswa SMA 12A',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '5',
  'nama_kelas' => 'SMA 12B',
  'status' => 'AKTIF',
  'grade' => 'B',
  'keterangan' => 'Data Siswa SMA 12B',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '5',
  'nama_kelas' => 'SMA 12C',
  'status' => 'AKTIF',
  'grade' => 'C',
  'keterangan' => 'Data Siswa SMA 12C',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '6',
  'nama_kelas' => 'MADIN',
  'status' => 'AKTIF',
  'grade' => '-',
  'keterangan' => 'Data Siswa MADIN',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '7',
  'nama_kelas' => 'TPQ',
  'status' => 'AKTIF',
  'grade' => '-',
  'keterangan' => 'Data Siswa TPQ',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '8',
  'nama_kelas' => 'YA PONDOK',
  'status' => 'AKTIF',
  'grade' => '-',
  'keterangan' => 'Data Siswa PONDOK',
]);

DB::table('kelas')->insert([
  'unitpendidikan_id' => '9',
  'nama_kelas' => 'TIDAK PONDOK',
  'status' => 'AKTIF',
  'grade' => '-',
  'keterangan' => 'Data Siswa TIDAK PONDOK',
]);

    }
}
