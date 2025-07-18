<?php
namespace App\Exports;

use App\Models\TransaksiKas;
use App\Models\TahunAjaran;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\View as ViewFacade;
use Maatwebsite\Excel\Concerns\FromView;

class KasExport implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = TransaksiKas::with(['kas', 'unitpendidikan']);

        if ($this->request->filled('kas')) {
            $query->where('kas_id', $this->request->kas);
        }

        if ($this->request->filled('unit_pendidikan')) {
            $query->where('unitpendidikan_id', $this->request->unit_pendidikan);
        }

        if ($this->request->filled('created_by')) {
            $query->where('created_by', $this->request->created_by);
        }

        if ($this->request->filled('tanggal_awal') && $this->request->filled('tanggal_akhir')) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->request->tanggal_awal)->startOfDay(),
                Carbon::parse($this->request->tanggal_akhir)->endOfDay()
            ]);
        } elseif ($this->request->filled('tahun_ajaran_id')) {
            $tahunAjaran = TahunAjaran::find($this->request->tahun_ajaran_id);
            if ($tahunAjaran) {
                $query->whereBetween('created_at', [
                    Carbon::parse($tahunAjaran->awal)->startOfDay(),
                    Carbon::parse($tahunAjaran->akhir)->endOfDay()
                ]);
            }
        }

        $transaksiKas = $query->get();

        return ViewFacade::make('yayasan.laporan.kas.export_excel', [
            'transaksiKas' => $transaksiKas
        ]);
    }
}
