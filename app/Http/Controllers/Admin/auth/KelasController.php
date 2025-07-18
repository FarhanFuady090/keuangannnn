<?php

namespace App\Http\Controllers\Admin\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kelas;  // Perbaiki ini untuk memanggil model Kelas yang benar
use App\Models\UnitPendidikan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class KelasController extends Controller
{
    public function index(Request $request)
{
    $query = Kelas::with('unitpendidikan');

    // Filtering
    if ($request->filled('unit')) {
        $query->whereHas('unitpendidikan', function ($q) use ($request) {
            $q->where('namaUnit', $request->unit);
        });
    }

    if ($request->filled('grade')) {
        $query->where('grade', $request->grade);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('search')) {
        $query->where('nama_kelas', 'like', '%' . $request->search . '%');
    }

    // Ambil jumlah per halaman dari request, default ke 10
    $perPage = $request->input('show', 10);

    // Paginate dengan nilai yang dipilih user
    $kelas = $query->paginate($perPage)->withQueryString();

    return view('admin.manage-kelas', compact('kelas'));
}

    public function create()
    {
        $unitpendidikan = UnitPendidikan::all();
        return view('admin.create-kelas', compact('unitpendidikan'));
    }

    public function editt($id)
    {
        $unitpendidikan = UnitPendidikan::all();
        $kelas = Kelas::with('unitpendidikan')->find($id);
        return view('admin.edit-kelas', compact('kelas', 'unitpendidikan'));
    }

    public function updatee(Request $request, $id)
    {
        $kelas = kelas::findorFail($id);
        // Cek Tahun Ajaran (tidak boleh sama kecuali milik ID yang sedang diedit)
if (Kelas::where('nama_kelas', $request->nama_kelas)->where('id', '!=', $id)->exists()) {
    return redirect()->back()->withErrors(['nama_kelas' => 'Data Nama Kelas telah digunakan.'])->withInput();
}

// Cek Tahun Awal
if (Kelas::where('keterangan', $request->keterangan)->where('id', '!=', $id)->exists()) {
    return redirect()->back()->withErrors(['keterangan' => 'Data Nama Keterangan telah digunakan.'])->withInput();
}

        $kelas->update($request->all());
        return redirect()->route('admin.manage-kelas', $id)->with('success', 'Data Kelas berhasil diperbarui');
    }

    public function submitt(Request $request)
    {
        \Log::info($request->all());

        // Validate the request data
        $validated = $request->validate([
            'unitpendidikan_id' => 'required',
            'nama_kelas' => 'required|string|max:20',
            'status' => 'required|in:AKTIF,TIDAK AKTIF',
            'grade' => 'required|in:-,A,B,C,D,E,F',
            'keterangan' => 'required|string|max:500',
        ]);

                // Cek Nama Kelas apakah sudah ada
if (Kelas::where('nama_kelas', $request->nama_kelas)->exists()) {
    return redirect()->back()->withErrors(['nama_kelas' => 'Data Nama Kelas telah digunakan.'])->withInput();
}

// Cek Keterangan apakah sudah ada
if (Kelas::where('keterangan', $request->keterangan)->exists()) {
    return redirect()->back()->withErrors(['keterangan' => 'Data Keterangan telah digunakan.'])->withInput();
}

        // Create a new record in the 'kelas' table
        $kelas = Kelas::create([  
            'unitpendidikan_id' => $validated['unitpendidikan_id'],
            'nama_kelas' => $validated['nama_kelas'],
            'status' => $validated['status'],
            'grade' => $validated['grade'],
            'keterangan' => $validated['keterangan'],
            
        ]);

        return redirect()->route('admin.manage-kelas', compact('kelas'))->with('success', 'Data Kelas berhasil ditambahkan.');
    }
}