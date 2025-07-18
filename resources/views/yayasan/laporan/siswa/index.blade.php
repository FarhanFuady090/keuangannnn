<x-layout-yayasan>
    <x-slot name="header"></x-slot>

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="p-6 bg-gray-100 min-h-screen">
        <div class="mb-6">
            <h2 class="text-2xl font-bold">Laporan Data Siswa</h2>
        </div>

        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                <strong>Sukses!</strong> {{ session('success') }}
            </div>
        @endif

        <!-- Filter Form -->
        <div class="bg-white p-4 rounded shadow mb-4">
            <form method="GET" action="{{ route('yayasan.laporan.siswa.index') }}" class="flex flex-wrap gap-3 items-center">
                <!-- Filter Kelas -->
                <select name="kelas_id" class="border border-gray-300 rounded p-2 text-sm">
                    <option value="">Pilih Kelas</option>
                    @foreach($kelas as $data)
                        <option value="{{ $data->id }}" {{ request('kelas_id') == $data->id ? 'selected' : '' }}>
                            {{ $data->nama_kelas }}
                        </option>
                    @endforeach
                </select>

                <!-- Filter Unit Pendidikan -->
                <select name="unitpendidikan_id" class="border border-gray-300 rounded p-2 text-sm">
                    <option value="">Pilih Unit Formal</option>
                    @foreach($unitpendidikanformal as $data)
                        <option value="{{ $data->id }}" {{ request('unitpendidikan_id') == $data->id ? 'selected' : '' }}>
                            {{ $data->namaUnit }}
                        </option>
                    @endforeach
                </select>

                <select name="unitpendidikan_idInformal" class="border border-gray-300 rounded p-2 text-sm">
                    <option value="">Pilih Unit Informal</option>
                    @foreach($unitpendidikaninformal as $data)
                        <option value="{{ $data->id }}" {{ request('unitpendidikan_idInformal') == $data->id ? 'selected' : '' }}>
                            {{ $data->namaUnit }}
                        </option>
                    @endforeach
                </select>

                <select name="unitpendidikan_idPondok" class="border border-gray-300 rounded p-2 text-sm">
                    <option value="">Pilih Pondok</option>
                    @foreach($unitpendidikanpondok as $data)
                        <option value="{{ $data->id }}" {{ request('unitpendidikan_idPondok') == $data->id ? 'selected' : '' }}>
                            {{ $data->namaUnit }}
                        </option>
                    @endforeach
                </select>

                <!-- Filter Status -->
                <select name="status" class="border border-gray-300 rounded p-2 text-sm">
                    <option value="">Pilih Status</option>
                    @foreach(['Aktif', 'Non Aktif', 'Drop Out', 'Pindah', 'Lulus'] as $status)
                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                            {{ $status }}
                        </option>
                    @endforeach
                </select>

                <!-- Tombol Aksi -->
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded text-sm">Tampilkan</button>
                <a href="{{ route('yayasan.laporan.siswa.index') }}" class="bg-yellow-500 text-white px-4 py-2 rounded text-sm hover:bg-yellow-600 transition">Reset</a>
                <a href="{{ route('yayasan.laporan.siswa.export.pdf', request()->query()) }}" class="bg-red-500 text-white px-4 py-2 rounded text-sm hover:bg-red-600 transition">Export PDF</a>
                <a href="{{ route('yayasan.laporan.siswa.export.filtered', request()->query()) }}" class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700">
                    <i class="fas fa-file-excel mr-1"></i> Export Excel
                </a>
            </form>
        </div>

        <!-- Show Entries & Search -->
        <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-2">
            <div class="flex items-center space-x-2">
                <form method="GET" action="{{ route('yayasan.laporan.siswa.index') }}">
                    <label class="text-sm">Show</label>
                    <select name="entries" class="border border-gray-300 rounded p-2 text-sm" onchange="this.form.submit()">
                        @foreach([10, 25, 50, 100] as $entry)
                            <option value="{{ $entry }}" {{ request('entries') == $entry ? 'selected' : '' }}>{{ $entry }}</option>
                        @endforeach
                    </select>
                    <label class="text-sm">entries</label>
                </form>
            </div>

            <form method="GET" action="{{ route('yayasan.laporan.siswa.index') }}" class="w-full sm:w-1/2">
                <input type="text" name="search" id="search" placeholder="Cari Nama atau NIS..." value="{{ request('search') }}"
                    class="block w-full p-3 border border-gray-300 rounded" />
            </form>
        </div>

        <!-- Info dan Table -->
        <div class="mb-4 text-sm font-medium text-gray-700">
            Total Data: {{ $siswas->count() }}
        </div>

        <div id="noDataMessage" class="p-4 bg-yellow-200 text-yellow-800 rounded-lg mb-4 hidden">
            <p>Maaf, saat ini tidak ada data Siswa yang tersedia.</p>
        </div>

        <!-- Table -->
        <div class="bg-white p-4 rounded shadow overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead>
                    <tr class="bg-green-500 text-white text-center">
                        <th rowspan="2" class="py-2 px-4 border-r w-16">No.</th>
                        <th rowspan="2" class="py-2 px-4 border-r">NIS</th>
                        <th rowspan="2" class="py-2 px-4 border-r">Nama Siswa</th>
                        <th rowspan="2" class="py-2 px-4 border-r">Jenis Kelamin</th>
                        <th rowspan="2" class="py-2 px-4 border-r">Kelas</th>
                        <th colspan="2" class="py-2 px-4 border-r">Unit Pendidikan</th>
                        <th rowspan="2" class="py-2 px-4 border-r">Status Pondok</th>
                        <th rowspan="2" class="py-2 px-4 border-r">Status</th>
                        <th rowspan="2" class="py-2 px-4 border-r">Aksi</th>
                    </tr>
                    <tr class="bg-green-500 text-white text-center">
                        <th class="py-2 px-4 border-r">Formal</th>
                        <th class="py-2 px-4 border-r">Informal</th>
                    </tr>
                </thead>
                <tbody id="siswaTable">
                    @foreach ($siswas as $no => $data)
                        <tr class="text-center border-b border-gray-300">
                            <td class="py-2 px-4 text-xs">{{ $no + 1 }}</td>
                            <td class="py-2 px-4 text-xs">{{ $data->nis ?? '-' }}</td>
                            <td class="py-2 px-4 text-xs">{{ $data->nama ?? '-' }}</td>
                            <td class="py-2 px-4 text-xs">{{ $data->jenis_kelamin ?? '-' }}</td>
                            <td class="py-2 px-4 text-xs">{{ $data->kelas->nama_kelas ?? '-' }}</td>
                            <td class="py-2 px-4 text-xs">{{ $data->unitpendidikan->namaUnit ?? '-' }}</td>
                            <td class="py-2 px-4 text-xs">
                                {{ $data->unitpendidikan_idInformal ? optional($unitpendidikan->firstWhere('id', $data->unitpendidikan_idInformal))->namaUnit : '-' }}
                            </td>
                            <td class="py-2 px-4 text-xs">
                                {{ $data->unitpendidikan_idPondok ? optional($unitpendidikan->firstWhere('id', $data->unitpendidikan_idPondok))->namaUnit : '-' }}
                            </td>
                            <td class="py-2 px-4 text-xs">
                                @switch($data->status)
                                    @case('Aktif')
                                        <span class="px-2 py-1 font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span>
                                        @break
                                    @case('Non Aktif')
                                        <span class="px-2 py-1 font-semibold text-yellow-700 bg-yellow-100 rounded-full">Non Aktif</span>
                                        @break
                                    @default
                                        <span class="px-2 py-1 font-semibold text-gray-700 bg-gray-100 rounded-full">{{ $data->status }}</span>
                                @endswitch
                            </td>
                            <td class="py-2 px-4 border-r text-xs border-gray-300">
                                <a href="{{ route('yayasan.laporan.siswa.show', $data->id) }}"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layout-yayasan>
