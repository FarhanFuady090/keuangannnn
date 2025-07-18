<x-layout-admin>
    <x-slot name="header">
        <!-- Header content can be added here if needed -->
    </x-slot>

    <div class="max-w-4xl mx-auto mt-10 bg-white p-6 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold mb-6">Edit Data Kelas</h2>

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

        <form action="{{ route('admin.updatee', $kelas->id) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <!-- ID Kelas -->
                <div class="mb-4">
                    <label for="id_kelas" class="block text-gray-700 mb-2">ID Kelas</label>
                    <input id="id" name="id_kelas" type="text" class="w-full px-3 py-2 border rounded bg-gray-200" placeholder="ID Kelas" value="{{ $kelas->id }}" disabled>
                </div>

                <!-- Unit Pendidikan -->
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2" for="unitpendidikan_id">Unit Pendidikan</label>
                    <select id="unitpendidikan_id" name="unitpendidikan_id" class="w-full px-4 py-2 border rounded-md focus:ring focus:ring-green-300">
                        <option value="" disabled>Pilih</option>
                        <option value="{{ $kelas->unitpendidikan_id }}" selected>{{ $kelas->unitpendidikan->namaUnit }}</option>
                        @foreach($unitpendidikan as $itemm)
                            <option value="{{ $itemm->id }}">{{ $itemm->namaUnit }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Kelas -->
                <div class="mb-4">
                    <label for="nama_kelas" class="block text-gray-700 mb-2">Nama Kelas</label>
                    <input id="nama_kelas" name="nama_kelas" type="text" class="w-full px-3 py-2 border rounded" placeholder="Nama Kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}">
                </div>

                <!-- Grade -->
                <div class="mb-4">
                    <label for="grade" class="block text-gray-700 mb-2">Grade</label>
                    <select id="grade" name="grade" class="w-full px-4 py-2 border rounded-md">
                        <option value="-" {{ old('grade', $kelas->grade) == '-' ? 'selected' : '' }}>-</option>
                        <option value="A" {{ old('grade', $kelas->grade) == 'A' ? 'selected' : '' }}>A</option>
                        <option value="B" {{ old('grade', $kelas->grade) == 'B' ? 'selected' : '' }}>B</option>
                        <option value="C" {{ old('grade', $kelas->grade) == 'C' ? 'selected' : '' }}>C</option>
                        <option value="D" {{ old('grade', $kelas->grade) == 'D' ? 'selected' : '' }}>D</option>
                        <option value="E" {{ old('grade', $kelas->grade) == 'E' ? 'selected' : '' }}>E</option>
                        <option value="F" {{ old('grade', $kelas->grade) == 'F' ? 'selected' : '' }}>F</option>
                    </select>
                </div>

                <!-- Keterangan -->
                <div class="mb-4">
                    <label for="keterangan" class="block text-gray-700 mb-2">Keterangan</label>
                    <input id="keterangan" name="keterangan" type="text" class="w-full px-3 py-2 border rounded" placeholder="Keterangan" value="{{ old('keterangan', $kelas->keterangan) }}">
                </div>

                <!-- Status -->
                <div class="mb-4">
                    <label for="status" class="block text-gray-700 mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-3 py-2 border rounded">
                        <option value="AKTIF" {{ old('status', $kelas->status) == 'AKTIF' ? 'selected' : '' }}>AKTIF</option>
                        <option value="TIDAK AKTIF" {{ old('status', $kelas->status) == 'TIDAK AKTIF' ? 'selected' : '' }}>TIDAK AKTIF</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-4 mt-6">
                <a href="{{ route('admin.manage-kelas') }}" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Kembali</a>
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Perbarui</button>
            </div>
        </form>
    </div>
</x-layout-admin>
