<x-layout-admin>
    <x-slot name="header">
        <!-- Kosong atau tambahkan judul halaman di sini -->
    </x-slot>

    <div class="max-w-4xl mx-auto mt-10">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6">Tambah Data Kas</h2>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ $errors->first() }}',
        });
    </script>
@endif


            <form action="{{ route('admin.submitKas') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Nama Kas -->
                <div class="flex items-center space-x-4">
                    <label for="namaKas" class="text-sm font-medium text-gray-700 w-1/4">Nama Kas</label>
                    <input type="text" id="namaKas" name="namaKas" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                </div>

                <!-- Kategori -->
                <div class="flex items-center space-x-4">
                    <label for="kategori" class="text-sm font-medium text-gray-700 w-1/4">Kategori</label>
                    <select id="kategori" name="kategori" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                        <option value="" disabled selected>Pilih</option>
                        <option value="Pemasukan">Pemasukan</option>
                        <option value="Pengeluaran">Pengeluaran</option>
                    </select>
                </div>

                <!-- Status -->
                <div class="flex items-center space-x-4">
                    <label for="status" class="text-sm font-medium text-gray-700 w-1/4">Status</label>
                    <select id="status" name="status_display" class="w-3/4 px-4 py-2 border rounded-md bg-gray-100" disabled>
                        <option value="Aktif" selected>Aktif</option>
                    </select>
                    <input type="hidden" name="status" value="Aktif">
                </div>

                <!-- Tombol -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.manage-data-kas') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 inline-flex items-center">
                        Kembali
                    </a>
                    <button type="reset" class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600">Reset</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-layout-admin>
