<?php

namespace App\Http\Controllers\yayasan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\UnitPendidikan;
use App\Models\JenisPembayaran;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\Kelas;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TagihanExport; // This will be the custom export class
use App\Exports\TagihanSiswaExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LapTagihanController extends Controller
{
    // main data
    public function index(Request $request)
    {
        $unit = $request->get('unitpendidikan_id');
        $kelas = $request->get('kelas');
        $search = $request->get('search');
        $tahunAjaran = $request->get('tahun_ajaran');
        $semester = $request->get('semester');
        $jenisPembayaranId = $request->get('jenis_pembayaran_id');
        $type = $request->filled('type') ? strtolower(trim($request->get('type'))) : null;

        $jenisPembayaranAktif = JenisPembayaran::query()
            ->when($type, fn($query) => $query->where('type', $type))
            ->with('unitPendidikan')
            ->get();

        $tipePembayaranList = JenisPembayaran::select('type')->distinct()->pluck('type');

        // Query dasar untuk tagihan dengan filter
        $queryTagihan = Tagihan::with(['siswa.kelas', 'unitPendidikan'])
            ->when($unit, fn($q) => $q->whereHas('siswa', fn($sub) => $sub->where('unitpendidikan_id', $unit)))
            ->when($kelas, fn($q) => $q->whereHas('siswa', fn($sub) => $sub->where('kelas_id', $kelas)))
            ->when($type, fn($q) => $q->whereHas('jenispembayaran', fn($sub) => $sub->where('type', $type)))
            ->when($jenisPembayaranId, fn($q) => $q->where('jenis_pembayaran_id', $jenisPembayaranId))
            ->when($search, fn($q) => $q->whereHas(
                'siswa',
                fn($sub) =>
                $sub->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%")
            ));

        // Ambil ID siswa yang memiliki tagihan sesuai filter
        $siswaIdsWithTagihan = $queryTagihan->pluck('siswa_id')->unique();

        // Query siswa yang hanya menampilkan siswa yang memiliki tagihan
        $querySiswa = Siswa::with('unitPendidikan', 'kelas')
            ->whereIn('id', $siswaIdsWithTagihan) // Filter hanya siswa yang memiliki tagihan
            ->when($unit, fn($q) => $q->where('unitpendidikan_id', $unit))
            ->when($kelas, fn($q) => $q->where('kelas_id', $kelas))
            ->when($search, fn($q) => $q->where(function ($q2) use ($search) {
                $q2->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            }));

        $siswas = $querySiswa->get();

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $tanggalAwal = Carbon::parse($request->tanggal_awal)->startOfDay();
            $tanggalAkhir = Carbon::parse($request->tanggal_akhir)->endOfDay();
            $queryTagihan->whereBetween('tanggal_bayar', [$tanggalAwal, $tanggalAkhir]);
        } elseif ($tahunAjaran) {
            $tahunAjaran = (int) $tahunAjaran;
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
            $queryTagihan->whereBetween('tanggal_bayar', [$tanggalMulai, $tanggalSelesai]);
        }

        $tagihanFiltered = $queryTagihan->get();

        $tagihanTotal = (clone $queryTagihan)->get();

        $tagihan = Tagihan::with(['siswa', 'jenispembayaran'])
            ->when($jenisPembayaranId, fn($q) => $q->where('jenis_pembayaran_id', $jenisPembayaranId))
            ->when($type, fn($q) => $q->whereHas('jenispembayaran', fn($q2) => $q2->where('type', $type)))
            ->get();

        $filteredTagihan = $tagihan->filter(fn($item) => !$unit || ($item->siswa && $item->siswa->unitpendidikan_id == $unit));

        $bulanList = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        $pembayaranData = [];
        foreach ($siswas as $siswa) {
            $dataPerBulan = [];
            foreach ($bulanList as $bulan) {
                $tagihan = $tagihanFiltered->where('siswa_id', $siswa->id)->where('bulan', $bulan);
                $dataPerBulan[$bulan] = [
                    'tagihan' => $tagihan->sum('nominal'),
                    'terbayar' => $tagihan->sum('jumlah_dibayar'),
                    'belum' => $tagihan->sum('nominal') - $tagihan->sum('jumlah_dibayar'),
                    'tanggal_bayar' => $tagihan->max('tanggal_bayar'),
                ];
            }
            $pembayaranData[$siswa->id] = $dataPerBulan;
        }

        // Lakukan pemrosesan seperti mapping, filtering, pengelompokan, dsb.
        $processedSiswas = $siswas->map(function ($siswa) use ($pembayaranData) {
            // Tambahkan data tambahan jika perlu
            $siswa->pembayaran = $pembayaranData[$siswa->id] ?? [];
            return $siswa;
        });

        // SISWA PAGINATION
        $currentPageSiswa = LengthAwarePaginator::resolveCurrentPage('page');
        $perPageSiswa = 15;

        $currentItemsSiswa = $siswas->slice(($currentPageSiswa - 1) * $perPageSiswa, $perPageSiswa)->values();

        $siswaQueryString = $request->except('rekap_page'); // Hanya hapus rekap_page

        $siswas = new LengthAwarePaginator(
            $currentItemsSiswa,
            $siswas->count(),
            $perPageSiswa,
            $currentPageSiswa,
            ['pageName' => 'page', 'path' => request()->url(), 'query' => $siswaQueryString]
        );

        $tagihan = $queryTagihan->get();

        $grouped = $filteredTagihan->groupBy('jenis_pembayaran_id');

        // Ubah ke indexed array agar bisa di-slice
        $groupedArray = $grouped->all();
        $rekapCollection = collect($groupedArray);

        $currentPageRekap = LengthAwarePaginator::resolveCurrentPage('rekap_page');
        $perPageRekap = 1;

        // Slice sebelum diproses map
        $currentItemsRaw = $rekapCollection->slice(($currentPageRekap - 1) * $perPageRekap, $perPageRekap);

        $totalPerJenisPembayaran = $tagihanTotal->groupBy('jenis_pembayaran_id')->map(function ($tagihanGroup) use ($tagihanFiltered) {
            $jenisId = $tagihanGroup->first()->jenis_pembayaran_id;
            $totalTagihan = $tagihanGroup->sum('nominal');
            $totalTerbayar = $tagihanFiltered->where('jenis_pembayaran_id', $jenisId)->sum('jumlah_dibayar');
            return [
                'total_tagihan' => $totalTagihan,
                'total_terbayar' => $totalTerbayar,
                'total_belum_terbayar' => $totalTagihan - $totalTerbayar,
            ];
        });

        $rekapPerJenis = $filteredTagihan->groupBy('jenis_pembayaran_id')->map(function ($group) {
            $first = $group->first();
            return [
                'nama' => $first->jenispembayaran->nama_pembayaran,
                'type' => $first->jenispembayaran->type,
                'unit' => optional($first->siswa->unitpendidikan)->namaUnit ?? '-',
                'total_terbayar' => $group->sum('jumlah_dibayar'),
                'total_tagihan' => $group->sum('nominal'),
                'belum_terbayar' => $group->sum('nominal') - $group->sum('jumlah_dibayar'),
            ];
        })->values();

        // ✅ Perbaiki key yang digunakan untuk mengambil data dari $rekapPerJenis
        $processedTagihan = $tagihan->map(function ($tagihan) use ($rekapPerJenis) {
            $tagihan->pembayaran = $rekapPerJenis[$tagihan->jenis_pembayaran_id] ?? [];
            return $tagihan;
        });

        $rekapQueryString = $request->except('page'); // Hanya hapus siswa page

        $paginatedRekapPerJenis = new LengthAwarePaginator(
            $currentItemsRaw,
            $rekapCollection->count(),
            $perPageRekap,
            $currentPageRekap,
            ['pageName' => 'rekap_page', 'path' => request()->url(), 'query' => $rekapQueryString]
        );

        $unitPendidikanList = UnitPendidikan::all();
        $jenisPembayaran = JenisPembayaran::where('status', 'Aktif')->with('unitPendidikan')->get();
        $typeList = JenisPembayaran::select('type')->distinct()->pluck('type');

        $kelasList = collect();
        if ($jenisPembayaranId) {
            $selectedJenis = JenisPembayaran::find($jenisPembayaranId);
            if ($selectedJenis) {
                $kelasList = Kelas::where('unitpendidikan_id', $selectedJenis->idunitpendidikan)->get();
            }
        } elseif ($unit) {
            $kelasList = Kelas::where('unitpendidikan_id', $unit)->get();
        }

        return view('yayasan.laporan.tagihan.index', compact(
            'siswas',
            'tagihan',
            'paginatedRekapPerJenis',
            'jenisPembayaranAktif',
            'unitPendidikanList',
            'kelasList',
            'bulanList',
            'pembayaranData',
            'unit',
            'kelas',
            'search',
            'tahunAjaran',
            'jenisPembayaranId',
            'semester',
            'type',
            'totalPerJenisPembayaran',
            'jenisPembayaran',
            'tipePembayaranList',
            'typeList'
        ));
    }

    /**
     * Get unit pendidikan berdasarkan jenis pembayaran
     */
    public function getUnitByPayment(Request $request)
    {
        try {
            $jenisPembayaranId = $request->input('jenis_pembayaran_id');

            if (!$jenisPembayaranId) {
                return response()->json([]);
            }

            // Debug: Log request
            \Log::info('Request jenis pembayaran ID: ' . $jenisPembayaranId);

            // Cek apakah jenis pembayaran ada
            $jenisPembayaran = JenisPembayaran::find($jenisPembayaranId);
            if (!$jenisPembayaran) {
                \Log::warning('Jenis pembayaran tidak ditemukan: ' . $jenisPembayaranId);
                return response()->json([]);
            }

            // Ambil unit pendidikan yang terkait dengan jenis pembayaran
            // Sesuaikan dengan nama tabel yang benar (cek nama tabel di database)
            $units = UnitPendidikan::whereHas('siswa', function ($query) use ($jenisPembayaranId) {
                $query->whereHas('tagihan', function ($subQuery) use ($jenisPembayaranId) {
                    $subQuery->where('jenis_pembayaran_id', $jenisPembayaranId);
                });
            })
                ->distinct()
                ->select('id', 'namaUnit') // Pastikan kolom ini sesuai dengan database
                ->orderBy('namaUnit')
                ->get();

            // Debug: Log hasil query
            \Log::info('Units found: ' . $units->count());
            \Log::info('Units data: ' . $units->toJson());

            return response()->json($units);
        } catch (\Exception $e) {
            \Log::error('Error in getUnitByPayment: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Gagal mengambil data unit pendidikan'], 500);
        }
    }

    /**
     * Get semua unit pendidikan
     */
    public function getAllUnits(Request $request)
    {
        try {
            $units = UnitPendidikan::select('id', 'namaUnit') // Sesuaikan dengan kolom yang ada
                ->orderBy('namaUnit')
                ->get();

            // Debug: Log hasil
            \Log::info('All units found: ' . $units->count());

            return response()->json($units);
        } catch (\Exception $e) {
            \Log::error('Error in getAllUnits: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data unit pendidikan'], 500);
        }
    }

    /**
     * Get semua kelas by unit
     */
    public function getKelasByJenis(Request $request)
    {
        $jenis = JenisPembayaran::find($request->jenis_pembayaran_id);

        if (!$jenis) {
            return response()->json([]);
        }

        $kelas = Kelas::where('unitpendidikan_id', $jenis->idunitpendidikan)->get();

        return response()->json($kelas);
    }

    /**
     * Get semua jenis pembayaran
     */
    public function getJenisPembayaran(Request $request)
    {
        $unitId = $request->query('unit_id');
        $tahunId = $request->query('tahun_id');

        $data = JenisPembayaran::where('idunitpendidikan', $unitId)
            ->where('id_tahunajaran', $tahunId)
            ->where('status', 'Aktif')
            ->get(['id', 'nama_pembayaran', 'type']);

        return response()->json($data);
    }

    /**
     * Get semua siswan
     */
    public function getSiswa(Request $request)
    {
        $unitId = $request->query('unit_id');
        $kelasId = $request->query('kelas_id');

        $query = Siswa::where('unitpendidikan_id', $unitId)
            ->where('status', 'Aktif');

        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }

        $data = $query->get(['id', 'nama', 'nis', 'kelas_id']);

        return response()->json($data);
    }

    /**
     * Get semua kelas by unit
     */
    public function getKelasByUnit(Request $request)
    {
        $unitId = $request->get('unit_id');

        if (!$unitId) {
            return response()->json([]);
        }

        $kelas = Kelas::where('unitpendidikan_id', $unitId)->get(['id', 'nama_kelas']);

        return response()->json($kelas);
    }

    /**
     * Get semua nominal by jenis pembayran
     */
    public function getNominalJenisPembayaran(Request $request)
    {
        $id = $request->get('id');
        $jenis = JenisPembayaran::find($id);

        if (!$jenis) {
            return response()->json(['error' => 'Jenis tidak ditemukan'], 404);
        }

        return response()->json(['nominal' => $jenis->nominal_jenispembayaran]);
    }
}
