<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        $siswaData = [
            [
                'nis' => '2023001',
                'nisn' => '00523001',
                'nama' => 'Ahmad Fauzan',
                'jenis_kelamin' => 'Laki-laki',
                'kelas_id' => 2,
                'agama' => 'Islam',
                'namaOrtu' => 'H. Mulyadi',
                'alamatOrtu' => 'Jl. Anggrek No. 12',
                'noTelp' => '081234567890',
                'email' => 'ahmadfauzan@example.com',
                'unitpendidikan_id' => 2,
                'unitpendidikan_idInformal' => 6,
                'unitpendidikan_idPondok' => 8,
                'status' => 'Aktif',
            ],
            [
                'nis' => '2023002',
                'nisn' => '00523002',
                'nama' => 'Siti Rohmah',
                'jenis_kelamin' => 'Perempuan',
                'kelas_id' => 6,
                'agama' => 'Islam',
                'namaOrtu' => 'Bu Sari',
                'alamatOrtu' => 'Jl. Kenanga No. 3',
                'noTelp' => '081298765432',
                'email' => 'sitirohmah@example.com',
                'unitpendidikan_id' => 3,
                'unitpendidikan_idInformal' => 7,
                'unitpendidikan_idPondok' => 9,
                'status' => 'Aktif',
            ],
            [
                'nis' => '2023003',
                'nisn' => '00523003',
                'nama' => 'Rizki Ramadhan',
                'jenis_kelamin' => 'Laki-laki',
                'kelas_id' => 23,
                'agama' => 'Islam',
                'namaOrtu' => 'Budi Santoso',
                'alamatOrtu' => 'Jl. Melati No. 5',
                'noTelp' => '081355512345',
                'email' => 'rizki@example.com',
                'unitpendidikan_id' => 4,
                'unitpendidikan_idInformal' => 6,
                'unitpendidikan_idPondok' => 8,
                'status' => 'Aktif',
            ],
            // Tambahkan 7 data lainnya di bawah ini
        ];

        DB::table('siswas')->insert($siswaData);

    }
}