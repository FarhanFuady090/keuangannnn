<?php

namespace App\Http\Controllers\Admin\auth;

use App\Http\Controllers\Controller;
use App\Models\PerpindahanKelas;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\UnitPendidikan;

class PerpindahanKelasController extends Controller
{
    public function index(Request $request)
    {
    $units = UnitPendidikan::all();
    $kelasList = Kelas::all();

    $query = Siswa::with(['kelas', 'unitpendidikan'])
        ->when($request->unit_id, fn($q) => $q->where('unitpendidikan_id', $request->unit_id))
        ->when($request->kelas_id, fn($q) => $q->where('kelas_id', $request->kelas_id))
        ->when($request->status, fn($q) => $q->where('status', $request->status))
        ->when($request->grade, fn($q) => $q->whereHas('kelas', function ($q2) use ($request) {
            $q2->where('grade', $request->grade);
        }));

    // Hitung total data
    $totalSiswa = $query->count();

    // Paginate dan tetap bawa filter di URL
    $siswas = $query->paginate($request->entries ?? 10)->withQueryString();

    // Tentukan alasan jika data kosong
    $emptyReason = null;

    if ($totalSiswa === 0) {
        if ($request->unit_id) {
            $emptyReason = 'unit';
        } elseif ($request->kelas_id) {
            $emptyReason = 'kelas';
        } elseif ($request->grade) {
            $emptyReason = 'grade';
        } elseif ($request->status) {
            $emptyReason = 'status';
        } else {
            $emptyReason = 'none'; // semua kosong
        }
    }

    return view('admin.perpindahan-kelas', compact(
        'units', 'kelasList', 'siswas', 'totalSiswa', 'emptyReason'
    ));
    }



    public function proses(Request $request)
    {
        $request->validate([
            'siswa_ids' => 'required|array',
            'kelas_tujuan' => 'required|exists:kelas,id',
            'unit_tujuan' => 'required|exists:unitpendidikan,id',
        ], [
            'siswa_ids.required' => 'Silakan pilih minimal satu siswa untuk dipindahkan.',
        ]);

        foreach ($request->siswa_ids as $id) {
            $siswa = Siswa::find($id);
            $siswa->kelas_id = $request->kelas_tujuan;
            $siswa->unitpendidikan_id = $request->unit_tujuan;
            $siswa->save();
        }

        return redirect()->route('admin.perpindahan-kelas')->with('success', 'Data Siswa telah berhasil diproses!');
    }
}