<?php

namespace App\Http\Controllers\Admin\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Kas;

class KasController extends Controller
{
    public function index(Request $request)
{
    $query = Kas::query();

    if ($request->filled('kategori')) {
        $query->where('kategori', $request->kategori);
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    $entries = $request->get('entries', 10);
$kas = $query->paginate($entries)->appends($request->query());
    return view('admin.manage-data-kas', compact('kas'));
}


    public function createKas()
    {
        return view('admin.create-data-kas');
    }

    public function submitKas(Request $request)
    {
        $request->validate([
            'namaKas' => 'required|string|max:255',
            'kategori' => 'required|in:Pemasukan,Pengeluaran',
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

        $kas = new Kas();
        $kas->namaKas = $request->namaKas;
        $kas->kategori = $request->kategori;
        $kas->status = $request->status;
        // Cek Nama Kas apakah sudah ada
if (Kas::where('namaKas', $request->namaKas)->exists()) {
    return redirect()->back()->withErrors(['namaKas' => 'Nama Kas telah digunakan.'])->withInput();
}
        $kas->save();

        return redirect()->route('admin.manage-data-kas')->with('success', 'Data Kas Berhasil Ditambahkan.');
    }

    public function editKas($id)
    {
        $kas = Kas::findOrFail($id);
        return view('admin.edit-data-kas', compact('kas'));
    }

    public function updateKas(Request $request, $id)
    {
        $request->validate([
            'namaKas' => 'required|string|max:255',
            'kategori' => 'required|in:Pemasukan,Pengeluaran',
            'status' => 'required|in:Aktif,Non Aktif',
        ]);

        $kas = Kas::findOrFail($id);
        $kas->namaKas = $request->namaKas;
        $kas->kategori = $request->kategori;
        $kas->status = $request->status;
        // Cek Nama Kas (tidak boleh sama kecuali milik ID yang sedang diedit)
if (Kas::where('namaKas', $request->namaKas)->where('id', '!=', $id)->exists()) {
    return redirect()->back()->withErrors(['namaKas' => 'Nama Kas telah digunakan.'])->withInput();
}
        $kas->update();

        return redirect()->route('admin.manage-data-kas')->with('success', 'Data Kas Berhasil Diperbarui.');
    }
}
