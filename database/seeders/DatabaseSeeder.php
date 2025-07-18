<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Admin;
use App\Models\UnitPendidikan;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            AdminSeeder::class,
            UnitPendidikanSeeder::class,
            KelasSeeder::class,
            SiswaSeeder::class,
        ]);
    }
}