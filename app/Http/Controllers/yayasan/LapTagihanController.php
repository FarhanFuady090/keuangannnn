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
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LapTagihanController extends Controller
{
    // Daftar Siswa
    public function index(Request $request)
    {
        $unit = $request->get('unitpendidikan_id');
        $kelas = $request->get('kelas');
        $search = $request->get('search');
        $tahunAjaran = $request->get('tahun_ajaran');
        $semester = $request->get('semester');
        $jenisPembayaranId = $request->get('jenis_pembayaran_id');
        $type = $request->filled('type') ? $request->get('type') : null;
        $jenisPembayaranAktif = JenisPembayaran::query()
            ->when($type, function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->with('unitPendidikan')
            ->get();
        $type = trim(strtolower($request->get('type') ?? ''));

        // Ambil semua tipe unik dari tabel jenis_pembayarans
        $tipePembayaranList = JenisPembayaran::select('type')->distinct()->pluck('type');


        $querySiswa = Siswa::with('unitPendidikan', 'kelas');

        if ($unit) {
            $querySiswa->where('unitpendidikan_id', $unit);
        }

        if ($kelas) {
            $querySiswa->where('kelas_id', $kelas);
        }

        if ($search) {
            $querySiswa->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        $siswas = $querySiswa->paginate(15)->withQueryString();
        $queryTagihan = Tagihan::query()
            ->with(['siswa.kelas', 'unitPendidikan'])
            ->when($request->unitpendidikan_id, function ($q) use ($request) {
                $q->whereHas('siswa', function ($query) use ($request) {
                    $query->where('unitpendidikan_id', $request->unitpendidikan_id);
                });
            })
            ->when(!empty($type), function ($q) use ($type) {
                $q->whereHas('jenispembayaran', function ($sub) use ($type) {
                    $sub->where('type', $type);
                });
            })
            ->when($jenisPembayaranId, function ($q) use ($jenisPembayaranId) {
                $q->where('jenis_pembayaran_id', $jenisPembayaranId);
            })
            ->when($kelas, fn($q) => $q->whereHas('siswa', fn($sub) => $sub->where('kelas_id', $kelas)))
            ->when($search, fn($q) => $q->whereHas('siswa', fn($sub) => $sub->where('nama', 'like', "%{$search}%")->orWhere('nis', 'like', "%{$search}%")));

        // Filter tanggal bayar manual
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
        // Query dasar tanpa filter tanggal bayar
        // Buat ulang query dasar TANPA filter tanggal bayar
        $queryTagihanTotal = Tagihan::with(['siswa.kelas', 'unitPendidikan'])
            ->when($request->unitpendidikan_id, function ($q) use ($request) {
                $q->whereHas('siswa', function ($query) use ($request) {
                    $query->where('unitpendidikan_id', $request->unitpendidikan_id);
                });
            })
            ->when(!empty($type), function ($q) use ($type) {
                $q->whereHas('jenispembayaran', function ($sub) use ($type) {
                    $sub->where('type', $type);
                });
            })
            ->when($jenisPembayaranId, function ($q) use ($jenisPembayaranId) {
                $q->where('jenis_pembayaran_id', $jenisPembayaranId);
            })
            ->when($kelas, fn($q) => $q->whereHas('siswa', fn($sub) => $sub->where('kelas_id', $kelas)))
            ->when($search, fn($q) => $q->whereHas('siswa', fn($sub) => $sub->where('nama', 'like', "%{$search}%")->orWhere('nis', 'like', "%{$search}%")));

        $tagihanTotal = $queryTagihanTotal->get(); // tanpa filter tanggal bayar

        // Tagihan TERBAYAR (dengan filter tanggal bayar)
        $tagihanTerbayar = $queryTagihan->get(); // ini pakai filter tanggal bayar yang aktif
        $tagihan = Tagihan::with(['siswa', 'jenispembayaran'])
            ->when($jenisPembayaranId, function ($q) use ($jenisPembayaranId) {
                $q->where('jenis_pembayaran_id', $jenisPembayaranId);
            })
            ->when($type, function ($q) use ($type) {
                $q->whereHas('jenispembayaran', function ($q2) use ($type) {
                    $q2->where('type', $type);
                });
            })
            ->get();
        $filteredTagihan = $tagihan->filter(function ($item) use ($request) {
            if ($request->filled('unitpendidikan_id')) {
                return $item->siswa && $item->siswa->unitpendidikan_id == $request->unitpendidikan_id;
            }
            return true;
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
        });
        // Manual pagination
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 1;
        $rekapCollection = collect($rekapPerJenis->values());
        $rekapPaginated = new LengthAwarePaginator(
            $rekapCollection->forPage($currentPage, $perPage),
            $rekapCollection->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        // Hitung total per jenis pembayaran
        $totalPerJenisPembayaran = $tagihanTotal
            ->groupBy('jenis_pembayaran_id')
            ->map(function ($tagihanGroup) use ($tagihanTerbayar) {
                $jenisId = $tagihanGroup->first()->jenis_pembayaran_id;
                $totalTagihan = $tagihanGroup->sum('nominal');

                $totalTerbayar = $tagihanTerbayar
                    ->where('jenis_pembayaran_id', $jenisId)
                    ->sum('jumlah_dibayar');

                $totalBelumTerbayar = $totalTagihan - $totalTerbayar;

                return [
                    'total_tagihan' => $totalTagihan,
                    'total_terbayar' => $totalTerbayar,
                    'total_belum_terbayar' => $totalBelumTerbayar,
                ];
            });

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

                $totalTagihan = $tagihan->sum('nominal');
                $totalTerbayar = $tagihan->sum('jumlah_dibayar');
                $tanggalBayar = $tagihan->max('tanggal_bayar');

                $dataPerBulan[$bulan] = [
                    'tagihan' => $totalTagihan,
                    'terbayar' => $totalTerbayar,
                    'belum' => $totalTagihan - $totalTerbayar,
                    'tanggal_bayar' => $tanggalBayar,
                ];
            }
            $pembayaranData[$siswa->id] = $dataPerBulan;
        }

        $unitPendidikanList = UnitPendidikan::all();
        $jenisPembayaran = JenisPembayaran::where('status', 'Aktif')->with('unitPendidikan')->get();
        // Ambil list type dari Jenis Pembayaran
        $typeList = JenisPembayaran::select('type')->distinct()->pluck('type');

        // Kelas berdasarkan jenis pembayaran atau unit pendidikan
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
            'rekapPaginated',
            'totalPerJenisPembayaran',
            'jenisPembayaran',
            'tipePembayaranList',
            'typeList'
        ));
    }

    public function getKelasByJenis(Request $request)
    {
        $jenis = JenisPembayaran::find($request->jenis_pembayaran_id);

        if (!$jenis) {
            return response()->json([]);
        }

        $kelas = Kelas::where('unitpendidikan_id', $jenis->idunitpendidikan)->get();

        return response()->json($kelas);
    }



    public function create(Request $request)
    {
        $unitpendidikan = UnitPendidikan::all();
        $tahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        $selectedUnit = $request->get('unit');
        $selectedTahun = $request->get('tahun');
        $selectedKelas = $request->get('kelas');

        $jenisPembayaran = [];
        $siswaList = [];
        $kelasList = collect();

        if ($selectedUnit) {
            $kelasList = Kelas::where('unitpendidikan_id', $selectedUnit)->get();
        }

        $jenisPembayaran = JenisPembayaran::where('idunitpendidikan', $selectedUnit)
            ->where('id_tahunajaran', $selectedTahun)
            ->where('status', 'Aktif')
            ->get();

        $query = Siswa::where('unitpendidikan_id', $selectedUnit)
            ->where('status', 'Aktif');

        if ($selectedKelas) {
            $query->where('kelas_id', $selectedKelas);
        }

        $siswaList = $query->get();

        return view('yayasan.laporan.tagihan.create', compact(
            'unitpendidikan',
            'tahunAjaran',
            'jenisPembayaran',
            'siswaList',
            'kelasList',
            'selectedUnit',
            'selectedTahun',
            'selectedKelas'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'jenis_pembayaran_id' => 'required|exists:jenispembayaran,id',
            'tahun_ajaran_id' => 'required|exists:tahunajaran,id',
            'siswa_ids' => 'required|array|min:1', // Pastikan ada minimal 1 siswa yang dipilih
            'siswa_ids.*' => 'exists:siswas,id',  // Validasi setiap siswa ID yang dipilih
        ]);

        $jenisPembayaran = JenisPembayaran::findOrFail($request->jenis_pembayaran_id);
        $type = $jenisPembayaran->type;

        $bulanMap = [
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
            'Desember'
        ];

        $nominal = $jenisPembayaran->nominal_jenispembayaran; // nominal pembayaran

        // Proses tagihan hanya untuk siswa yang dipilih
        foreach ($request->siswa_ids as $siswaId) {
            foreach ($request->tagihan as $siswaTagihanId => $inputNominal) {
                if ($siswaId == $siswaTagihanId && $nominal > 0) {
                    switch ($type) {
                        case 'Bulanan':
                            foreach ($bulanMap as $bulan) {
                                Tagihan::create([
                                    'siswa_id' => $siswaId,
                                    'jenis_pembayaran_id' => $jenisPembayaran->id,
                                    'tahun_ajaran_id' => $request->tahun_ajaran_id,
                                    'bulan' => $bulan,
                                    'nominal' => $nominal,
                                    'jumlah_dibayar' => 0,
                                    'status' => 'belum'
                                ]);
                            }
                            break;

                        case 'Semester':
                            foreach (['Semester 1', 'Semester 2'] as $semesterBulan) {
                                Tagihan::create([
                                    'siswa_id' => $siswaId,
                                    'jenis_pembayaran_id' => $jenisPembayaran->id,
                                    'tahun_ajaran_id' => $request->tahun_ajaran_id,
                                    'bulan' => $semesterBulan,
                                    'nominal' => $nominal,
                                    'jumlah_dibayar' => 0,
                                    'status' => 'belum'
                                ]);
                            }
                            break;

                        case 'Tahunan':
                        case 'Bebas':
                            Tagihan::create([
                                'siswa_id' => $siswaId,
                                'jenis_pembayaran_id' => $jenisPembayaran->id,
                                'tahun_ajaran_id' => $request->tahun_ajaran_id,
                                'bulan' => null,
                                'nominal' => $nominal,
                                'jumlah_dibayar' => 0,
                                'status' => 'belum'
                            ]);
                            break;
                    }
                }
            }
        }

        return redirect()->route('yayasan.laporan.tagihan.create')->with('success', 'Tagihan berhasil dibuat.');
    }

    // Rincian Tagihan Siswa
    public function show(Request $request, $siswaId)
    {
        $siswa = Siswa::findOrFail($siswaId);
        $unitPendidikanId = $siswa->unitpendidikan_id;  // Mengambil ID unit pendidikan siswa

        // Menyesuaikan jenis pembayaran berdasarkan unit pendidikan
        $jenisPembayaranList = JenisPembayaran::where('idunitpendidikan', $unitPendidikanId)->get();

        // Query untuk tagihan siswa
        $query = Tagihan::where('siswa_id', $siswaId);

        // Filter berdasarkan jenis pembayaran
        if ($request->has('jenis_pembayaran') && $request->jenis_pembayaran) {
            $query->where('jenis_pembayaran_id', $request->jenis_pembayaran);
        }

        // Filter berdasarkan status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        $perPage = $request->input('perPage', 15);
        $tagihans = $query->paginate($perPage)->withQueryString();

        return view('yayasan.laporan.tagihan.show', compact('siswa', 'tagihans', 'jenisPembayaranList'));
    }

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

    public function cetak(Request $request, Siswa $siswa)
    {
        $jenisPembayaranFilter = $request->get('jenis_pembayaran');

        $query = Tagihan::with('jenisPembayaran', 'tahunAjaran')
            ->where('siswa_id', $siswa->id);

        if ($jenisPembayaranFilter) {
            $query->where('jenis_pembayaran_id', $jenisPembayaranFilter);
        }

        $tagihans = $query->get();

        $totalTagihan = $tagihans->sum('nominal');
        $totalDibayar = $tagihans->sum('jumlah_dibayar');
        $sisaTagihan = $totalTagihan - $totalDibayar;

        $pdf = Pdf::loadView('yayasan.laporan.tagihan.cetak', compact('siswa', 'tagihans', 'totalTagihan', 'totalDibayar', 'sisaTagihan'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream("Kwitansi_{$siswa->nama}.pdf");
    }

    public function getKelasByUnit(Request $request)
    {
        $unitId = $request->get('unit_id');

        if (!$unitId) {
            return response()->json([]);
        }

        $kelas = Kelas::where('unitpendidikan_id', $unitId)->get(['id', 'nama_kelas']);

        return response()->json($kelas);
    }

    public function getNominalJenisPembayaran(Request $request)
    {
        $id = $request->get('id');
        $jenis = JenisPembayaran::find($id);

        if (!$jenis) {
            return response()->json(['error' => 'Jenis tidak ditemukan'], 404);
        }

        return response()->json(['nominal' => $jenis->nominal_jenispembayaran]);
    }

    public function cetakKwitansi(Tagihan $tagihan)
    {
        if ($tagihan->status !== 'lunas') {
            abort(403, 'Tagihan belum lunas');
        }

        $siswa = $tagihan->siswa; // pastikan relasi siswa ada
        $jenisPembayaran = $tagihan->jenisPembayaran;
        $tahunAjaran = $tagihan->tahunAjaran;

        $pdf = Pdf::loadView('yayasan.laporan.tagihan.kwitansi', compact('tagihan', 'siswa', 'jenisPembayaran', 'tahunAjaran'))
            ->setPaper('A5', 'landscape');

        return $pdf->stream("Kwitansi_{$siswa->nama}_{$tagihan->id}.pdf");
    }

    public function cetakMultipleKwitansi(Request $request)
    {
        $ids = $request->input('tagihan_ids', []);

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada tagihan yang dipilih.');
        }

        $tagihans = Tagihan::with(['siswa', 'jenisPembayaran', 'tahunAjaran'])
            ->whereIn('id', $ids)
            ->where('status', 'lunas')
            ->get();

        if ($tagihans->isEmpty()) {
            return redirect()->back()->with('error', 'Tagihan tidak valid atau belum lunas.');
        }

        $pdf = Pdf::loadView('yayasan.laporan.tagihan.kwitansi-multiple', compact('tagihans'))
            ->setPaper('A5', 'portrait');

        return $pdf->stream('kwitansi-multiple.pdf');
    }

    public function exportExcel(Request $request, $siswaId)
    {
        // Filter data yang ingin diexport, seperti di method show sebelumnya
        $siswa = Siswa::findOrFail($siswaId);
        $tagihans = Tagihan::where('siswa_id', $siswaId)
            ->with('jenisPembayaran', 'tahunAjaran')
            ->get();

        return Excel::download(new TagihanExport($tagihans), 'tagihan_siswa.xlsx');
    }
    public function exportAll(Request $request)
    {
        $unit = $request->get('unit');
        $kelas = $request->get('kelas');

        $query = Siswa::with(['kelas', 'unitPendidikan', 'tagihan']);

        if ($unit) {
            $query->where('unitpendidikan_id', $unit);
        }

        if ($kelas) {
            $query->where('kelas_id', $kelas);
        }

        $siswas = $query->get();

        return Excel::download(new TagihanSiswaExport($siswas), 'daftar_tagihan_siswa.xlsx');
    }
}
