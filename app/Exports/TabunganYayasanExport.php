<?php

namespace App\Exports;

use App\Models\Tabungan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class TabunganYayasanExport implements FromCollection, WithHeadings
{
    protected $filteredData;

    public function __construct(Collection $filteredData)
    {
        $this->filteredData = $filteredData;
    }

    public function collection()
    {
        return $this->filteredData->map(function ($item) {
            return [
                'Nama Siswa'      => $item->siswa->nama,
                'Unit'            => $item->siswa->kelas->unitpendidikan->namaUnit ?? '-',
                'Kelas'           => $item->siswa->kelas->nama_kelas ?? '-',
                'Setoran Awal'    => $item->saldo_awal,
                'Total Setoran'   => $item->total_setoran ?? 0,
                'Total Penarikan' => $item->total_penarikan ?? 0,
                'Saldo Akhir'     => $item->saldo_akhir,
                'Created At'      => Carbon::parse($item->created_at)->format('d-m-Y H:i'),
            ];
        });
    }

        public function headings(): array
    {
        return [
            'Nama Siswa',
            'Unit',
            'Kelas',
            'Setoran Awal',
            'Total Setoran',
            'Total Penarikan',
            'Saldo Akhir',
            'Created At',
        ];
    }
}
