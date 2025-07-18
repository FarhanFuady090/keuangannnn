<x-layout-admin>
    <x-slot name="header">
        <!-- Tambahkan konten header jika diperlukan -->
    </x-slot>

    <div class="max-w-4xl mx-auto mt-10">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6">Tambah Data Jenis Pemasukan</h2>

            <!-- Menampilkan Notifikasi Error -->
            @if ($errors->any())
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.create-jenis-pembayaran-submit') }}" class="space-y-4">
                @csrf

                <!-- Input Nama Pembayaran -->
                <div class="flex items-center space-x-4">
                    <label for="nama_pembayaran" class="text-sm font-medium text-gray-700 w-1/4">Nama Pembayaran</label>
                    <input class="w-3/4 p-2 border rounded-md focus:ring focus:ring-green-300" id="nama_pembayaran" name="nama_pembayaran" type="text" value="{{ old('nama_pembayaran') }}" required />
                </div>

                <!-- Input Tipe Pembayaran -->
                <div class="flex items-center space-x-4">
                    <label for="tipe_pembayaran" class="text-sm font-medium text-gray-700 w-1/4">Tipe Pembayaran</label>
                    <select class="w-3/4 p-2 border rounded-md focus:ring focus:ring-green-300" id="type" name="type" required>
                        <option value="">Pilih Tipe Pembayaran</option>
                        <option value="Bulanan" {{ old('type') == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="Semester" {{ old('type') == 'Semester' ? 'selected' : '' }}>Semester</option>
                        <option value="Bebas" {{ old('type') == 'Bebas' ? 'selected' : '' }}>Bebas</option>
                    </select>
                </div>

                <!-- Input Tahun Ajaran -->
                <div class="flex items-center space-x-4">
                    <label for="tahun" class="text-sm font-medium text-gray-700 w-1/4">Tahun Ajaran</label>
                    <select class="w-3/4 p-2 border rounded-md focus:ring focus:ring-green-300" id="tahun" name="id_tahunajaran" required>
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach ($tahunAjaran as $tahun)
                            <option value="{{ $tahun->id }}" {{ old('id_tahunajaran') == $tahun->id ? 'selected' : '' }}>
                                {{ $tahun->tahun_ajaran }} - {{ ucfirst($tahun->semester) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Nominal -->
                <div class="flex items-center space-x-4">
                    <label for="nominal_jenispembayaran" class="text-sm font-medium text-gray-700 w-1/4">Nominal</label>
                    <input class="w-3/4 p-2 border rounded-md focus:ring focus:ring-green-300" id="nominal_jenispembayaran" name="nominal_jenispembayaran" type="text" value="{{ old('nominal_jenispembayaran') }}" required />
                </div>

                <!-- Input Status -->
                <div class="flex items-center space-x-4">
                    <label class="w-1/4">Status</label>
                    <select class="w-3/4 p-2 border rounded-md bg-gray-100" name="status_display" disabled>
                        <option value="Aktif" selected>Aktif</option>
                    </select>
                    <input type="hidden" name="status" value="Aktif">
                </div>

                <!-- Input Unit Pendidikan -->
                <div class="flex items-center space-x-4">
                    <label for="idunitpendidikan" class="text-sm font-medium text-gray-700 w-1/4">Unit Pendidikan</label>
                    <select class="w-3/4 p-2 border rounded-md focus:ring focus:ring-green-300" id="idunitpendidikan" name="idunitpendidikan" required>
                    @foreach ($unitpendidikan as $unit)
                        <option value="{{ $unit->id }}" {{ old('idunitpendidikan') == $unit->id ? 'selected' : '' }}>{{ $unit->namaUnit }}</option>
                    @endforeach
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end mt-4 space-x-4">
                    <a href="{{ route('admin.manage-jenis-pembayaran') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 inline-flex items-center">
                        Kembali
                    </a>
                    <button type="reset" class="bg-orange-500 text-white px-4 py-2 rounded-md hover:bg-orange-600">Reset</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript jika diperlukan -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handle Form Submit or Other JS logic if needed
        });
    </script>
</x-layout-admin>
