<x-layout-admin>

    <x-slot name="header"></x-slot>

    <!-- Konten halaman -->
    <html lang="en">
        <head>
            <meta charset="utf-8" />
            <meta content="width=device-width, initial-scale=1.0" name="viewport" />
            <title>Manajemen Data Jenis Kas</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
        </head>

        <body class="bg-gray-100">
            <div class="flex h-screen">
                <!-- Main Content -->
                <div class="flex-1 p-6">
                  <div class="flex justify-between items-center mb-6">
                    <div class="text-2xl font-bold">Manajemen Data Jenis Kas</div>
                  </div>

                  <div class="bg-white p-4 rounded shadow">
                    @if(session('success'))
                        <div class="mb-4 px-4 py-3 rounded bg-green-100 text-green-800 border border-green-300">
                            <strong>Sukses!</strong> {{ session('success') }}
                        </div>
                    @endif
                      <div class="flex justify-between items-center mb-4">
                      <form action="{{ route('admin.manage-data-kas') }}" method="GET" class="flex items-center space-x-4">
  <select id="kategori" name="kategori" class="border border-gray-300 rounded p-2 text-sm" onchange="this.form.submit()">
    <option value="">Kategori</option>
    <option value="pemasukan" {{ request('kategori') == 'pemasukan' ? 'selected' : '' }}>Pemasukan</option>
    <option value="pengeluaran" {{ request('kategori') == 'pengeluaran' ? 'selected' : '' }}>Pengeluaran</option>
  </select>

  <select id="status" name="status" class="border border-gray-300 rounded p-2 text-sm" onchange="this.form.submit()">
    <option value="">Semua Status</option>
    <option value="Aktif" {{ request('status') == 'Aktif' ? 'selected' : '' }}>Aktif</option>
    <option value="Non Aktif" {{ request('status') == 'Non Aktif' ? 'selected' : '' }}>Non Aktif</option>
  </select>
</form>


                        <!-- Button Tambah Data -->
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('admin.create-data-kas') }} ">
                                <x-primary-button class="bg-green-500 text-white px-4 py-2 rounded flex items-center">
                                    <i class="fas fa-plus mr-2"></i>
                                    {{ __('Tambah Data') }}
                                </x-primary-button>
                            </a>
                        </div>
                      </div>
                      <!-- Filter Entries -->
                      <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center space-x-2">  
                          <form method="GET" action="{{ route('admin.manage-data-kas') }}">
                            <label class="text-sm" for="entries">Show</label>
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
                            <input type="text" id="search" class="block w-full p-3 border border-gray-300 rounded flex-grow" placeholder="Cari Nama Kas..." onkeyup="searchData()">
                        </div>

                      </div>

                      <div class="mb-4">
                            <span id="totalItems" class="text-sm font-medium text-gray-700">Total Data: {{ $kas->count() }}</span>
                        </div>

                        <div id="noDataMessage" class="p-4 bg-yellow-200 text-yellow-800 rounded-lg mb-4 {{ $kas->isEmpty() ? '' : 'hidden' }}">
    <p>Maaf, saat ini tidak ada data Kas yang tersedia.</p>
</div>


                      <hr class="border-gray-300 mb-4" />

                      <div class="overflow-x-auto">
                        <table class="min-w-full bg-white border border-gray-300">
                          <thead>
                            <tr class="bg-green-500 text-white text-center">
                              <th class="py-2 px-4 border-r border-gray-300 w-16">No.</th>
                              <th class="py-2 px-4 border-r border-gray-300">Nama Kas</th>
                              <th class="py-2 px-4 border-r border-gray-300">Kategori</th>
                              <th class="py-2 px-4 border-r border-gray-300">Status</th>
                              <th class="py-2 px-4 border-r border-gray-300 w-32">Aksi</th>
                            </tr>
                          </thead>
                            <tbody id="tableBody">
  @forelse($kas as $index => $item)
    <tr class="bg-white text-black text-center border-b border-gray-300">
      <td class="py-2 px-4 border-r text-xs border-gray-300 w-16">{{ $kas->firstItem() + $index }}</td>
      <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $item->namaKas }}</td>
      <td class="py-2 px-4 border-r text-xs border-gray-300">{{ $item->kategori }}</td>
      <td class="py-2 px-4 border-r text-xs border-gray-300">
        @if($item->status === 'Aktif')
          <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span>
        @else
          <span class="px-2 py-1 text-xs font-semibold text-red-700 bg-red-100 rounded-full">Non Aktif</span>
        @endif
      </td>
      <td class="py-2 px-4 border-r text-xs border-gray-300 w-32">
                                    <div class="flex justify-center space-x-2">
                                      <a href="{{ route('admin.edit-data-kas', $item->id) }}" class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-xs font-medium rounded hover:bg-yellow-900 transition duration-150 ease-in-out">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-1.036L6.75 17.75a2 2 0 11-2.829-2.829L13.5 4.5m1.061-1.061L17.5 7.5m-3.061-4.061a2 2 0 113.536 1.536L8.75 16.25a2 2 0 11-2.829-2.829L13.5 4.5m1.061-1.061z" />
                                                    </svg>
                                          Edit
                                      </a>
                                    </div>
                                  </td>
    </tr>
  @empty
    <tr>
      <td colspan="5" class="text-center py-4 text-sm text-gray-500">Tidak ada data ditemukan.</td>
    </tr>
  @endforelse
</tbody>

                        </table>
                        <div class="mt-4">
    {{ $kas->onEachSide(1)->links('pagination::tailwind') }}
</div>

                        <script>
function searchData() {
  const searchQuery = document.getElementById('search').value.toLowerCase();
  const rows = document.querySelectorAll('#tableBody tr');
  const noDataMessage = document.getElementById('noDataMessage');
  const totalItems = document.getElementById('totalItems');

  let visibleRows = 0;

  rows.forEach(row => {
    // skip row kalau cuma baris "tidak ada data"
    if (row.cells.length < 5) return;

    const namaKas = row.cells[1].textContent.toLowerCase();
    const username = namaKas; // bisa diganti kalau mau pakai kolom lain

    if (namaKas.includes(searchQuery) || username.includes(searchQuery)) {
      row.style.display = '';
      visibleRows++;
    } else {
      row.style.display = 'none';
    }
  });

  if (visibleRows === 0) {
    noDataMessage.classList.remove('hidden');
    totalItems.textContent = `Total Data: 0`;
  } else {
    noDataMessage.classList.add('hidden');
    totalItems.textContent = `Total Data: ${visibleRows}`;
  }
}
</script>



                      </div>
                  </div>
                </div>
            </div>
        </body>

</x-layout-admin>