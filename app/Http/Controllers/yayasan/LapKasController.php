<?php

namespace App\Http\Controllers\yayasan;

use App\Http\Controllers\Controller;
use App\Models\Kas;
use App\Models\TransaksiKas;
use App\Models\UnitPendidikan;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\KasExport;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use Carbon\Carbon;

class LapKasController extends Controller
{
public function index(Request $request)
{
    $query = TransaksiKas::with(['kas', 'unitpendidikan']);

    // Filter berdasarkan dropdown
    if ($request->filled('kas')) {
        $query->where('kas_id', $request->kas);
    }

    if ($request->filled('unit_pendidikan')) {
        $query->where('unitpendidikan_id', $request->unit_pendidikan);
    }

    if ($request->filled('created_by')) {
        $query->where('created_by', $request->created_by);
    }

    // Filter berdasarkan Tahun Ajaran saja
    $tahunAjaranId = $request->input('tahun_ajaran_id');
    $tanggalAwal = $request->input('tanggal_awal');
    $tanggalAkhir = $request->input('tanggal_akhir');

    $start = null;
    $end = null;

    if ($tahunAjaranId) {
        $tahunAjaran = \App\Models\TahunAjaran::find($tahunAjaranId);
        if ($tahunAjaran) {
            $start = \Carbon\Carbon::parse($tahunAjaran->awal)->startOfDay();
            $end = \Carbon\Carbon::parse($tahunAjaran->akhir)->endOfDay();
        }
    } elseif ($tanggalAwal && $tanggalAkhir) {
        $start = \Carbon\Carbon::parse($tanggalAwal)->startOfDay();
        $end = \Carbon\Carbon::parse($tanggalAkhir)->endOfDay();
    }

    if ($start && $end) {
        $query->whereBetween('created_at', [$start, $end]);
    }

    $transaksiKas = $query->get();

    $filterKas = TransaksiKas::select('kas_id')->distinct()->with('kas:id,namaKas')->get()->pluck('kas')->filter();
    $unitPendidikanFilter = TransaksiKas::select('unitpendidikan_id')->distinct()->with('unitpendidikan:id,namaunit')->get()->pluck('unitpendidikan')->filter();
    $createdByUsers = TransaksiKas::select('created_by')->distinct()->pluck('created_by');
    $kas = Kas::where('status', 'Aktif')->get();
    $tahunAjarans = \App\Models\TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

    return view('yayasan.laporan.kas.index', compact(
        'transaksiKas',
        'filterKas',
        'unitPendidikanFilter',
        'createdByUsers',
        'kas',
        'tahunAjarans',
        'tahunAjaranId'
    ));
}


    public function trashed()
    {
        $trashedTransaksiKas = TransaksiKas::onlyTrashed()->with(['kas', 'unitpendidikan'])->get();
        return view('yayasan.laporan.kas.trashed', compact('trashedTransaksiKas'));
    }

    public function exportExcel(Request $request)
{
    return Excel::download(new KasExport($request), 'Laporan-Kas-Yayasan.xlsx');
}

public function exportPDF(Request $request)
{
    $query = TransaksiKas::with(['kas', 'unitpendidikan']);

    if ($request->filled('kas')) {
        $query->where('kas_id', $request->kas);
    }

    if ($request->filled('unit_pendidikan')) {
        $query->where('unitpendidikan_id', $request->unit_pendidikan);
    }

    if ($request->filled('created_by')) {
        $query->where('created_by', $request->created_by);
    }

    if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
        $query->whereBetween('created_at', [
            Carbon::parse($request->tanggal_awal)->startOfDay(),
            Carbon::parse($request->tanggal_akhir)->endOfDay()
        ]);
    } elseif ($request->filled('tahun_ajaran_id')) {
        $tahunAjaran = TahunAjaran::find($request->tahun_ajaran_id);
        if ($tahunAjaran) {
            $query->whereBetween('created_at', [
                Carbon::parse($tahunAjaran->awal)->startOfDay(),
                Carbon::parse($tahunAjaran->akhir)->endOfDay()
            ]);
        }
    }

    $transaksiKas = $query->get();

    $pdf = Pdf::loadView('yayasan.laporan.kas.kas_pdf', [
        'transaksiKas' => $transaksiKas
    ])->setPaper('A4', 'landscape');

    return $pdf->download('Laporan-Kas-Yayasan.pdf');
}


}
