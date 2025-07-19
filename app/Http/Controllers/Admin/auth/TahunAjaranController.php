<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TahunAjaranController extends Controller
{
    function createTahunAjaran()
    {
        return view("admin.create-tahun-ajaran");
    }

    function submitTahunAjaran(Request $request)
    {
        // Validasi input
        $request->validate([
            'awal' => 'required|date',
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

        $awal = \Carbon\Carbon::parse($request->awal);
        $bulan = $awal->month;

        if ($bulan >= 7 && $bulan <= 12) {
            $semester = 'Ganjil';
            $akhir = $awal->copy()->endOfYear(); // 31 Desember
            $tahun_ajaran = $awal->year . '/' . ($awal->year + 1);
        } elseif ($bulan >= 1 && $bulan <= 6) {
            $semester = 'Genap';
            $akhir = $awal->copy()->month(6)->endOfMonth(); // 30 Juni
            $tahun_ajaran = ($awal->year - 1) . '/' . $awal->year;
        } else {
            return redirect()->back()->withErrors(['awal' => 'Tanggal tidak valid'])->withInput();
        }

        // Cek Tahun Awal apakah sudah ada
        if (TahunAjaran::where('awal', $request->awal)->exists()) {
            return redirect()->back()->withErrors(['awal' => 'Tahun Awal telah digunakan.'])->withInput();
        }

        $tahunajaran = new TahunAjaran();
        $tahunajaran->tahun_ajaran = $tahun_ajaran;
        $tahunajaran->awal = $awal;
        $tahunajaran->akhir = $akhir;
        $tahunajaran->semester = $semester;
        $tahunajaran->status = $request->status;

        $tahunajaran->save();

        return redirect()->route('admin.manage-tahun-ajaran')->with('success', 'Tahun ajaran berhasil ditambah.');
    }

    function editTahunAjaran($id)
    {
        $tahunajaran = TahunAjaran::find($id);
        return view('admin.edit-tahun-ajaran', compact('tahunajaran'));
    }

    function updateTahunAjaran(Request $request, $id)
    {
        $tahunajaran = TahunAjaran::findOrFail($id);

        // Validasi input
        $request->validate([
            'awal' => 'required|date',
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

            // Logika semester dan akhir
    $awal = \Carbon\Carbon::parse($request->awal);
    $bulan = $awal->month;

    if ($bulan >= 7 && $bulan <= 12) {
        $semester = 'Ganjil';
        $akhir = $awal->copy()->endOfYear(); // 31 Desember
        $tahun_ajaran = $awal->year . '/' . ($awal->year + 1);
    } elseif ($bulan >= 1 && $bulan <= 6) {
        $semester = 'Genap';
        $akhir = $awal->copy()->month(6)->endOfMonth(); // 30 Juni
        $tahun_ajaran = ($awal->year - 1) . '/' . $awal->year;
    } else {
        return redirect()->back()->withErrors(['awal' => 'Tanggal tidak valid'])->withInput();
    }

            // Cek Tahun Awal
            if (TahunAjaran::where('awal', $request->awal)->where('id', '!=', $id)->exists()) {
                return redirect()->back()->withErrors(['awal' => 'Tahun Awal telah digunakan.'])->withInput();
            }

        $tahunajaran->tahun_ajaran = $tahun_ajaran;
        $tahunajaran->awal = $awal;
        $tahunajaran->akhir = $akhir;
        $tahunajaran->semester = $semester;
        $tahunajaran->status = $request->status;



        $tahunajaran->update();

        return redirect()->route('admin.manage-tahun-ajaran')->with('success', 'Tahun ajaran berhasil diperbarui.');
    }

    public function index(Request $request)
    {
        $query = TahunAjaran::query();

        // Ambil filter status dari request (default: "Semua")
        $status = $request->input('status', 'Semua');
        if ($status !== 'Semua') {
            $query->where('status', $status);
        }

        // Ambil filter pencarian dari request
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where('tahun_ajaran', 'LIKE', "%$search%");
        }

        // Ambil jumlah entri dari request (default: 10)
        $perPage = $request->input('entries', 10);

        // Ambil data sesuai filter yang dipilih dengan pagination
        $tahunajaran = $query->paginate($perPage);

        // Kirim filter yang dipilih ke view agar tetap tersimpan
        return view('admin.manage-tahun-ajaran', compact('tahunajaran', 'perPage', 'status'));
    }
}