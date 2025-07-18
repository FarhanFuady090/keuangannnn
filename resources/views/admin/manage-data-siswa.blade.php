<x-layout-admin>
    <x-slot name="header"></x-slot>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Konten halaman -->
    <html lang="en">
        <head>
            <meta charset="utf-8" />
            <meta content="width=device-width, initial-scale=1.0" name="viewport" />
            <title>Manajemen Data Siswa</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
        </head>
        <body class="bg-gray-100">
            <div class="flex h-screen">
                <!-- Main Content -->
                <div class="flex-1 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="text-2xl font-bold">Manajemen Data Siswa</div>
                    </div>
                    <div class="bg-white p-4 rounded shadow">
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                            <strong>Sukses!</strong> {{ session('success') }}
                        </div>
                    @endif

                    @if(session('failures'))
                        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
                            <p><strong>Beberapa baris gagal diimpor:</strong></p>
                            <ul class="list-disc list-inside text-sm mt-2">
                                @foreach (session('failures') as $failure)
                                    <li>
                                        Baris {{ $failure->row() }}:
                                        @foreach ($failure->errors() as $error)
                                            <span>{{ $error }}</span>
                                        @endforeach
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center space-x-4">
                                <form method="GET" action="{{ route('admin.manage-data-siswa') }}" class="flex items-center space-x-4">
                                    <!-- Filter Kelas -->
                                    <select name="kelas_id" class="border border-gray-300 rounded p-2 text-sm">
                                        <option value="">Pilih Kelas</option>
                                        @foreach($kelas as $data)
                                            <option value="{{ $data->id }}" {{ request('kelas_id') == $data->id ? 'selected' : '' }}>{{ $data->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                    <!-- Filter Status -->
                                    <select name="status" class="border border-gray-300 rounded p-2 text-sm">
                                        <option value="Pilih Status" {{ request('status') == 'Pilih Status' ? 'selected' : '' }}>Pilih Status</option>
                                        <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="Non Aktif" {{ request('status') == 'Non Aktif' ? 'selected' : '' }}>Non Aktif</option>
                                        <option value="Drop Out" {{ request('status') == 'Drop Out' ? 'selected' : '' }}>Drop Out</option>
                                        <option value="Pindah" {{ request('status') == 'Pindah' ? 'selected' : '' }}>Pindah</option>
                                        <option value="Lulus" {{ request('status') == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                    </select>
                                    <!-- Tombol Tampilkan -->
                                    <button class="bg-yellow-500 text-white px-4 py-2 rounded text-sm">Tampilkan</button>
                                </form>
                            </div>
                            <div class="flex items-center space-x-4">
                                <!-- Tombol Import -->
                                <x-primary-button
                                    class="bg-green-500 text-white px-4 py-2 rounded flex items-center"
                                    onclick="toggleModal('importModal')"
                                >
                                    <i class="fas fa-file-import mr-2"></i>
                                    {{ __('Import Data Siswa') }}
                                </x-primary-button>

                                <!-- Tombol Tambah -->
                                <a href="{{ route('admin.create-data-siswa') }}">
                                    <x-primary-button class="bg-green-500 text-white px-4 py-2 rounded flex items-center">
                                        <i class="fas fa-plus mr-2"></i>
                                        {{ __('Tambah Data') }}
                                    </x-primary-button>
                                </a>

                                <!-- Tombol Download Template -->
                                <a href="{{ asset('template/template-import-siswa.xlsx') }}" download>
                                    <x-primary-button class="bg-green-500 text-white px-4 py-2 rounded flex items-center">
                                        <i class="fas fa-download mr-2"></i>
                                        {{ __('Download Template') }}
                                    </x-primary-button>
                                </a>
                            </div>

                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center space-x-2">
                                <label class="text-sm" for="entries">Show</label>
                                <form method="GET" action="{{ route('admin.manage-data-siswa') }}">
    <!-- Filter & search lainnya -->

    <select name="entries" class="border border-gray-300 rounded p-2 text-sm" onchange="this.form.submit()">
        <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
        <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
        <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
        <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
    </select>
</form>
                                <label class="text-sm" for="entries">entries</label>
                            </div>

                            <div class="w-full sm:w-1/2 flex">
                                <input
                                    class="block w-full p-3 border border-gray-300 rounded flex-grow"
                                    id="search"
                                    name="search"
                                    type="text"
                                    placeholder="Cari Nama atau NIS..."
                                    value="{{ request('search') }}"
                                />
                            </div>
                        </div>

                        <!-- Modal Import Data -->
                        <div id="importModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center">
                            <div class="bg-white w-1/3 rounded-lg shadow-lg p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h2 class="text-lg font-bold">Import Data Siswa</h2>
                                    <button onclick="toggleModal('importModal')" class="text-gray-500">&times;</button>
                                </div>
                                <form action="{{ route('admin.import-data-siswa') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-4">
                                        <label for="file" class="block text-sm font-medium text-gray-700 mb-2">Pilih File</label>
                                        <input
                                            type="file"
                                            id="file"
                                            name="file"
                                            accept=".xlsx,.xls,.csv"
                                            class="border border-gray-300 rounded p-2 w-full"
                                            required
                                        />
                                    </div>
                                    <div class="flex justify-end">
                                        <button type="button" onclick="toggleModal('importModal')" class="bg-red-500 text-white px-4 py-2 rounded">Batal</button>
                                        <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded ml-2">Import</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <script>
                            function toggleModal(id) {
                                const modal = document.getElementById(id);
                                if (modal.classList.contains('hidden')) {
                                    modal.classList.remove('hidden');
                                    modal.classList.add('flex');
                                } else {
                                    modal.classList.add('hidden');
                                    modal.classList.remove('flex');
                                }
                            }
                        </script>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const searchInput = document.getElementById('search');
                                const tableRows = document.querySelectorAll('.siswa-row');
                                const totalItemsElement = document.getElementById('totalItems');
                                const noDataMessage = document.getElementById('noDataMessage');

                                function updateTotalItems() {
                                    const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
                                    totalItemsElement.textContent = `Total Data: ${visibleRows.length}`;

                                    if (visibleRows.length === 0) {
                                        noDataMessage.classList.remove('hidden');
                                    } else {
                                        noDataMessage.classList.add('hidden');
                                    }
                                }

                                searchInput.addEventListener('input', function () {
                                    const searchTerm = searchInput.value.toLowerCase();
                                    tableRows.forEach(row => {
                                        const namaCell = row.querySelector('td:nth-child(3)');
                                        const nisCell = row.querySelector('td:nth-child(2)');

                                        const namaText = namaCell ? namaCell.textContent.toLowerCase() : '';
                                        const nisText = nisCell ? nisCell.textContent.toLowerCase() : '';

                                        if (namaText.includes(searchTerm) || nisText.includes(searchTerm)) {
                                            row.style.display = '';
                                        } else {
                                            row.style.display = 'none';
                                        }
                                    });
                                    updateTotalItems();
                                });

                                updateTotalItems();
                            });
                        </script>

                        <div class="mb-4">
                            <span id="totalItems" class="text-sm font-medium text-gray-700">Total Data: {{ $siswas->count() }}</span>
                        </div>

                        <div id="noDataMessage" class="p-4 bg-yellow-200 text-yellow-800 rounded-lg mb-4 hidden">
                            <p>Maaf, saat ini tidak ada data Siswa yang tersedia.</p>
                        </div>

                        <hr class="border-gray-300 mb-4" />

                        <!-- Table Data Siswa -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white border border-gray-300">
                                <tr class="bg-green-500 text-white text-center">
                                    <th class="py-2 px-4 border-r border-gray-300 w-16">No.</th>
                                    <th class="py-2 px-4 border-r border-gray-300">NIS</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Nama Siswa</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Jenis Kelamin</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Kelas</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Status</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Aksi</th>
                                </tr>
                                <tbody id="siswaTable">
                                @foreach ($siswas as $no=>$data)
                                <tr class="bg-white text-black text-center border-b border-gray-300 siswa-row">
                                    <td class="py-2 px-4 border-r text-xs border-gray-300 w-16">{{ $no+1 }}</td>
                                    <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $data->nis }}</td>
                                    <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $data->nama }}</td>
                                    <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $data->jenis_kelamin }}</td>
                                    <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $data->kelas->nama_kelas}}</td>
                                    <td class="py-2 px-4 border-r text-xs border-gray-300">
    @if($data->status === 'Aktif')
        <span class="px-2 py-1 font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span>
    @elseif($data->status === 'Non Aktif')
        <span class="px-2 py-1 font-semibold text-red-700 bg-red-100 rounded-full">Non Aktif</span>
    @elseif($data->status === 'Drop Out')
        <span class="px-2 py-1 font-semibold text-yellow-800 bg-yellow-100 rounded-full">Drop Out</span>
    @elseif($data->status === 'Pindah')
        <span class="px-2 py-1 font-semibold text-blue-700 bg-blue-100 rounded-full">Pindah</span>
    @elseif($data->status === 'Lulus')
        <span class="px-2 py-1 font-semibold text-purple-700 bg-purple-100 rounded-full">Lulus</span>
    @else
        <span class="px-2 py-1 font-semibold text-gray-700 bg-gray-100 rounded-full">Tidak Diketahui</span>
    @endif
</td>
                                    <td class="flex justify-center space-x-2 py-2">
                                        <a href="{{ route('admin.detail-data-siswa', $data->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-blue-500 text-white text-xs font-medium rounded hover:bg-blue-900 transition duration-150 ease-in-out">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3c0 1.1-.9 2-2 2H7c-1.1 0-2-.9-2-2s.9-2 2-2h6c1.1 0 2 .9 2 2zM5 6v12c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V6H5z" />
                                                    </svg>
                                                    Detail
                                        <a href="{{ route('admin.edit-data-siswa', $data->id) }}"
                                        class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded hover:bg-yellow-900 transition duration-150 ease-in-out">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-1.036L6.75 17.75a2 2 0 11-2.829-2.829L13.5 4.5m1.061-1.061L17.5 7.5m-3.061-4.061a2 2 0 113.536 1.536L8.75 16.25a2 2 0 11-2.829-2.829L13.5 4.5m1.061-1.061z" />
                                                    </svg>
                                                    Edit
                                    </td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-4">
                            {{ $siswas->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </body>
    </html>
</x-layout-admin>
