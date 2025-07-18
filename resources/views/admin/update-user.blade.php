<x-layout-admin>
    <x-slot name="header"></x-slot>

    <style>
        .w-fixed-small {
            width: 60px; /* Role */
        }
        .w-fixed-medium {
            width: 100px; /* Unit */
        }
    </style>

    <script>
        function toggleUnitPendidikan() {
            const peranUser = document.getElementById("peran_user").value;
            const unitPendidikanField = document.getElementById("unitPendidikanContainer");

            // Menampilkan atau menyembunyikan Unit Pendidikan berdasarkan peran user
            if (peranUser === "tuunit") {
                unitPendidikanField.style.display = "flex";
            } else {
                unitPendidikanField.style.display = "none";
                // Reset nilai Unit Pendidikan jika peran bukan 'tuunit'
                document.getElementById("nama_unit").value = "";
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            // Atur visibilitas awal berdasarkan nilai saat ini
            toggleUnitPendidikan();

            // Tambahkan event listener untuk perubahan pada dropdown peran user
            document.getElementById("peran_user").addEventListener("change", toggleUnitPendidikan);
        });
    </script>

    <div class="max-w-4xl mx-auto mt-10">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-bold mb-6">Ubah Data Akun User</h2>

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

            <form action="{{ route('admin.updateuserrr', $users->id) }}" method="POST" class="space-y-4">
                @csrf
                @method('POST')

                <input type="hidden" name="id" value="{{ $users->id }}">

                <div class="flex items-center space-x-4">
                    <label for="nama_user" class="text-sm font-medium text-gray-700 w-1/4">Nama User</label>
                    <input type="text" id="nama_user" name="nama_user" value="{{ old('name', $users->name) }}"
                        class="w-3/4 px-4 py-2 border rounded-md"required>
                </div>

                <div class="flex items-center space-x-4">
                    <label for="email" class="text-sm font-medium text-gray-700 w-1/4">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $users->email) }}"
                        class="w-3/4 px-4 py-2 border rounded-md" required>
                </div>

                <div class="flex items-center space-x-4">
                    <label for="no_telp" class="text-sm font-medium text-gray-700 w-1/4">No. Telp/WA</label>
                    <input type="text" id="no_telp" name="no_telp" pattern="\d+" inputmode="numeric" minlength="10" value="{{ old('no_telp', $users->no_telp) }}" class="w-3/4 px-4 py-2 border rounded-md" required>
                </div>

                <div class="flex items-center space-x-4">
                    <label for="username" class="text-sm font-medium text-gray-700 w-1/4">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username', $users->username) }}"
                        class="w-3/4 px-4 py-2 border rounded-md" required>
                </div>

                <div class="flex items-center space-x-4">
                    <label for="password" class="text-sm font-medium text-gray-700 w-1/4">Password</label>
                    <input type="password" id="password" name="password" value="{{ old('password', $users->password) }} "
                        class="w-3/4 px-4 py-2 border rounded-md">
                </div>


           <div class="flex items-center space-x-4">
    <label for="status" class="text-sm font-medium text-gray-700 w-1/4">Status</label>
    <select id="status" name="status" class="w-3/4 px-4 py-2 border rounded-md" required>
        <option value="">Pilih Status</option>
        <option value="Aktif" {{ (old('status', $users->status) == 'Aktif') ? 'selected' : '' }}>Aktif</option>
        <option value="Non Aktif" {{ (old('status', $users->status) == 'Non Aktif') ? 'selected' : '' }}>Non Aktif</option>
    </select>
</div>



                <div class="flex items-center space-x-4">
                    <label for="peran_user" class="text-sm font-medium text-gray-900 w-1/4">Peran User</label>
                    <span class="w-3/4 px-4 py-2 border rounded-md bg-gray-200 text-gray-500">{{ old('peran_user', $users->role) }}</span>
                        <!-- Kirim value tersembunyi agar controller menerima input -->
                        <input type="hidden" name="peran_user" value="{{ old('peran_user', $users->role) }}">
                </div>

                <div class="flex items-center space-x-4" id="unitPendidikanContainer" style="display: {{ old('role', $users->role) == 'tuunit' ? 'flex' : 'none' }};">
                    <label for="namaUnit" class="text-sm font-medium text-gray-700 w-1/4">Unit Pendidikan</label>
                    <select id="namaUnit" name="namaUnit" class="w-3/4 px-4 py-2 border rounded-md">
                        <option value="">Pilih Unit</option>
                        <option value="TK" {{ old('namaUnit', $users->namaUnit) == 'TK' ? 'selected' : '' }}>TK</option>
                        <option value="SD" {{ old('namaUnit', $users->namaUnit) == 'SD' ? 'selected' : '' }}>SD</option>
                        <option value="SMP" {{ old('namaUnit', $users->namaUnit) == 'SMP' ? 'selected' : '' }}>SMP</option>
                        <option value="SMA" {{ old('namaUnit', $users->namaUnit) == 'SMA' ? 'selected' : '' }}>SMA</option>
                        <option value="TPQ" {{ old('namaUnit', $users->namaUnit) == 'TPQ' ? 'selected' : '' }}>TPQ</option>
                        <option value="YA PONDOK" {{ old('namaUnit', $users->namaUnit) == 'PONDOK' ? 'selected' : '' }}>PONDOK</option>
                        <option value="MADIN" {{ old('namaUnit', $users->namaUnit) == 'MADIN' ? 'selected' : '' }}>MADIN</option>
                    </select>
                </div>

                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.manage-user') }}" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-650 inline-flex items-center">
                        Kembali
                    </a>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-700">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
</x-layout-admin>