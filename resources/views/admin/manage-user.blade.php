<x-layout-admin>
    <x-slot name="header"></x-slot>

    <!-- Konten halaman -->
    <html lang="en">
        <head>
            <meta charset="utf-8" />
            <meta content="width=device-width, initial-scale=1.0" name="viewport" />
            <title>Manajemen Data User</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
        </head>
        <body class="bg-gray-100">
            <div class="flex h-screen">
                <!-- Main Content -->
                <div class="flex-1 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="text-2xl font-bold">Manajemen Data User</div>
                    </div>
                    <div class="bg-white p-4 rounded shadow">
                    @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
        <strong>Sukses!</strong> {{ session('success') }}
    </div>
@endif
                        <div class="flex justify-between items-center mb-4">
                            <!-- Filter and Add User Section -->
                            <div class="flex items-center space-x-4">
                            <form action="{{ route('admin.manage-user') }}" method="GET">
    <div class="flex items-center space-x-4">
        <select id="role" name="role" class="block w-full p-3 border border-gray-300 rounded text-sm" onchange="this.form.submit()">
            <option value="all" {{ $role === 'all' ? 'selected' : '' }}>Pilih Peran User</option>
            <option value="admin" {{ $role === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="tupusat" {{ $role === 'tupusat' ? 'selected' : '' }}>Tu Pusat</option>
            <option value="tuunit" {{ $role === 'tuunit' ? 'selected' : '' }}>Tu Unit</option>
        </select>
    </div>
</form>

                            </div>
                            <div class="flex items-center space-x-4">
                                <form action="{{ route('admin.create-user') }} ">
                                    <x-primary-button class="bg-green-500 text-white px-4 py-2 rounded flex items-center">
                                        <i class="fas fa-plus mr-2"></i>
                                        {{ __('Tambah User') }}
                                    </x-primary-button>
                                </form>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center space-x-2">
                            <form method="GET" action="{{ route('admin.manage-user') }}">
    <input type="hidden" name="role" value="{{ $role }}">
    <label class="text-sm" for="show">Show</label>
    <select class="border border-gray-300 rounded p-2" id="show" name="show" onchange="this.form.submit()">
        <option value="10" {{ request('show') == 10 ? 'selected' : '' }}>10</option>
        <option value="25" {{ request('show') == 25 ? 'selected' : '' }}>25</option>
        <option value="50" {{ request('show') == 50 ? 'selected' : '' }}>50</option>
        <option value="100" {{ request('show') == 100 ? 'selected' : '' }}>100</option>
    </select>
    <label class="text-sm" for="show">entries</label>
</form>
                            </div>
                            <div class="w-full sm:w-1/2 flex">
                                <input type="text" id="search" class="block w-full p-3 border border-gray-300 rounded flex-grow" placeholder="Cari Nama atau Username...">
                            </div>
                        </div>

                        <div class="mb-4">
                        <span id="totalItems" class="text-sm font-medium text-gray-700">Total Data: {{ $users->total() }}</span>
                        </div>

                        <div id="noDataMessage" class="p-4 bg-yellow-200 text-yellow-800 rounded-lg mb-4 hidden">
                            <p>Maaf, saat ini tidak ada data pengguna yang tersedia.</p>
                        </div>

                        <hr class="border-gray-300 mb-4" />

                        <!-- Table Data User -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <tr class="bg-green-500 text-white text-center">
                                    <th class="py-2 px-4 border-r border-gray-300 w-16">No.</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Nama</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Email</th>
                                    <th class="py-2 px-4 border-r border-gray-300">No. Telp</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Username</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Peran</th> <!-- Lebar lebih kecil -->
                                    <th class="py-2 px-4 border-r border-gray-300">Status</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Unit</th> <!-- Lebar lebih kecil -->
                                    <th class="py-2 px-4 border-r border-gray-300">Aksi</th>
                                </tr>
                                <tbody id="userTable">
                                    @foreach ($users as $item)
                                    <tr class="bg-white text-black text-center border-b border-gray-300" data-role="{{ strtolower($item->role) }}">
                                        <td class="py-2 px-4 border-r text-xs border-gray-300 w-16">{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                        <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $item->name }}</td>
                                        <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $item->email }}</td>
                                        <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $item->no_telp }}</td>
                                        <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $item->username }}</td>
                                        <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $item->role }}</td>
                                        <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $item->status }}</td>
                                        <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $item->namaUnit }}</td>
                                        <td class="py-2 px-4 border-r text-xs border-gray-300">
                                            <a href="{{ route('admin.update-user', $item->id) }}"
                                                class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded hover:bg-yellow-900 transition duration-150 ease-in-out">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-1.036L6.75 17.75a2 2 0 11-2.829-2.829L13.5 4.5m1.061-1.061L17.5 7.5m-3.061-4.061a2 2 0 113.536 1.536L8.75 16.25a2 2 0 11-2.829-2.829L13.5 4.5m1.061-1.061z" />
                                                </svg>
                                                Edit
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mt-4">
    {{ $users->links() }}
</div>

                        </div>
                    </div>

                    <script>
                        // Fungsi untuk toggle password visibility
                        function togglePassword(userId) {
                            const passwordField = document.getElementById('password-' + userId);
                            const eyeIcon = document.getElementById('eye-icon-' + userId);

                            // Cek tipe input, jika password maka ubah ke text, jika text maka ubah ke password
                            if (passwordField.type === "password") {
                                passwordField.type = "text";  // Ganti tipe menjadi text (password terlihat)
                                eyeIcon.classList.remove("fa-eye");  // Ganti ikon mata
                                eyeIcon.classList.add("fa-eye-slash"); // Menambahkan ikon mata tertutup
                            } else {
                                passwordField.type = "password";  // Kembalikan tipe menjadi password (password disembunyikan)
                                eyeIcon.classList.remove("fa-eye-slash"); // Ganti ikon mata tertutup
                                eyeIcon.classList.add("fa-eye"); // Menambahkan ikon mata terbuka
                            }
                        }

                        document.addEventListener('DOMContentLoaded', function () {
                            const searchInput = document.getElementById('search'); // Input search
                            const tableRows = document.querySelectorAll('tr[data-role]'); // Semua baris tabel
                            const totalItemsElement = document.getElementById('totalItems'); // Elemen yang menampilkan total data yang ditampilkan
                            const noDataMessage = document.getElementById('noDataMessage'); // Elemen notifikasi no data

                            function updateTotalItems() {
                                // Hitung jumlah baris yang masih terlihat
                                const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
                                totalItemsElement.textContent = `Total Data: ${visibleRows.length}`;

                                // Tampilkan atau sembunyikan pesan jika tidak ada data yang terlihat
                                if (visibleRows.length === 0) {
                                    noDataMessage.classList.remove('hidden');
                                } else {
                                    noDataMessage.classList.add('hidden');
                                }
                            }

                            searchInput.addEventListener('input', function () {
                                const searchTerm = searchInput.value.toLowerCase(); // Ambil input pencarian dan ubah menjadi huruf kecil
                                tableRows.forEach(row => {
                                    const nameCell = row.querySelector('td:nth-child(2)'); // Kolom Nama
                                    const usernameCell = row.querySelector('td:nth-child(5)'); // Kolom Username

                                    // Ambil teks dari kolom Nama dan Username
                                    const nameText = nameCell ? nameCell.textContent.toLowerCase() : '';
                                    const usernameText = usernameCell ? usernameCell.textContent.toLowerCase() : '';

                                    // Periksa apakah teks pencarian ada di dalam Nama atau Username
                                    if (nameText.includes(searchTerm) || usernameText.includes(searchTerm)) {
                                        row.style.display = ''; // Tampilkan baris jika cocok
                                    } else {
                                        row.style.display = 'none'; // Sembunyikan baris jika tidak cocok
                                    }
                                });
                                updateTotalItems(); // Update jumlah item yang tampil
                            });

                            updateTotalItems(); // Perbarui jumlah item pada awal halaman
                        });
                    </script>
                </div>
            </div>
        </body>
    </html>
</x-layout-admin>
