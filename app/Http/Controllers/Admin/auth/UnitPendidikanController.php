<?php

namespace App\Http\Controllers\Admin\auth;

use App\Http\Controllers\Controller;
use App\Models\UnitPendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UnitPendidikanController extends Controller
{
    public function index(Request $request)
{
    $query = UnitPendidikan::query();

    // Filter kategori
    if ($request->filled('kategori')) {
        $query->where('kategori', $request->kategori);
    }

    // Filter namaUnit
    if ($request->filled('namaUnit')) {
        $query->where('namaUnit', $request->namaUnit);
    }

    // Filter status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $perPage = $request->input('entries', 10); // default 10 jika tidak dipilih

    $unitpendidikan = $query->paginate($perPage);

    // Untuk pilihan select-nya tetap muncul
    $kategoriList = UnitPendidikan::select('kategori')->distinct()->pluck('kategori');
    $namaUnitList = UnitPendidikan::select('namaUnit')->distinct()->pluck('namaUnit');

    return view('admin.manage-unit-pendidikan', compact('unitpendidikan', 'kategoriList', 'namaUnitList'));
}

    function createUnitPendidikan () {
        return view("admin.create-manage-unit-pendidikan");
    }
    
    function submitUnitPendidikan(Request $request)
{
    // Simpan data jika belum ada
    $unitpendidikan = new UnitPendidikan();
    $unitpendidikan->kategori = $request->kategori;
    $unitpendidikan->namaUnit = $request->namaUnit;
    $unitpendidikan->status = $request->status;
// Cek Nama Unit Pendidikan apakah sudah ada
if (UnitPendidikan::where('namaUnit', $request->namaUnit)->exists()) {
    return redirect()->back()->withErrors(['namaUnit' => 'Nama Unit Pendidikan telah digunakan.'])->withInput();
}
    $unitpendidikan->save();

    return redirect()->route('admin.manage-unit-pendidikan')
        ->with('success', 'Data Unit Pendidikan berhasil ditambahkan.');
}

    function editUnitPendidikan ($id) {
        $unitpendidikan = UnitPendidikan::find($id);
        return view('admin.edit-manage-unit-pendidikan', compact('unitpendidikan'));
    }

    function updateUnitPendidikan(Request $request, $id)
{
    $unitpendidikan = UnitPendidikan::find($id);
    $unitpendidikan->kategori = $request->kategori;
    $unitpendidikan->namaUnit = $request->namaUnit;
    $unitpendidikan->status = $request->status;
// Cek Nama Unit Pendidikan (tidak boleh sama kecuali milik ID yang sedang diedit)
if (UnitPendidikan::where('namaUnit', $request->namaUnit)->where('id', '!=', $id)->exists()) {
    return redirect()->back()->withErrors(['namaUnit' => 'Nama Unit telah digunakan.'])->withInput();
}
    $unitpendidikan->update();

    return redirect()->route('admin.manage-unit-pendidikan')
        ->with('success', 'Data Unit Pendidikan berhasil diperbarui.');
}

    function deleteUnitPendidikan ($id) {
        $unitpendidikan = UnitPendidikan::find($id);
        $unitpendidikan->delete();
        return redirect()->route('admin.manage-unit-pendidikan')->with('success', 'Data Unit Pendidikan berhasil dihapus.');
    }
}