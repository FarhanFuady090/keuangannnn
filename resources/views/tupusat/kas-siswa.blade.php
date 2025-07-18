  <x-layout-tupusat>
    <x-slot name="header">
    </x-slot>

    <!-- Konten halaman -->
    <html lang="en">
        <head>
            <meta charset="utf-8" />
            <meta content="width=device-width, initial-scale=1.0" name="viewport" />
            <title>Transaksi Kas Seluruh Unit Pendidikan</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
        </head>

        <body class="bg-gray-100">
            <div class="flex h-screen">
                <!-- Main Content -->
                <div class="flex-1 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="text-2xl font-bold">Transaksi Kas Seluruh Unit Pendidikan</div>
                    </div>

                    <div class="bg-white p-4 rounded shadow">
                        <div class="flex justify-between items-center mb-4">
                            <form method="GET" action="#" class="flex items-center space-x-4">
                                <!-- Dropdown Filter Unit Pendidikan -->
                                <select name="unitpendidikan" class="border border-gray-300 rounded p-2 text-sm" onchange="this.form.submit()">
                                    <option value="">Pilih Kategori</option>
                                    <option value="2">Pemasukan</option>
                                    <option value="3">Pengeluaran</option>
                                </select>

                                <!-- Dropdown Filter Status -->
                                <select name="status" class="border border-gray-300 rounded p-2 text-sm" onchange="this.form.submit()">
                                    <option value="">Pilih Kasir</option>
                                    <option value="Aktif">Pak Wulan</option>
                                    <option value="Non Aktif">Ibu Wulan</option>
                                </select>
                            </form>

                            <!-- Button Tambah Data -->
                            <div class="flex items-center space-x-4">
                                <a href="#">
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
                            <form method="GET" action="#">
    <label class="text-sm" for="show">Show</label>
    <select class="border border-gray-300 rounded p-2" id="show" name="show" onchange="this.form.submit()">
        <option value="10">10</option>
        <option value="25">25</option>
        <option value="50">50</option>
        <option value="1">1</option>
    </select>
    <label class="text-sm" for="show">entries</label>
</form>
                            </div>

                            <!-- Search Input -->
                            <div class="w-full sm:w-1/2 flex">
                                <input class="block w-full p-3 border border-gray-300 rounded flex-grow" id="search" type="text" placeholder="Cari Nama Kas..." />
                            </div>
                        </div>

                        <div class="mb-4">
                        <span id="totalItems" class="text-sm font-medium text-gray-700">Total Data:</span>
                        </div>

                        <div id="noDataMessage" class="p-4 bg-yellow-200 text-yellow-800 rounded-lg mb-4 hidden">
                            <p>Maaf, saat ini tidak ada data Jenis Pemasukan yang sesuai dengan pencarian.</p>
                        </div>

                        <hr class="border-gray-300 mb-4" />

                        <!-- Table for Data -->
                        <div class="overflow-x-auto">
                            <table id="data-table" class="min-w-full bg-white border border-gray-300">
                                <thead>
                                    <tr class="bg-green-500 text-white text-center">
                                        <th class="py-2 px-4 border-r border-gray-300 w-16">No.</th>
                                        <th class="py-2 px-4 border-r border-gray-300">Tanggal Transaksi</th>
                                        <th class="py-2 px-4 border-r border-gray-300">Nama Kas</th>
                                        <th class="py-2 px-4 border-r border-gray-300">Kategori</th>
                                        <th class="py-2 px-4 border-r border-gray-300">Nominal</th>
                                        <th class="py-2 px-4 border-r border-gray-300">Unit</th>
                                        <th class="py-2 px-4 border-r border-gray-300">Petugas</th>
                                    </tr>
                                </thead>
                            </table>
                            <div class="mt-4">
</div>
                        </div>
                    </div>
                </div>
            </div>
        </body>
    </html>
</x-layout-tupusat>
