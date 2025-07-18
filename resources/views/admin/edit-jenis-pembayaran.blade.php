<x-layout-admin>
    <x-slot name="header">
        <!-- Header tambahan jika diperlukan -->
    </x-slot>

    <style>
        .w-fixed-small {
            width: 60px; /* Role */
        }

        .w-fixed-medium {
            width: 100px; /* Unit */
        }
    </style>

    <div class="max-w-4xl mx-auto mt-10">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6">Ubah Data Jenis Pemasukan</h2>

            <form action="{{ route('admin.update-jenis-pembayaran', ['id' => $jenispembayaran->id]) }}" method="POST" class="space-y-4">
                @csrf
                @method('POST')

                <!-- Input Nama Pembayaran -->
                <div class="flex items-center space-x-4">
                    <label for="nama_pembayaran" class="text-sm font-medium text-gray-700 w-1/4">Nama Pembayaran</label>
                    <input type="text" id="nama_pembayaran" name="nama_pembayaran" value="{{ old('nama_pembayaran', $jenispembayaran->nama_pembayaran) }}" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required />
                </div>

                <!-- Input Tipe Pembayaran -->
                <div class="flex items-center space-x-4">
                    <label for="type" class="text-sm font-medium text-gray-700 w-1/4">Tipe Pembayaran</label>
                    <select id="type" name="type" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                        <option value="Bulanan" {{ old('type', $jenispembayaran->type) == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                        <option value="Semester" {{ old('type', $jenispembayaran->type) == 'Semester' ? 'selected' : '' }}>Semester</option>
                        <option value="Tahunan" {{ old('type', $jenispembayaran->type) == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
                        <option value="Bebas" {{ old('type', $jenispembayaran->type) == 'Bebas' ? 'selected' : '' }}>Bebas</option>
                    </select>
                </div>

                <!-- Input Tahun Ajaran -->
                <div class="flex items-center space-x-4">
                    <label for="tahun" class="text-sm font-medium text-gray-700 w-1/4">Tahun Ajaran</label>
                    <select id="tahun" name="id_tahunajaran" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                        <option value="">Pilih Tahun Ajaran</option>
                        @foreach ($tahunAjaran as $tahun)
                        <option value="{{ $tahun->id }}" {{ old('id_tahunajaran', $jenispembayaran->id_tahunajaran) == $tahun->id ? 'selected' : '' }}>
                            {{ $tahun->tahun_ajaran }} - {{ ucfirst($tahun->semester) }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Input Nominal -->
                <div class="flex items-center space-x-4">
                    <label for="nominal_jenispembayaran" class="text-sm font-medium text-gray-700 w-1/4">Nominal</label>
                    <input type="text" id="nominal_jenispembayaran" name="nominal_jenispembayaran" value="{{ old('nominal_jenispembayaran', $jenispembayaran->nominal_jenispembayaran) }}" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required />
                </div>

                <!-- Input Status -->
                <div class="flex items-center space-x-4">
                    <label for="status" class="text-sm font-medium text-gray-700 w-1/4">Status</label>
                    <select name="status" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                        <option value="Aktif" {{ old('status', $jenispembayaran->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Non Aktif" {{ old('status', $jenispembayaran->status) == 'Non Aktif' ? 'selected' : '' }}>Non Aktif</option>
                    </select>
                </div>

                <!-- Input Unit Pendidikan -->
                <div class="flex items-center space-x-4">
                    <label for="idunitpendidikan" class="text-sm font-medium text-gray-700 w-1/4">Unit Pendidikan</label>
                    <select id="idunitpendidikan" name="idunitpendidikan" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300">
                        @foreach($unitpendidikan as $unit)
                            <option value="{{ $unit->id }}" {{ $jenispembayaran->idunitpendidikan == $unit->id ? 'selected' : '' }}>{{ $unit->namaUnit }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Tombol aksi -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.manage-jenis-pembayaran') }}" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600 inline-flex items-center">
                        Kembali
                    </a>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">Perbarui</button>
                </div>

                <!-- Menampilkan pesan error jika ada -->
                @if ($errors->any())
                    <div class="text-red-500 p-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </form>
        </div>
    </div>

</x-layout-admin>
