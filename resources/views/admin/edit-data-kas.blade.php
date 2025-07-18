<x-layout-admin>
    <x-slot name="header">
    </x-slot>

    <div class="max-w-4xl mx-auto mt-10">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6">Ubah Data Kas</h2>

                    @if(session('success'))
                        <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300">
                            <strong>Sukses!</strong> {{ session('success') }}
                        </div>
                    @endif

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

            <form action="{{ route('admin.updateKas', $kas->id) }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input Nama Kas -->
                <div class="flex items-center space-x-4">
                    <label for="namaKas" class="text-sm font-medium text-gray-700 w-1/4">Nama Kas</label>
                    <input type="text" id="namaKas" name="namaKas" value="{{ $kas->namaKas }}" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                </div>

                <!-- Dropdown Kategori -->
                <div class="flex items-center space-x-4">
                    <label for="kategori" class="text-sm font-medium text-gray-700 w-1/4">Kategori</label>
                    <select id="kategori" name="kategori" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                        <option value="Pemasukan" {{ $kas->kategori == 'Pemasukan' ? 'selected' : '' }}>Pemasukan</option>
                        <option value="Pengeluaran" {{ $kas->kategori == 'Pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
                    </select>
                </div>

                <!-- Dropdown Status -->
                <div class="flex items-center space-x-4">
                    <label for="status" class="text-sm font-medium text-gray-700 w-1/4">Status</label>
                    <select id="status" name="status" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                        <option value="Aktif" {{ $kas->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Non Aktif" {{ $kas->status == 'Non Aktif' ? 'selected' : '' }}>Non Aktif</option>
                    </select>
                </div>

                <!-- Tombol -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.manage-data-kas') }}" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Kembali
                    </a>
                    <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-700">
                        Perbarui
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout-admin>
