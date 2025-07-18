<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Http\Request;

class SiswaYayasanExport implements FromView
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = Siswa::with('kelas', 'unitpendidikan');

        if ($this->request->filled('search')) {
            $search = $this->request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($this->request->filled('status') && $this->request->status !== 'Pilih Status') {
            $query->where('status', $this->request->status);
        }

        if ($this->request->filled('kelas_id')) {
            $query->where('kelas_id', $this->request->kelas_id);
        }

        if ($this->request->filled('unitpendidikan_id')) {
            $query->where('unitpendidikan_id', $this->request->unitpendidikan_id);
        }

        if ($this->request->filled('unitpendidikan_idInformal')) {
            $query->where('unitpendidikan_idInformal', $this->request->unitpendidikan_idInformal);
        }

        if ($this->request->filled('unitpendidikan_idPondok')) {
            $query->where('unitpendidikan_idPondok', $this->request->unitpendidikan_idPondok);
        }

        $siswas = $query->get();
        $unitpendidikan = \App\Models\UnitPendidikan::all();

        return view('yayasan.laporan.siswa.export_excel', compact('siswas', 'unitpendidikan'));
    }
}
