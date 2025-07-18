<?php

namespace App\Http\Controllers\Admin\auth;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\UnitPendidikan;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Validators\ValidationException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;


class SiswaController extends Controller
{

    function createSiswa () {
        $kelas = Kelas::all();
        $unitpendidikan = UnitPendidikan::all();
        return view("admin.create-data-siswa", compact('kelas', 'unitpendidikan'));
    }

    function submitSiswa (Request $request)
    {
        $request->validate([
            'nis' => 'required|numeric|digits_between:7,20',
            'nisn' => 'required|numeric',
            'nama' => 'required|string',
            // tambahkan validasi lain jika perlu
        ], [
            'nis.numeric' => 'NIS harus berupa angka.',
            'nis.digits_between' => 'NIS minimal terdiri dari 7 digit.',
            'nisn.numeric' => 'NISN harus berupa angka.',
        ]);

        $siswas = new Siswa();
        $siswas->nis = $request->nis;
        $siswas->nisn = $request->nisn;
        $siswas->nama = $request->nama;
        $siswas->jenis_kelamin = $request->jenis_kelamin;
        $siswas->kelas_id = $request->kelas_id;
        $siswas->agama = $request->agama;
        $siswas->namaOrtu = $request->namaOrtu;
        $siswas->alamatOrtu = $request->alamatOrtu;
        $siswas->noTelp = $request->noTelp;
        $siswas->email = $request->email;
        $siswas->unitpendidikan_id = $request->unitpendidikan_id;
        $siswas->unitpendidikan_idInformal = $request->unitpendidikan_idInformal;
        $siswas->unitpendidikan_idPondok = $request->unitpendidikan_idPondok;
        // $siswas->unitPendidikanInformal = $request->unitPendidikanInformal;
        // $siswas->statusPondok = $request->statusPondok;
        $siswas->status = $request->status;
        // Cek NIS apakah sudah ada
if (Siswa::where('nis', $request->nis)->exists()) {
    return redirect()->back()->withErrors(['nis' => 'Data NIS telah digunakan.'])->withInput();
}

// Cek NISN apakah sudah ada
if (Siswa::where('nisn', $request->nisn)->exists()) {
    return redirect()->back()->withErrors(['nisn' => 'Data NISN telah digunakan.'])->withInput();
}
        $siswas->save();

        return redirect()->route('admin.manage-data-siswa')->with('success', 'Data Siswa berhasil ditambahkan.');
    }

    function editSiswa ($id) {
        $kelas = Kelas::all();
        $unitpendidikan = UnitPendidikan::all();
        $siswas = Siswa::with('kelas', 'unitpendidikan')->find($id);
        return view('admin.edit-data-siswa', compact('siswas', 'kelas', 'unitpendidikan'));
    }

    function updateSiswa (Request $request, $id) {
        $request->validate([
            'nis' => 'required|numeric|digits_between:7,20',
            'nisn' => 'required|numeric',
            'nama' => 'required|string',
            // tambahkan validasi lain jika perlu
        ], [
            'nis.numeric' => 'NIS harus berupa angka.',
            'nis.digits_between' => 'NIS minimal terdiri dari 7 digit.',
            'nisn.numeric' => 'NISN harus berupa angka.',
        ]);

        $siswas = Siswa::find($id);
        $siswas->nis = $request->nis;
        $siswas->nisn = $request->nisn;
        $siswas->nama = $request->nama;
        $siswas->jenis_kelamin = $request->jenis_kelamin;
        $siswas->kelas_id = $request->kelas_id;
        $siswas->agama = $request->agama;
        $siswas->namaOrtu = $request->namaOrtu;
        $siswas->alamatOrtu = $request->alamatOrtu;
        $siswas->noTelp = $request->noTelp;
        $siswas->email = $request->email;
        $siswas->unitpendidikan_id = $request->unitpendidikan_id;
        $siswas->unitpendidikan_idInformal = $request->unitpendidikan_idInformal;
        $siswas->unitpendidikan_idPondok = $request->unitpendidikan_idPondok;
        // $siswas->unitPendidikanInformal = $request->unitPendidikanInformal;
        // $siswas->statusPondok = $request->statusPondok;
        $siswas->status = $request->status;
        // Cek NIS (tidak boleh sama kecuali milik ID yang sedang diedit)
if (Siswa::where('nis', $request->nis)->where('id', '!=', $id)->exists()) {
    return redirect()->back()->withErrors(['nis' => 'Data NIS telah digunakan.'])->withInput();
}

// Cek NISN
if (Siswa::where('nisn', $request->nisn)->where('id', '!=', $id)->exists()) {
    return redirect()->back()->withErrors(['nisn' => 'Data NISN telah digunakan.'])->withInput();
}
        $siswas->update();

        return redirect()->route('admin.manage-data-siswa')->with('success', 'Data Siswa berhasil diperbarui.');
    }

    public function detailSiswa($id)
    {
        // Ambil data siswa berdasarkan ID
        $siswas = Siswa::findorFail($id);
        $unitpendidikan = UnitPendidikan::all();

        // Return view ke halaman detail data siswa
        return view('admin.detail-data-siswa', compact('siswas', 'unitpendidikan'));
    }

    public function importData(Request $request)
    {
        try {
            Excel::import($import = new SiswaImport, $request->file('file'));
    
            if ($import->failures()->isNotEmpty()) {
                return back()->with([
                    'error' => 'Beberapa data gagal diimpor.',
                    'failures' => $import->failures()
                ]);
            }
    
            return redirect()->route('admin.manage-data-siswa')->with('success', 'Data Siswa berhasil Diimpor.');
    
        } catch (\Throwable $th) {
            return back()->with('error', 'Terjadi kesalahan saat mengimpor: ' . $th->getMessage());
        }
    }
    

    public function index(Request $request)
{
    $query = Siswa::with('kelas', 'unitpendidikan');

    // Filter berdasarkan search
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('nama', 'like', "%{$search}%")
              ->orWhere('nis', 'like', "%{$search}%");
        });
    }

    // Filter berdasarkan status
    if ($request->filled('status') && $request->status !== 'Pilih Status') {
        $query->where('status', $request->status);
    }

    // Filter berdasarkan kelas
    if ($request->filled('kelas_id')) {
        $query->where('kelas_id', $request->kelas_id);
    }

    // Ambil jumlah entri yang ingin ditampilkan
    $perPage = $request->filled('entries') ? (int)$request->entries : 10;

    // Pagination dengan query string agar filter tidak hilang saat pindah halaman
    $siswas = $query->paginate($perPage)->withQueryString();

    // Ambil data kelas dan unit pendidikan
    $kelas = Kelas::all();
    $unitpendidikan = UnitPendidikan::all();

    return view('admin.manage-data-siswa', compact('siswas', 'kelas', 'unitpendidikan'));
}

}