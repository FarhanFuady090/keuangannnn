<x-layout-admin>
    <x-slot name="header"></x-slot>

    <div class="max-w-4xl mx-auto mt-10">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-6">Ubah Data Unit Pendidikan</h2>

            {{-- ALERT MESSAGE --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
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


            <form action="{{ route('admin.updateUnitPendidikan', $unitpendidikan->id) }}" method="POST">
                @csrf

                <div class="space-y-6">
                    <!-- Kategori -->
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium text-gray-700">Kategori:</label>
                        <select class="w-2/3 p-2 border border-gray-300 rounded-md" name="kategori">
                            <option value="-" {{ $unitpendidikan->kategori == '-' ? 'selected' : '' }}>-</option>
                            <option value="formal" {{ $unitpendidikan->kategori == 'formal' ? 'selected' : '' }}>Formal</option>
                            <option value="Informal" {{ $unitpendidikan->kategori == 'Informal' ? 'selected' : '' }}>Informal</option>
                            <option value="Pondok" {{ $unitpendidikan->kategori == 'Pondok' ? 'selected' : '' }}>Pondok</option>
                        </select>
                    </div>

                    <!-- Nama Unit Pendidikan -->
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium text-gray-700">Nama Unit Pendidikan:</label>
                        <select class="w-2/3 p-2 border border-gray-300 rounded-md" name="namaUnit">
                            <option value="-" {{ $unitpendidikan->namaUnit == '-' ? 'selected' : '' }}>-</option>
                            <option value="TK" {{ $unitpendidikan->namaUnit == 'TK' ? 'selected' : '' }}>TK</option>
                            <option value="SD" {{ $unitpendidikan->namaUnit == 'SD' ? 'selected' : '' }}>SD</option>
                            <option value="SMP" {{ $unitpendidikan->namaUnit == 'SMP' ? 'selected' : '' }}>SMP</option>
                            <option value="SMA" {{ $unitpendidikan->namaUnit == 'SMA' ? 'selected' : '' }}>SMA</option>
                            <option value="MADIN" {{ $unitpendidikan->namaUnit == 'MADIN' ? 'selected' : '' }}>MADIN</option>
                            <option value="TPQ" {{ $unitpendidikan->namaUnit == 'TPQ' ? 'selected' : '' }}>TPQ</option>
                            <option value="YA PONDOK" {{ $unitpendidikan->namaUnit == 'YA PONDOK' ? 'selected' : '' }}>YA PONDOK</option>
                            <option value="TIDAK PONDOK" {{ $unitpendidikan->namaUnit == 'TIDAK PONDOK' ? 'selected' : '' }}>TIDAK PONDOK</option>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium text-gray-700">Status:</label>
                        <select class="w-2/3 p-2 border border-gray-300 rounded-md" name="status">
                            <option value="Aktif" {{ $unitpendidikan->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Tidak Aktif" {{ $unitpendidikan->status == 'Tidak Aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>
                </div>

                <!-- Button Kembali dan Perbarui -->
                <div class="flex justify-end mt-6 space-x-4">
                    <a href="{{ route('admin.manage-unit-pendidikan') }}">
                        <button class="bg-red-500 text-white px-6 py-2 rounded-md hover:bg-red-700" type="button">Kembali</button>
                    </a>

                    <button class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-700" type="submit">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</x-layout-admin>
