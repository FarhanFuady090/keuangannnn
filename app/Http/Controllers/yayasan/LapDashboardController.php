<?php

namespace App\Http\Controllers\yayasan;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use App\Models\Siswa;
use App\Models\UnitPendidikan;
use App\Models\TransaksiTabungan;
use App\Models\TransaksiKas;
use App\Models\Kas;
use App\Models\TahunAjaran;
use App\Models\Tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Tambahkan import ini
use Carbon\Carbon;

class LapDashboardController extends Controller
{
    public function index(Request $request)
    {
        // === Ambil Tahun Ajaran Aktif berdasarkan nama tahun ajaran (gabungan Ganjil & Genap)
        $tahunAjaranAktifList = TahunAjaran::where('status', 'Aktif')->get();
        $tahunAjaranGroup = $tahunAjaranAktifList->groupBy('tahun_ajaran')->first();
        $tahunAjaranIds = $tahunAjaranGroup ? $tahunAjaranGroup->pluck('id')->toArray() : [];

        // === Hitung total tagihan yang sudah lunas dari tahun ajaran aktif
        $totalTagihanTerbayar = 0;
        if (!empty($tahunAjaranIds)) {
            $totalTagihanTerbayar = Tagihan::whereIn('tahun_ajaran_id', $tahunAjaranIds)
                ->where('status', 'lunas')
                ->sum('jumlah_dibayar');
        }

        $siswaAktif = Siswa::where('status', 'Aktif')->count();
        $siswaNonAktif = Siswa::where('status', 'Non Aktif')->count();
        $totalSiswa = Siswa::count();

        $totalKasMasuk = TransaksiKas::join('kas', 'transaksi_kas.kas_id', '=', 'kas.id')
            ->where('kas.kategori', 'Pemasukan')
            ->where('kas.status', 'Aktif')
            ->sum('nominal');

        $totalKasKeluar = TransaksiKas::join('kas', 'transaksi_kas.kas_id', '=', 'kas.id')
            ->where('kas.kategori', 'Pengeluaran')
            ->where('kas.status', 'Aktif')
            ->sum('nominal');

        $totalKas = $totalKasMasuk - $totalKasKeluar;

        $totalSetoranTransaksi = TransaksiTabungan::join('tabungans', 'transaksi_tabungans.tabungan_id', '=', 'tabungans.id')
            ->where('transaksi_tabungans.jenis_transaksi', 'Setoran')
            ->where('tabungans.status', 'Aktif')
            ->sum('transaksi_tabungans.jumlah');

        $totalSetoranAwal = Tabungan::where('status', 'Aktif')->sum('saldo_awal');
        $totalTabunganMasuk = $totalSetoranTransaksi + $totalSetoranAwal;

        $totalTabunganKeluar = TransaksiTabungan::join('tabungans', 'transaksi_tabungans.tabungan_id', '=', 'tabungans.id')
            ->where('transaksi_tabungans.jenis_transaksi', 'Penarikan')
            ->where('tabungans.status', 'Aktif')
            ->sum('transaksi_tabungans.jumlah');

        $totalTabunganAkhir = Tabungan::where('status', 'Aktif')->get()->sum(function ($tabungan) {
            $setoran = TransaksiTabungan::where('tabungan_id', $tabungan->id)
                ->where('jenis_transaksi', 'Setoran')
                ->sum('jumlah');
            $penarikan = TransaksiTabungan::where('tabungan_id', $tabungan->id)
                ->where('jenis_transaksi', 'Penarikan')
                ->sum('jumlah');
            return $tabungan->saldo_awal + $setoran - $penarikan;
        });

        $totalPemasukan = $totalKasMasuk + $totalTagihanTerbayar; // Perbaikan di sini
        $totalPengeluaran = $totalKasKeluar;
        $total = $totalPemasukan - $totalPengeluaran;
        $totalUnit = UnitPendidikan::where('status', 'Aktif')->count();

        $siswaPerUnit = Siswa::select(
            'unitpendidikan_id',
            DB::raw("SUM(CASE WHEN status = 'Aktif' THEN 1 ELSE 0 END) AS aktif"),
            DB::raw("SUM(CASE WHEN status = 'Non Aktif' THEN 1 ELSE 0 END) AS non_aktif"),
            DB::raw("SUM(CASE WHEN status = 'Drop Out' THEN 1 ELSE 0 END) AS drop_out"),
            DB::raw("SUM(CASE WHEN status = 'Lulus' THEN 1 ELSE 0 END) AS lulus"),
            DB::raw("SUM(CASE WHEN status = 'Pindah' THEN 1 ELSE 0 END) AS pindah"),
            DB::raw("COUNT(*) AS total")
        )
            ->whereNotNull('unitpendidikan_id')
            ->groupBy('unitpendidikan_id')
            ->with('unitpendidikan:id,namaunit')
            ->get();

        $keuanganPerUnit = collect();
        $allUnits = UnitPendidikan::where('status', 'Aktif')->get();

        foreach ($allUnits as $unit) {
            $unitId = $unit->id;

            $totalSetoranTransaksi = TransaksiTabungan::join('tabungans', 'transaksi_tabungans.tabungan_id', '=', 'tabungans.id')
                ->join('siswas', 'tabungans.siswa_id', '=', 'siswas.id')
                ->where('transaksi_tabungans.jenis_transaksi', 'Setoran')
                ->where('tabungans.status', 'Aktif')
                ->where('siswas.unitpendidikan_id', $unitId)
                ->sum('transaksi_tabungans.jumlah');

            $totalSaldoAwal = Tabungan::join('siswas', 'tabungans.siswa_id', '=', 'siswas.id')
                ->where('tabungans.status', 'Aktif')
                ->where('siswas.unitpendidikan_id', $unitId)
                ->sum('tabungans.saldo_awal');

            $totalSaldoMasuk = $totalSetoranTransaksi + $totalSaldoAwal;

            $totalKasMasuk = Schema::hasColumn('transaksi_kas', 'unitpendidikan_id') ?
                TransaksiKas::join('kas', 'transaksi_kas.kas_id', '=', 'kas.id')
                ->where('kas.kategori', 'Pemasukan')
                ->where('kas.status', 'Aktif')
                ->where('transaksi_kas.unitpendidikan_id', $unitId)
                ->sum('transaksi_kas.nominal') : 0;

            $totalTagihanTerbayar = 0;
            $totalTagihanBelumTerbayar = 0;

            if (!empty($tahunAjaranIds)) {
                $tagihanUnit = Tagihan::join('siswas', 'tagihan.siswa_id', '=', 'siswas.id')
                    ->where('siswas.unitpendidikan_id', $unitId)
                    ->whereIn('tagihan.tahun_ajaran_id', $tahunAjaranIds);

                $totalTagihanTerbayar = (clone $tagihanUnit)->where('tagihan.status', 'lunas')->sum('jumlah_dibayar');
                $totalTagihanBelumTerbayar = (clone $tagihanUnit)->where('tagihan.status', 'belum')->sum(DB::raw('nominal - jumlah_dibayar'));
            }

            $totalSaldoKeluar = TransaksiTabungan::join('tabungans', 'transaksi_tabungans.tabungan_id', '=', 'tabungans.id')
                ->join('siswas', 'tabungans.siswa_id', '=', 'siswas.id')
                ->where('transaksi_tabungans.jenis_transaksi', 'Penarikan')
                ->where('tabungans.status', 'Aktif')
                ->where('siswas.unitpendidikan_id', $unitId)
                ->sum('transaksi_tabungans.jumlah');

            $totalKasKeluar = Schema::hasColumn('transaksi_kas', 'unitpendidikan_id') ?
                TransaksiKas::join('kas', 'transaksi_kas.kas_id', '=', 'kas.id')
                ->where('kas.kategori', 'Pengeluaran')
                ->where('kas.status', 'Aktif')
                ->where('transaksi_kas.unitpendidikan_id', $unitId)
                ->sum('transaksi_kas.nominal') : 0;

            $totalSaldoAkhir = Tabungan::join('siswas', 'tabungans.siswa_id', '=', 'siswas.id')
                ->where('tabungans.status', 'Aktif')
                ->where('siswas.unitpendidikan_id', $unitId)
                ->get()
                ->sum(function ($tabungan) {
                    $setoran = TransaksiTabungan::where('tabungan_id', $tabungan->id)
                        ->where('jenis_transaksi', 'Setoran')
                        ->sum('jumlah');
                    $penarikan = TransaksiTabungan::where('tabungan_id', $tabungan->id)
                        ->where('jenis_transaksi', 'Penarikan')
                        ->sum('jumlah');
                    return $tabungan->saldo_awal + $setoran - $penarikan;
                });

            if ($totalSaldoMasuk > 0 || $totalKasMasuk > 0 || $totalSaldoKeluar > 0 || $totalKasKeluar > 0 || $totalSaldoAkhir > 0) {
                $keuanganPerUnit->push((object)[
                    'unitpendidikan_id' => $unitId,
                    'unitpendidikan' => (object)[
                        'id' => $unit->id,
                        'namaunit' => $unit->namaunit ?? 'Unit ' . $unit->id
                    ],
                    'total_saldo_masuk' => $totalSaldoMasuk,
                    'total_kas_masuk' => $totalKasMasuk,
                    'total_tagihan_terbayar' => $totalTagihanTerbayar,
                    'total_saldo_keluar' => $totalSaldoKeluar,
                    'total_kas_keluar' => $totalKasKeluar,
                    'total_tagihan_belum_terbayar' => $totalTagihanBelumTerbayar,
                    'total_saldo_akhir' => $totalSaldoAkhir,
                    'total_kas' => $totalKasMasuk - $totalKasKeluar,
                    'total_tagihan' => $totalTagihanTerbayar + $totalTagihanBelumTerbayar,
                    'total_pemasukan' => $totalSaldoMasuk + $totalKasMasuk + $totalTagihanTerbayar,
                    'total_pengeluaran' => $totalSaldoKeluar + $totalKasKeluar,
                    'total_akhir' => $totalSaldoMasuk + $totalKasMasuk + $totalTagihanTerbayar - ($totalSaldoKeluar + $totalKasKeluar)
                ]);
            }
        }


        // Ambil semua unit pendidikan yang aktif
        $allUnits = UnitPendidikan::where('status', 'Aktif')->get();
        $keuanganPerUnit = collect();

        // Ambil filter dari request
        $tahunAjaran = $request->input('tahun_ajaran');
        $semester = $request->input('semester');
        $tahun = $request->input('tahun');
        $bulan = $request->input('bulan');

        $tanggalMulai = null;
        $tanggalSelesai = null;

        // Prioritas filter berdasarkan Tahun Ajaran
        if ($tahunAjaran) {
            if ($semester === 'Ganjil') {
                $tanggalMulai = Carbon::create($tahunAjaran, 7, 1)->startOfDay();
                $tanggalSelesai = Carbon::create($tahunAjaran, 12, 31)->endOfDay();
            } elseif ($semester === 'Genap') {
                $tanggalMulai = Carbon::create($tahunAjaran + 1, 1, 1)->startOfDay();
                $tanggalSelesai = Carbon::create($tahunAjaran + 1, 6, 30)->endOfDay();
            } else {
                $tanggalMulai = Carbon::create($tahunAjaran, 7, 1)->startOfDay();
                $tanggalSelesai = Carbon::create($tahunAjaran + 1, 6, 30)->endOfDay();
            }
        } elseif ($semester && !$tahun) {
            $now = now()->year;
            if ($semester === 'Ganjil') {
                $tanggalMulai = Carbon::create($now, 7, 1)->startOfDay();
                $tanggalSelesai = Carbon::create($now, 12, 31)->endOfDay();
            } elseif ($semester === 'Genap') {
                $tanggalMulai = Carbon::create($now, 1, 1)->startOfDay();
                $tanggalSelesai = Carbon::create($now, 6, 30)->endOfDay();
            }
        } elseif ($bulan && $tahun) {
            $tanggalMulai = Carbon::create($tahun, $bulan, 1)->startOfMonth();
            $tanggalSelesai = Carbon::create($tahun, $bulan, 1)->endOfMonth();
        } elseif ($bulan && !$tahun) {
            return back()->with('error', 'Silakan pilih tahun jika ingin memfilter berdasarkan bulan.');
        } elseif ($tahun && !$bulan) {
            $tanggalMulai = Carbon::create($tahun, 1, 1)->startOfDay();
            $tanggalSelesai = Carbon::create($tahun, 12, 31)->endOfDay();
        }

        // Loop tiap unit
        foreach ($allUnits as $unit) {
            $unitId = $unit->id;

            // Total Saldo Masuk (Setoran + Saldo Awal)
            $querySetoran = TransaksiTabungan::join('tabungans', 'transaksi_tabungans.tabungan_id', '=', 'tabungans.id')
                ->join('siswas', 'tabungans.siswa_id', '=', 'siswas.id')
                ->where('transaksi_tabungans.jenis_transaksi', 'Setoran')
                ->where('tabungans.status', 'Aktif')
                ->where('siswas.unitpendidikan_id', $unitId);

            if ($tanggalMulai && $tanggalSelesai) {
                $querySetoran->whereBetween('transaksi_tabungans.created_at', [$tanggalMulai, $tanggalSelesai]);
            }

            $totalSetoranTransaksi = $querySetoran->sum('transaksi_tabungans.jumlah');

            $querySaldoAwal = Tabungan::join('siswas', 'tabungans.siswa_id', '=', 'siswas.id')
                ->where('tabungans.status', 'Aktif')
                ->where('siswas.unitpendidikan_id', $unitId);

            if ($tanggalMulai && $tanggalSelesai) {
                $querySaldoAwal->whereBetween('tabungans.created_at', [$tanggalMulai, $tanggalSelesai]);
            }

            $totalSaldoAwal = $querySaldoAwal->sum('tabungans.saldo_awal');
            $totalSaldoMasuk = $totalSetoranTransaksi + $totalSaldoAwal;

            // Kas Masuk
            $totalKasMasuk = 0;
            if (Schema::hasColumn('transaksi_kas', 'unitpendidikan_id')) {
                $queryKasMasuk = TransaksiKas::join('kas', 'transaksi_kas.kas_id', '=', 'kas.id')
                    ->where('kas.kategori', 'Pemasukan')
                    ->where('kas.status', 'Aktif')
                    ->where('transaksi_kas.unitpendidikan_id', $unitId);

                if ($tanggalMulai && $tanggalSelesai) {
                    $queryKasMasuk->whereBetween('transaksi_kas.created_at', [$tanggalMulai, $tanggalSelesai]);
                }

                $totalKasMasuk = $queryKasMasuk->sum('transaksi_kas.nominal');
            }

            // Tagihan Terbayar & Belum Terbayar
            $totalTagihanTerbayar = 0;
            $totalTagihanBelumTerbayar = 0;
            $tahunAjaranIds = $tahunAjaran ? [$tahunAjaran] : TahunAjaran::pluck('id')->toArray();

            if (!empty($tahunAjaranIds)) {
                $tagihanUnit = Tagihan::join('siswas', 'tagihan.siswa_id', '=', 'siswas.id')
                    ->where('siswas.unitpendidikan_id', $unitId)
                    ->whereIn('tagihan.tahun_ajaran_id', $tahunAjaranIds);

                $totalTagihanTerbayar = (clone $tagihanUnit)
                    ->whereRaw('LOWER(tagihan.status) = ?', ['lunas'])
                    ->sum(DB::raw('COALESCE(jumlah_dibayar, 0)'));

                $totalTagihanBelumTerbayar = (clone $tagihanUnit)
                    ->whereRaw('LOWER(tagihan.status) = ?', ['belum'])
                    ->sum(DB::raw('COALESCE(nominal, 0) - COALESCE(jumlah_dibayar, 0)'));
            }

            // Saldo Keluar
            $queryPenarikan = TransaksiTabungan::join('tabungans', 'transaksi_tabungans.tabungan_id', '=', 'tabungans.id')
                ->join('siswas', 'tabungans.siswa_id', '=', 'siswas.id')
                ->where('transaksi_tabungans.jenis_transaksi', 'Penarikan')
                ->where('tabungans.status', 'Aktif')
                ->where('siswas.unitpendidikan_id', $unitId);

            if ($tanggalMulai && $tanggalSelesai) {
                $queryPenarikan->whereBetween('transaksi_tabungans.created_at', [$tanggalMulai, $tanggalSelesai]);
            }

            $totalSaldoKeluar = $queryPenarikan->sum('transaksi_tabungans.jumlah');

            // Kas Keluar
            $totalKasKeluar = 0;
            if (Schema::hasColumn('transaksi_kas', 'unitpendidikan_id')) {
                $queryKasKeluar = TransaksiKas::join('kas', 'transaksi_kas.kas_id', '=', 'kas.id')
                    ->where('kas.kategori', 'Pengeluaran')
                    ->where('kas.status', 'Aktif')
                    ->where('transaksi_kas.unitpendidikan_id', $unitId);

                if ($tanggalMulai && $tanggalSelesai) {
                    $queryKasKeluar->whereBetween('transaksi_kas.created_at', [$tanggalMulai, $tanggalSelesai]);
                }

                $totalKasKeluar = $queryKasKeluar->sum('transaksi_kas.nominal');
            }

            // Saldo Akhir
            $queryTabungan = Tabungan::join('siswas', 'tabungans.siswa_id', '=', 'siswas.id')
                ->where('tabungans.status', 'Aktif')
                ->where('siswas.unitpendidikan_id', $unitId);

            if ($tanggalMulai && $tanggalSelesai) {
                $queryTabungan->whereBetween('tabungans.created_at', [$tanggalMulai, $tanggalSelesai]);
            }

            $tabungansForUnit = $queryTabungan->get();

            $totalSaldoAkhir = $tabungansForUnit->sum(function ($tabungan) use ($tanggalMulai, $tanggalSelesai) {
                $setoran = TransaksiTabungan::where('tabungan_id', $tabungan->id)
                    ->where('jenis_transaksi', 'Setoran');
                $penarikan = TransaksiTabungan::where('tabungan_id', $tabungan->id)
                    ->where('jenis_transaksi', 'Penarikan');

                if ($tanggalMulai && $tanggalSelesai) {
                    $setoran->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai]);
                    $penarikan->whereBetween('created_at', [$tanggalMulai, $tanggalSelesai]);
                }

                return $tabungan->saldo_awal + $setoran->sum('jumlah') - $penarikan->sum('jumlah');
            });

            // Simpan hanya unit yang memiliki transaksi
            if ($totalSaldoMasuk > 0 || $totalKasMasuk > 0 || $totalSaldoKeluar > 0 || $totalKasKeluar > 0 || $totalSaldoAkhir > 0 || $totalTagihanTerbayar > 0) {
                $keuanganPerUnit->push((object)[
                    'unitpendidikan_id' => $unitId,
                    'unitpendidikan' => (object)[
                        'id' => $unit->id,
                        'namaunit' => $unit->namaunit ?? 'Unit ' . $unit->id
                    ],
                    'total_saldo_masuk' => $totalSaldoMasuk,
                    'total_kas_masuk' => $totalKasMasuk,
                    'total_saldo_keluar' => $totalSaldoKeluar,
                    'total_kas_keluar' => $totalKasKeluar,
                    'total_tagihan_terbayar' => $totalTagihanTerbayar,
                    'total_tagihan_belum_terbayar' => $totalTagihanBelumTerbayar,
                    'total_saldo_akhir' => $totalSaldoAkhir,
                    'total_kas' => $totalKasMasuk - $totalKasKeluar,
                    'total_tagihan' => $totalTagihanTerbayar + $totalTagihanBelumTerbayar,
                    'total_pemasukan' => $totalSaldoMasuk + $totalKasMasuk + $totalTagihanTerbayar,
                    'total_pengeluaran' => $totalSaldoKeluar + $totalKasKeluar,
                    'total_akhir' => $totalSaldoMasuk + $totalKasMasuk + $totalTagihanTerbayar - ($totalSaldoKeluar + $totalKasKeluar),
                ]);
            }
        }



        // === Grafik
        $setoranTransaksi = TransaksiTabungan::join('tabungans', 'transaksi_tabungans.tabungan_id', '=', 'tabungans.id')
            ->where('transaksi_tabungans.jenis_transaksi', 'Setoran')
            ->where('tabungans.status', 'Aktif')
            ->selectRaw('MONTH(transaksi_tabungans.created_at) as month, SUM(transaksi_tabungans.jumlah) as total')
            ->groupBy(DB::raw('MONTH(transaksi_tabungans.created_at)'))
            ->pluck('total', 'month');

        $setoranAwal = Tabungan::where('status', 'Aktif')
            ->selectRaw('MONTH(created_at) as month, SUM(saldo_awal) as total')
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->pluck('total', 'month');

        $setoranGabungan = [];
        $penarikanDataFormatted = [];

        for ($i = 1; $i <= 12; $i++) {
            $setoranGabungan[] = $setoranTransaksi->get($i, 0) + $setoranAwal->get($i, 0);
            $penarikanDataFormatted[] = TransaksiTabungan::join('tabungans', 'transaksi_tabungans.tabungan_id', '=', 'tabungans.id')
                ->where('transaksi_tabungans.jenis_transaksi', 'Penarikan')
                ->where('tabungans.status', 'Aktif')
                ->whereMonth('transaksi_tabungans.created_at', $i)
                ->sum('jumlah');
        }

        $labels = range(1, 12);


        return view('yayasan.dashboard', compact(
            'tahunAjaran',
            'totalPemasukan',
            'totalPemasukan',
            'totalPengeluaran',
            'total',
            'siswaAktif',
            'siswaNonAktif',
            'totalSiswa',
            'totalTabunganMasuk',
            'totalTabunganKeluar',
            'totalTabunganAkhir',
            'totalKasMasuk',
            'totalKasKeluar',
            'totalKas',
            'totalSaldoMasuk',
            'totalSaldoKeluar',
            'totalSaldoAkhir',
            'totalUnit',
            'keuanganPerUnit',
            'siswaPerUnit',
            'labels',
            'setoranGabungan',
            'penarikanDataFormatted',
            'tahunAjaranAktifList',
            'totalTagihanTerbayar'
        ));
    }
}
