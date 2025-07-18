<?php

namespace App\Exports;

use App\Models\Tabungan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TabunganExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Tabungan::with('siswa')->get()->map(function ($tabungan) {
            return [
                'Nama Siswa' => $tabungan->siswa->nama,
                'Saldo Awal' => $tabungan->saldo_awal,
                'Saldo Akhir' => $tabungan->saldo_akhir,
                'Status' => $tabungan->status,
            ];
        });
    }

    public function headings(): array
    {
        return ['Nama Siswa', 'Saldo Awal', 'Saldo Akhir', 'Status'];
    }
}
