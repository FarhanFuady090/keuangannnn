<x-layout-admin>
    <x-slot name="header"></x-slot>

    <div class="max-w-4xl mx-auto mt-10">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-6">Tambah Data Unit Pendidikan</h2>

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


            <form action="{{ route('admin.submitUnitPendidikan') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <!-- Kategori -->
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium text-gray-700">Kategori:</label>
                        <select class="w-2/3 p-2 border border-gray-300 rounded-md" name="kategori" required>
                            <option value="" disabled selected>Pilih</option>
                            <option value="-">-</option>
                            <option value="formal">Formal</option>
                            <option value="Informal">Informal</option>
                            <option value="Pondok">Pondok</option>
                        </select>
                    </div>

                    <!-- Nama Unit Pendidikan -->
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium text-gray-700">Nama Unit Pendidikan:</label>
                        <select class="w-2/3 p-2 border border-gray-300 rounded-md" name="namaUnit" required>
                            <option value="" disabled selected>Pilih</option>
                            <option value="-">-</option>
                            <option value="TK">TK</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="MADIN">MADIN</option>
                            <option value="TPQ">TPQ</option>
                            <option value="YA PONDOK">YA PONDOK</option>
                            <option value="TIDAK PONDOK">TIDAK PONDOK</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium text-gray-700">Status:</label>
                        <select id="status" name="status_display" class="w-2/3 p-2 border border-gray-300 rounded-md bg-gray-100" disabled>
                            <option value="Aktif" selected>Aktif</option>
                        </select>
                        <input type="hidden" name="status" value="Aktif">
                    </div>
                </div>

                <!-- Button Kembali dan Simpan -->
                <div class="flex justify-end mt-6 space-x-4">
                    <a href="{{ route('admin.manage-unit-pendidikan') }}">
                        <button class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-700" type="button">Kembali</button>
                    </a>
                    <button type="reset" class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600">Reset</button>
                    <button class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-700" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</x-layout-admin>
