<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\UnitPendidikan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\Importable;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;

class SiswaImport implements 
    ToModel,
    WithHeadingRow,
    WithValidation,
    SkipsOnFailure,
    WithBatchInserts,
    WithEvents
{
    use Importable, SkipsFailures;

    // Track NIS yang sudah muncul dalam satu file
    private $nisCollection = [];

    public function model(array $row)
    {
        // Lookup ID relasional berdasarkan nama
        $kelas = Kelas::where('nama_kelas', $row['kelas'])->first();
        $formal = UnitPendidikan::where('namaUnit', $row['unit_pendidikan_formal'])->first();
        $informal = UnitPendidikan::where('namaUnit', $row['unit_pendidikan_informal'])->first();
        $pondok = UnitPendidikan::where('namaUnit', $row['status_pondok'])->first();

        return new Siswa([
            'nis' => $row['nis'],
            'nisn' => $row['nisn'] ?? null,
            'nama' => $row['nama'],
            'jenis_kelamin' => $row['jenis_kelamin'],
            'kelas_id' => $kelas?->id,
            'agama' => $row['agama'],
            'namaOrtu' => $row['nama_ortu'],
            'alamatOrtu' => $row['alamat_ortu'],
            'noTelp' => $row['no_telp'] ?? null,
            'email' => $row['email'] ?? null,
            'unitpendidikan_id' => $formal?->id,
            'unitpendidikan_idInformal' => $informal?->id,
            'unitpendidikan_idPondok' => $pondok?->id,
            'status' => $row['status'] ?? 'Aktif',
        ]);
    }

    public function rules(): array
    {
        return [
            'nis' => [
                'required',
                'numeric',
                'digits_between:7,20',
                function ($attribute, $value, $fail) {
                    // Cek di database
                    if (Siswa::where('nis', $value)->exists()) {
                        $fail("NIS '$value' sudah terdaftar di database.");
                        return;
                    }

                    // Cek duplikat dalam file
                    if (in_array($value, $this->nisCollection)) {
                        $fail("NIS '$value' duplikat dalam file Excel.");
                        return;
                    }

                    // Jika valid, simpan ke dalam list
                    $this->nisCollection[] = $value;
                },
            ],
            'nisn' => 'nullable|numeric',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'kelas' => 'required|string',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Budha,Konghucu',
            'nama_ortu' => 'required|string|max:255',
            'alamat_ortu' => 'required|string|max:255',
            'no_telp' => 'nullable|string|max:20',
            'email' => 'nullable|email',
            'unit_pendidikan_formal' => 'required|string',
            'unit_pendidikan_informal' => 'required|string',
            'status_pondok' => 'required|string',
            'status' => 'nullable|in:Aktif,Non Aktif,Pindah,Lulus,Drop Out',
        ];
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function registerEvents(): array
    {
        return [
            BeforeImport::class => function (BeforeImport $event) {
                // Reset koleksi NIS saat mulai import baru
                $event->getConcernable()->nisCollection = [];
            },
        ];
    }
    
}