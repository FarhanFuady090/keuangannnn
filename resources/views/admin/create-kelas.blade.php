<x-layout-admin>
    <x-slot name="header">
        <!-- Header content can be added here if needed -->
    </x-slot>

    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold mb-6">Tambah Data Kelas</h2>

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

        <form action="{{ route('admin.submitt') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- ID Kelas -->
                <div class="mb-4">
                    <label for="id_kelas" class="block text-gray-700 mb-2">ID Kelas</label>
                    <input id="id_kelas" name="id_kelas" type="text" class="w-full px-3 py-2 border rounded bg-gray-200" placeholder="ID Kelas" disabled>
                </div>

                <!-- Unit Pendidikan -->
                <div class="mb-4">
                    <label for="unitpendidikan_id" class="block text-gray-700 mb-2">Unit Pendidikan</label>
                    <select id="unitpendidikan_id" name="unitpendidikan_id" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                        <option value="" disabled selected>Pilih Unit Pendidikan</option>
                        @foreach($unitpendidikan as $item)
                            <option value="{{ $item->id }}">{{ $item->namaUnit }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Kelas -->
                <div class="mb-4">
                    <label for="nama_kelas" class="block text-gray-700 mb-2">Nama Kelas</label>
                    <input id="nama_kelas" name="nama_kelas" type="text" class="w-full px-3 py-2 border rounded" placeholder="Nama Kelas" required>
                </div>

                <!-- Grade -->
                <div class="mb-4">
                    <label for="grade" class="block text-gray-700 mb-2">Grade</label>
                    <select id="grade" name="grade" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                        <option value="" disabled selected>Pilih Grade</option>
                        <option value="-">-</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="C">C</option>
                        <option value="D">D</option>
                        <option value="E">E</option>
                        <option value="F">F</option>
                    </select>
                </div>

                <!-- Keterangan -->
                <div class="mb-4">
                    <label for="keterangan" class="block text-gray-700 mb-2">Keterangan</label>
                    <input id="keterangan" name="keterangan" type="text" class="w-full px-3 py-2 border rounded" placeholder="Keterangan" required>
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label for="status" class="block text-gray-700 mb-2">Status</label>
                    <select id="status" name="status_display" class="w-full px-4 py-2 border rounded-md bg-gray-100" disabled>
                        <option value="AKTIF" selected>Aktif</option>
                    </select>
                    <input type="hidden" name="status" value="AKTIF">
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('admin.manage-kelas') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Kembali</a>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Simpan</button>
            </div>
        </form>
    </div>
</x-layout-admin>
