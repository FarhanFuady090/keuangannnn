<?php

namespace App\Exports;

use App\Models\Tabungan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AllTabunganExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $index = 1;
    public function collection()
    {
        return Tabungan::with('siswa.kelas.unitpendidikan')->get();
    }

    public function map($tabungan): array
    {
        return [
            $this->index++, // ← Nomor urut
            $tabungan->siswa->nama,
            $tabungan->siswa->nis,
            $tabungan->siswa->kelas->nama_kelas ?? '-',
            $tabungan->siswa->kelas->unitpendidikan->namaUnit ?? '-',
            $tabungan->saldo_awal,
            $tabungan->saldo_akhir,
            $tabungan->status,
        ];
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Siswa',
            'NIS',
            'Kelas',
            'Unit Pendidikan',
            'Saldo Awal',
            'Saldo Akhir',
            'Status',
        ];
    }
}
