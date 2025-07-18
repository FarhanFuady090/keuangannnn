<x-layout-admin>
    <x-slot name="header">
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
            <h2 class="text-2xl font-bold mb-6">Ubah Data Siswa</h2>

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

            <!-- Form Ubah Data Siswa -->
            <form action="{{ route('admin.updateSiswa', $siswas->id) }}" method="POST" class="space-y-4">
                @csrf

                <!-- Input NIS -->
                <div class="flex items-center space-x-4">
                    <label for="nis" class="text-sm font-medium text-gray-700 w-1/4">NIS</label>
                    <input type="text" id="nis" name="nis" value="{{ $siswas->nis }}" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                </div>

                <!-- Input NISN -->
                <div class="flex items-center space-x-4">
                    <label for="nisn" class="text-sm font-medium text-gray-700 w-1/4">NISN</label>
                    <input type="text" id="nisn" name="nisn" value="{{ $siswas->nisn }}" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300">
                </div>

                <!-- Input Nama Siswa -->
                <div class="flex items-center space-x-4">
                    <label for="nama" class="text-sm font-medium text-gray-700 w-1/4">Nama Siswa</label>
                    <input type="text" id="nama" name="nama" value="{{ $siswas->nama }}" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                </div>

                <!-- Dropdown Jenis Kelamin -->
                <div class="flex items-center space-x-4">
                    <label for="jenis_kelamin" class="text-sm font-medium text-gray-700 w-1/4">Jenis Kelamin</label>
                    <select id="jenis_kelamin" name="jenis_kelamin" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                        <option value="">Pilih</option>
                        <option value="Laki-laki" {{ $siswas->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ $siswas->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                <!-- Dropdown Kelas -->
                <div class="flex items-center space-x-4">
                    <label for="kelas_id" class="text-sm font-medium text-gray-700 w-1/4">Kelas</label>
                    <select id="kelas_id" name="kelas_id" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                        <option value="" disabled>Pilih</option>
                        <option value="{{ $siswas->kelas_id }}" selected>{{ $siswas->kelas->nama_kelas }}</option>
                        @foreach($kelas as $data)
                            <option value="{{ $data->id }}">{{ $data->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Agama -->
                <div class="flex items-center space-x-4">
                    <label for="agama" class="text-sm font-medium text-gray-700 w-1/4">Agama</label>
                    <select id="agama" name="agama" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                        <option value="">Pilih</option>
                        <option value="Islam" {{ $siswas->agama == 'Islam' ? 'selected' : '' }}>Islam</option>
                        <option value="Kristen" {{ $siswas->agama == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                        <option value="Katolik" {{ $siswas->agama == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                        <option value="Hindu" {{ $siswas->agama == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                        <option value="Budha" {{ $siswas->agama == 'Budha' ? 'selected' : '' }}>Budha</option>
                        <option value="Konghucu" {{ $siswas->agama == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                    </select>
                </div>

                <!-- Input Nama Orang Tua / Wali -->
                <div class="flex items-center space-x-4">
                    <label for="namaOrtu" class="text-sm font-medium text-gray-700 w-1/4">Nama Orang Tua / Wali</label>
                    <input type="text" id="namaOrtu" name="namaOrtu" value="{{ $siswas->namaOrtu }}" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                </div>

                <!-- Input Alamat Orang Tua -->
                <div class="flex items-center space-x-4">
                    <label for="alamatOrtu" class="text-sm font-medium text-gray-700 w-1/4">Alamat Orang Tua</label>
                    <input type="text" id="alamatOrtu" name="alamatOrtu" value="{{ $siswas->alamatOrtu }}" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                </div>

                <!-- Input No Telp. / WA Ortu -->
                <div class="flex items-center space-x-4">
                    <label for="noTelp" class="text-sm font-medium text-gray-700 w-1/4">No Telp. / WA Ortu</label>
                    <input type="text" id="noTelp" name="noTelp" value="{{ $siswas->noTelp }}" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300">
                </div>

                <!-- Input Email -->
                <div class="flex items-center space-x-4">
                    <label for="email" class="text-sm font-medium text-gray-700 w-1/4">Email</label>
                    <input type="email" id="email" name="email" value="{{ $siswas->email }}" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300">
                </div>

                <!-- Dropdown Unit Pendidikan Formal -->
                <div class="flex items-center space-x-4">
                    <label for="unitpendidikan_id" class="text-sm font-medium text-gray-700 w-1/4">Unit Pendidikan Formal</label>
                    <select id="unitpendidikan_id" name="unitpendidikan_id" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                        <option value="" disabled>Pilih</option>
                        @foreach($unitpendidikan as $data)
                            @if(in_array($data->id, [2, 3, 4, 5]))
                                <option value="{{ $data->id }}" {{ old('unitpendidikan_id', $siswas->unitpendidikan_id) == $data->id ? 'selected' : '' }}>
                                    {{ $data->namaUnit }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Unit Pendidikan Informal -->
                <div class="flex items-center space-x-4">
                    <label for="unitpendidikan_idInformal" class="text-sm font-medium text-gray-700 w-1/4">Unit Pendidikan Informal</label>
                    <select id="unitpendidikan_idInformal" name="unitpendidikan_idInformal" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                        <option value="" disabled>Pilih</option>
                        @foreach($unitpendidikan as $data)
                            @if(in_array($data->id, [6, 7]))
                                <option value="{{ $data->id }}" {{ old('unitpendidikan_idInformal', $siswas->unitpendidikan_idInformal) == $data->id ? 'selected' : '' }}>
                                    {{ $data->namaUnit }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Status Pondok -->
                <div class="flex items-center space-x-4">
                    <label for="unitpendidikan_idPondok" class="text-sm font-medium text-gray-700 w-1/4">Status Pondok</label>
                    <select id="unitpendidikan_idPondok" name="unitpendidikan_idPondok" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300" required>
                        <option value="" disabled>Pilih</option>
                        @foreach($unitpendidikan as $data)
                            @if(in_array($data->id, [8, 9]))
                                <option value="{{ $data->id }}" {{ old('unitpendidikan_idPondok', $siswas->unitpendidikan_idPondok) == $data->id ? 'selected' : '' }}>
                                    {{ $data->namaUnit }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <!-- Dropdown Status -->
                <div class="flex items-center space-x-4">
                    <label for="status" class="text-sm font-medium text-gray-700 w-1/4">Status</label>
                    <select id="status" name="status" class="w-3/4 px-4 py-2 border rounded-md focus:ring focus:ring-green-300">
                        <option value="Aktif" {{ $siswas->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="Non Aktif" {{ $siswas->status == 'Non Aktif' ? 'selected' : '' }}>Non Aktif</option>
                        <option value="Drop Out" {{ $siswas->status == 'Drop Out' ? 'selected' : '' }}>Drop Out</option>
                        <option value="Pindah" {{ $siswas->status == 'Pindah' ? 'selected' : '' }}>Pindah</option>
                        <option value="Lulus" {{ $siswas->status == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                    </select>
                </div>

                <!-- Tindakan Tombol -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.manage-data-siswa') }}" class="bg-blue-500 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Kembali
                    </a>
                    <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-700">
                        Perbarui
                    </button>
                </div>
            </form>
            <script>
    document.querySelector('form').addEventListener('submit', function(e) {
        const nis = document.getElementById('nis').value;

        if (nis.length < 7) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'NIS minimal terdiri dari 7 digit.',
            });
        }
    });
</script>
        </div>
    </div>
</x-layout-admin>
