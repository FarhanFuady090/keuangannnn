<?php

namespace App\Http\Controllers\yayasan;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Siswa;
use App\Models\Tabungan;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\TabunganExport;
use App\Exports\AllTabunganExport;
use App\Exports\TabunganYayasanExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use Carbon\Carbon;

class LapTabunganController extends Controller
{
    // Menampilkan semua tabungan (bisa difilter berdasarkan unit)
public function index(Request $request)
{
    $query = Tabungan::with(['siswa.kelas.unitpendidikan']);

    if ($request->has('trashed') && $request->trashed == true) {
        $query->onlyTrashed();
    }

    if ($request->filled('search')) {
        $query->whereHas('siswa', function ($q) use ($request) {
            $q->where('nama', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('status')) {
        $query->whereHas('siswa', function ($q) use ($request) {
            $q->where('status', $request->status);
        });
    }

    if ($request->filled('unit')) {
        $query->whereHas('siswa.kelas.unitpendidikan', function ($q) use ($request) {
            $q->where('id', $request->unit);
        });
    }

    if ($request->filled('kelas')) {
        $query->whereHas('siswa.kelas', function ($q) use ($request) {
            $q->where('id', $request->kelas);
        });
    }

    // Ambil semua Tahun Ajaran
    $tahunAjarans = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

    // Filter berdasarkan Tahun Ajaran ID & Semester
    $tahunAjaranId = $request->input('tahun_ajaran_id');
    $semester = $request->input('semester');

    if ($tahunAjaranId) {
        $tahunAjaran = TahunAjaran::find($tahunAjaranId);
        if ($tahunAjaran) {
            // Menggunakan nama kolom yang sesuai dengan migrasi
            $tanggalMulai = Carbon::parse($tahunAjaran->awal)->startOfDay();
            $tanggalSelesai = Carbon::parse($tahunAjaran->akhir)->endOfDay();
            $query->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai]);
        }
    } elseif ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
        $query->whereBetween('created_at', [
            $request->tanggal_awal . ' 00:00:00',
            $request->tanggal_akhir . ' 23:59:59'
        ]);
    }


    $tabungans = $query->paginate(20);

    foreach ($tabungans as $tabungan) {
        $setoranAwal = $tabungan->saldo_awal;

        $totalSetoranTransaksi = $tabungan->transaksi()
            ->where('jenis_transaksi', 'Setoran')
            ->sum('jumlah');

        $totalPenarikan = $tabungan->transaksi()
            ->where('jenis_transaksi', 'Penarikan')
            ->sum('jumlah');

        $tabungan->total_setoran = $setoranAwal + $totalSetoranTransaksi;
        $tabungan->total_penarikan = $totalPenarikan;
    }

    $units = \App\Models\UnitPendidikan::all();
    $kelasList = \App\Models\Kelas::all();

    return view('yayasan.laporan.tabungan.index', compact(
        'tabungans', 'units', 'kelasList', 'tahunAjarans', 'tahunAjaranId', 'semester'
    ));
}

    // Detail tabungan per siswa
    public function show($id, Request $request)
    {
        $tabungan = Tabungan::with('siswa')->findOrFail($id);

        $transaksiQuery = $tabungan->transaksi()->orderBy('created_at', 'asc');

        if ($request->filled('start') && $request->filled('end')) {
            $transaksiQuery->whereBetween('created_at', [$request->start, $request->end]);
        }

        $transaksi = $transaksiQuery->get();

        $transaksiPerBulan = $tabungan->transaksi()
            ->selectRaw("
        DATE_FORMAT(created_at, '%Y-%m') as bulan,
        SUM(CASE WHEN jenis_transaksi = 'Setoran' THEN jumlah ELSE 0 END) as total_setoran,
        SUM(CASE WHEN jenis_transaksi = 'Penarikan' THEN jumlah ELSE 0 END) as total_penarikan
    ")
            ->groupBy('bulan')
            ->orderBy('bulan')
            ->get()
            ->keyBy('bulan');

        // Ambil bulan saat tabungan dibuat
        $createdMonth = $tabungan->created_at->format('Y-m');

        // Jika bulan saldo_awal sama dengan salah satu bulan transaksi, tambahkan ke total_setoran
        if ($transaksiPerBulan->has($createdMonth)) {
            $transaksiPerBulan[$createdMonth]->total_setoran += $tabungan->saldo_awal;
        } else {
            // Jika tidak ada transaksi di bulan tersebut, buat entri baru
            $transaksiPerBulan->put($createdMonth, (object)[
                'bulan' => $createdMonth,
                'total_setoran' => $tabungan->saldo_awal,
                'total_penarikan' => 0,
            ]);
        }

        // Urutkan ulang berdasarkan bulan dan reset key
        $chartData = $transaksiPerBulan->sortKeys()->values();


        return view('yayasan.laporan.tabungan.show', compact('tabungan', 'transaksi', 'chartData'));
    }

    public function exportPdf($id)
    {
        $tabungan = Tabungan::with(['siswa', 'transaksi'])->findOrFail($id);
        $pdf = Pdf::loadView('yayasan.laporan.tabungan.export_pdf', compact('tabungan'));
        return $pdf->download('Laporan_Tabungan_' . $tabungan->siswa->nama . '.pdf');
    }

    public function exportAll()
    {
        return Excel::download(new AllTabunganExport, 'rekap_semua_tabungan.xlsx');
    }

    public function exportFiltered(Request $request)
{
    $query = Tabungan::with(['siswa.kelas.unitpendidikan']);

    if ($request->filled('search')) {
        $query->whereHas('siswa', function ($q) use ($request) {
            $q->where('nama', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('status')) {
        $query->whereHas('siswa', function ($q) use ($request) {
            $q->where('status', $request->status);
        });
    }

    if ($request->filled('unit')) {
        $query->whereHas('siswa.kelas.unitpendidikan', function ($q) use ($request) {
            $q->where('id', $request->unit);
        });
    }

    if ($request->filled('kelas')) {
        $query->whereHas('siswa.kelas', function ($q) use ($request) {
            $q->where('id', $request->kelas);
        });
    }

    if ($request->filled('tahun_ajaran_id')) {
        $tahunAjaran = \App\Models\TahunAjaran::find($request->tahun_ajaran_id);
        if ($tahunAjaran) {
            $tanggalMulai = \Carbon\Carbon::parse($tahunAjaran->awal)->startOfDay();
            $tanggalSelesai = \Carbon\Carbon::parse($tahunAjaran->akhir)->endOfDay();
            $query->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai]);
        }
    } elseif ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
        $query->whereBetween('created_at', [
            $request->tanggal_awal . ' 00:00:00',
            $request->tanggal_akhir . ' 23:59:59'
        ]);
    }

    $tabungans = $query->get();

    // Hitung total setoran & penarikan
    foreach ($tabungans as $tabungan) {
        $setoranAwal = $tabungan->saldo_awal;
        $totalSetoranTransaksi = $tabungan->transaksi()->where('jenis_transaksi', 'Setoran')->sum('jumlah');
        $totalPenarikan = $tabungan->transaksi()->where('jenis_transaksi', 'Penarikan')->sum('jumlah');
        $tabungan->total_setoran = $setoranAwal + $totalSetoranTransaksi;
        $tabungan->total_penarikan = $totalPenarikan;
    }

    return Excel::download(new TabunganYayasanExport($tabungans), 'Rekap_Tabungan_Yayasan.xlsx');
}

public function exportFilteredPdf(Request $request)
{
    $query = Tabungan::with(['siswa.kelas.unitpendidikan']);

    // Terapkan semua filter sama seperti di fungsi index()
    if ($request->filled('search')) {
        $query->whereHas('siswa', function ($q) use ($request) {
            $q->where('nama', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('status')) {
        $query->whereHas('siswa', function ($q) use ($request) {
            $q->where('status', $request->status);
        });
    }

    if ($request->filled('unit')) {
        $query->whereHas('siswa.kelas.unitpendidikan', function ($q) use ($request) {
            $q->where('id', $request->unit);
        });
    }

    if ($request->filled('kelas')) {
        $query->whereHas('siswa.kelas', function ($q) use ($request) {
            $q->where('id', $request->kelas);
        });
    }

    if ($request->filled('tahun_ajaran_id')) {
        $tahunAjaran = TahunAjaran::find($request->tahun_ajaran_id);
        if ($tahunAjaran) {
            $tanggalMulai = \Carbon\Carbon::parse($tahunAjaran->awal)->startOfDay();
            $tanggalSelesai = \Carbon\Carbon::parse($tahunAjaran->akhir)->endOfDay();
            $query->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai]);
        }
    } elseif ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
        $query->whereBetween('created_at', [
            $request->tanggal_awal . ' 00:00:00',
            $request->tanggal_akhir . ' 23:59:59'
        ]);
    }

    $tabungans = $query->get();

    foreach ($tabungans as $tabungan) {
        $setoranAwal = $tabungan->saldo_awal;
        $totalSetoranTransaksi = $tabungan->transaksi()->where('jenis_transaksi', 'Setoran')->sum('jumlah');
        $totalPenarikan = $tabungan->transaksi()->where('jenis_transaksi', 'Penarikan')->sum('jumlah');
        $tabungan->total_setoran = $setoranAwal + $totalSetoranTransaksi;
        $tabungan->total_penarikan = $totalPenarikan;
    }

    $pdf = \PDF::loadView('yayasan.laporan.tabungan.export_filtered_pdf', compact('tabungans'));
    return $pdf->download('Rekap_Tabungan_Yayasan_Filtered.pdf');
}

}