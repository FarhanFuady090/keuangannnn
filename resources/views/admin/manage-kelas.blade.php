<x-layout-admin>
    <x-slot name="header"></x-slot>

    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <title>Manajemen Data Kelas</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    </head>
    <body class="bg-gray-100">
    <div class="flex h-screen">
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="text-2xl font-bold">Manajemen Data Kelas</div>
            </div>
            <div class="bg-white p-4 rounded shadow">
            @if (session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
        <strong>Sukses!</strong> {{ session('success') }}
    </div>
@endif

                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-4">
                        <select id="filter-nama-unit" class="border border-gray-300 rounded p-2 text-sm">
                            <option value="">Pilih Nama Unit</option>
                            <option value="-">-</option>
                            <option value="TK">TK</option>
                            <option value="SD">SD</option>
                            <option value="SMP">SMP</option>
                            <option value="SMA">SMA</option>
                            <option value="TPQ">TPQ</option>
                            <option value="YA PONDOK">PONDOK</option>
                            <option value="MADIN">MADIN</option>
                        </select>

                        <select id="filter-grade" class="border border-gray-300 rounded p-2 text-sm">
                            <option value="">Pilih Grade</option>
                            <option value="-">-</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                            <option value="F">F</option>
                        </select>

                        <select id="filter-status" class="border border-gray-300 rounded p-2 text-sm">
                            <option value="">Pilih Status</option>
                            <option value="AKTIF">Aktif</option>
                            <option value="TIDAK AKTIF">Nonaktif</option>
                        </select>
                    </div>
                    <div class="ml-auto">
                        <a href="{{ route('admin.create-kelas') }}">
                            <x-primary-button class="bg-green-500 text-white px-4 py-2 rounded flex items-center">
                                <i class="fas fa-plus mr-2"></i>
                                {{ __('Tambah Kelas') }}
                            </x-primary-button>
                        </a>
                    </div>
                </div>

                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-2">
                    <form method="GET" action="{{ route('admin.manage-kelas') }}">
    <label class="text-sm" for="show">Show</label>
    <input type="hidden" name="unit" value="{{ request('unit') }}">
<input type="hidden" name="grade" value="{{ request('grade') }}">
<input type="hidden" name="status" value="{{ request('status') }}">
<input type="hidden" name="search" value="{{ request('search') }}">
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
                        <input type="text" id="nama_kelas_filter" class="block w-full p-3 border border-gray-300 rounded flex-grow" placeholder="Cari Kelas...">
                    </div>
                </div>

                <div class="mb-4">
                    <span id="totalItems" class="text-sm font-medium text-gray-700">Total Data: {{ $kelas->total() }}</span>
                </div>
                
                <div id="noResultsMessage" class="hidden p-4 bg-yellow-200 text-yellow-800 rounded-lg mb-4"><p>Maaf, saat ini tidak ada data Kas yang tersedia.</p></div>



                <hr class="border-gray-300 mb-4" />

                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead>
                        <tr class="bg-green-500 text-white text-center">
                            <th class="py-2 px-4 border-r border-gray-300 w-16">No.</th>
                            <th class="py-2 px-4 border-r border-gray-300">Unit Pendidikan</th>
                            <th class="py-2 px-4 border-r border-gray-300">Kelas</th>
                            <th class="py-2 px-4 border-r border-gray-300">Grade</th>
                            <th class="py-2 px-4 border-r border-gray-300">Keterangan</th>
                            <th class="py-2 px-4 border-r border-gray-300">Status</th>
                            <th class="py-2 px-4 border-r border-gray-300">Aksi</th>
                        </tr>
                        </thead>
                        <tbody id="kelas-table-body">
                        @foreach ($kelas as $itemm)
                            <tr class="bg-white text-black text-center border-b border-gray-300">
                                <td class="py-2 px-4 border-r text-xs border-gray-300 w-16">{{ ($kelas->currentPage() - 1) * $kelas->perPage() + $loop->iteration }}</td>
                                <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $itemm->unitpendidikan->namaUnit }}</td>
                                <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $itemm->nama_kelas }}</td>
                                <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $itemm->grade }}</td>
                                <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $itemm->keterangan }}</td>
                                <td class="py-2 px-4 border-r text-xs border-gray-300">
                                @if($itemm->status === 'AKTIF')
          <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span>
        @else
          <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Non Aktif</span>
        @endif
                                </td>
                                <td class="py-2 px-4 border-r text-xs border-gray-300">
                                    <a href="{{ route('admin.edit-kelas', $itemm->id) }}"
                                       class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded hover:bg-yellow-900 transition duration-150 ease-in-out">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                             viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M15.232 5.232l3.536 3.536m-2.036-1.036L6.75 17.75a2 2 0 11-2.829-2.829L13.5 4.5m1.061-1.061L17.5 7.5m-3.061-4.061a2 2 0 113.536 1.536L8.75 16.25a2 2 0 11-2.829-2.829L13.5 4.5m1.061-1.061z"/>
                                        </svg>
                                        Edit
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">
    {{ $kelas->links() }}
</div>
                </div>
            </div>
        </div>

        <script>
    document.addEventListener("DOMContentLoaded", function () {
        const unitFilter = document.getElementById("filter-nama-unit");
        const gradeFilter = document.getElementById("filter-grade");
        const statusFilter = document.getElementById("filter-status");
        const searchInput = document.getElementById("nama_kelas_filter");
        const tableBody = document.getElementById("kelas-table-body");
        const rows = tableBody.querySelectorAll("tr");
        const totalItems = document.getElementById("totalItems");
        const noResultsMessage = document.getElementById("noResultsMessage");

        function filterTable() {
            let visibleCount = 0;
            const unit = unitFilter.value.toLowerCase();
            const grade = gradeFilter.value.toLowerCase();
            const status = statusFilter.value.toLowerCase();
            const searchTerm = searchInput.value.toLowerCase();

            rows.forEach(row => {
                const unitCell = row.cells[1].innerText.toLowerCase();
                const namaKelasCell = row.cells[2].innerText.toLowerCase();
                const gradeCell = row.cells[3].innerText.toLowerCase();
                const statusCell = row.cells[5].innerText.toLowerCase();

                const isMatch = 
                    (unit === "" || unitCell === unit) &&
                    (grade === "" || gradeCell === grade) &&
                    (status === "" || statusCell === status) &&
                    (namaKelasCell.includes(searchTerm));

                row.style.display = isMatch ? "" : "none";
                if (isMatch) visibleCount++;
            });

            totalItems.textContent = `Total Data: ${visibleCount}`;
            noResultsMessage.classList.toggle("hidden", visibleCount > 0);
        }

        // Event listeners
        unitFilter.addEventListener("change", filterTable);
        gradeFilter.addEventListener("change", filterTable);
        statusFilter.addEventListener("change", filterTable);
        searchInput.addEventListener("input", filterTable);

        // Trigger awal (biar kalau ada default value, tetap sinkron)
        filterTable();
    });
</script>
    </body>
    </html>
</x-layout-admin>
