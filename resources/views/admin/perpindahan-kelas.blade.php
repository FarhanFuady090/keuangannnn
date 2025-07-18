<x-layout-admin>
    <x-slot name="header"></x-slot>

    <html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <title>Perpindahan / Kenaikan Kelas</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
    </head>
    <body class="bg-gray-100">
    <div class="flex h-screen">
        <div class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="text-2xl font-bold">Proses Perpindahan Kelas</div>
            </div>
            <div class="bg-white p-4 rounded shadow">
            @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <strong class="font-bold">Terjadi Kesalahan:</strong>
        <ul class="mt-2 list-disc list-inside text-sm">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if (session('success'))
    <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

                <div class="flex items-center space-x-2 mb-2">
                    <label class="text-sm" for="entries">Show</label>
                    <form method="GET" action="{{ route('admin.perpindahan-kelas') }}">
                        <select name="entries" class="border border-gray-300 rounded p-2 text-sm" onchange="this.form.submit()">
                            <option value="10" {{ request('entries') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('entries') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('entries') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('entries') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </form>
                    <label class="text-sm" for="entries">entries</label>
                </div>

                <div class="mb-4">
                    <span id="totalItems" class="text-sm font-medium text-gray-700">
                        Total Data: {{ $totalSiswa }}
                    </span>
                </div>

                @if ($totalSiswa === 0)
                    <div class="p-4 bg-yellow-200 text-yellow-800 rounded-lg mb-4">
                        @switch($emptyReason)
                            @case('unit')
                                <p>Maaf, tidak ada data siswa untuk unit yang dipilih.</p>
                                @break
                            @case('kelas')
                                <p>Maaf, tidak ada data siswa untuk kelas yang dipilih.</p>
                                @break
                            @case('grade')
                                <p>Maaf, tidak ada data siswa untuk grade yang dipilih.</p>
                                @break
                            @case('status')
                                <p>Maaf, tidak ada data siswa untuk status yang dipilih.</p>
                                @break
                            @default
                                <p>Maaf, saat ini tidak ada data siswa yang tersedia.</p>
                        @endswitch
                    </div>
                @endif

                <hr class="border-gray-300 mb-4" />

                <div class="flex justify-between items-center mb-4">
                    <div class="flex items-center space-x-4">
                        <form method="GET" action="{{ route('admin.perpindahan-kelas') }}" class="flex items-center space-x-4">
                            <label>Pilihan Awal</label>
                            <select name="unit_id" class="border border-gray-300 rounded p-2 text-sm form-control">
                                <option value="">-- Pilih Unit --</option>
                                @foreach ($units as $unit)
                                    <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>{{ $unit->namaUnit }}</option>
                                @endforeach
                            </select>
                            <select name="kelas_id" class="border border-gray-300 rounded p-2 text-sm form-control">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" {{ request('kelas_id') == $kelas->id ? 'selected' : '' }}>{{ $kelas->nama_kelas }}</option>
                                @endforeach
                            </select>
                            <select name="grade" class="border border-gray-300 rounded p-2 text-sm form-control">
                                <option value="">-- Pilih Grade --</option>
                                @foreach (['A','B','C','D','E','F'] as $grade)
                                    <option value="{{ $grade }}" {{ request('grade') == $grade ? 'selected' : '' }}>{{ $grade }}</option>
                                @endforeach
                            </select>
                            <select name="status" class="border border-gray-300 rounded p-2 text-sm form-control">
                                <option value="">-- Pilih Status --</option>
                                @foreach (['Aktif', 'Non Aktif', 'Drop Out', 'Pindah', 'Lulus'] as $status)
                                    <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>{{ $status }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded text-sm">Filter</button>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <form method="POST" action="{{ route('admin.proses') }}">
                        @csrf
                        <table class="min-w-full bg-white border border-gray-300">
                            <thead>
                                <tr class="bg-green-500 text-white text-center">
                                    <th class="py-2 px-4 border-r border-gray-300">No</th>
                                    <th class="py-2 px-4 border-r border-gray-300">NIS</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Nama Siswa</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Unit</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Kelas</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Grade</th>
                                    <th class="py-2 px-4 border-r border-gray-300">Status</th>
                                    <th class="py-2 px-4 border-r border-gray-300"><input type="checkbox" id="checkAll"> Pilih Semua</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswas as $siswa)
                                    <tr class="bg-white text-black text-center border-b border-gray-300">
                                        <td class="py-2 px-4 text-xs border-r border-gray-300">{{ $loop->iteration }}</td>
                                        <td class="py-2 px-4 text-xs border-r border-gray-300">{{ $siswa->nis }}</td>
                                        <td class="py-2 px-4 text-xs border-r border-gray-300">{{ $siswa->nama }}</td>
                                        <td class="py-2 px-4 text-xs border-r border-gray-300">{{ $siswa->unitpendidikan->namaUnit ?? '-' }}</td>
                                        <td class="py-2 px-4 text-xs border-r border-gray-300">{{ $siswa->kelas->nama_kelas ?? '-' }}</td>
                                        <td class="py-2 px-4 text-xs border-r border-gray-300">{{ $siswa->kelas->grade ?? '-' }}</td>
                                        <td class="py-2 px-4 text-xs border-r border-gray-300">
                                            @switch($siswa->status)
                                                @case('Aktif')
                                                    <span class="px-2 py-1 font-semibold text-green-700 bg-green-100 rounded-full">Aktif</span>
                                                    @break
                                                @case('Non Aktif')
                                                    <span class="px-2 py-1 font-semibold text-red-700 bg-red-100 rounded-full">Non Aktif</span>
                                                    @break
                                                @case('Drop Out')
                                                    <span class="px-2 py-1 font-semibold text-yellow-800 bg-yellow-100 rounded-full">Drop Out</span>
                                                    @break
                                                @case('Pindah')
                                                    <span class="px-2 py-1 font-semibold text-blue-700 bg-blue-100 rounded-full">Pindah</span>
                                                    @break
                                                @case('Lulus')
                                                    <span class="px-2 py-1 font-semibold text-purple-700 bg-purple-100 rounded-full">Lulus</span>
                                                    @break
                                                @default
                                                    <span class="px-2 py-1 font-semibold text-gray-700 bg-gray-100 rounded-full">Tidak Diketahui</span>
                                            @endswitch
                                        </td>
                                        <td class="py-2 px-4 text-xs border-r border-gray-300"><input type="checkbox" name="siswa_ids[]" value="{{ $siswa->id }}"></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <br />
                        <hr class="border-gray-300 mb-4" />

                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center space-x-4">
                                <label>Unit Tujuan</label>
                                <select name="unit_tujuan" class="border border-gray-300 rounded p-2 text-sm form-control" required>
                                    <option value="">-- Pilih Unit --</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->namaUnit }}</option>
                                    @endforeach
                                </select>

                                <label>Kelas Tujuan</label>
                                <select name="kelas_tujuan" class="border border-gray-300 rounded p-2 text-sm form-control" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach ($kelasList as $kelas)
                                        <option value="{{ $kelas->id }}">{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>

                                <label>Grade Tujuan</label>
                                <select name="grade_tujuan" class="border border-gray-300 rounded p-2 text-sm form-control">
                                    <option value="">-- Pilih Grade --</option>
                                    @foreach (['A','B','C','D','E','F'] as $grade)
                                        <option value="{{ $grade }}">{{ $grade }}</option>
                                    @endforeach
                                </select>

                                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded text-sm">Proses Pindah / Naik Kelas</button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-4">
                        {{ $siswas->links() }}
                    </div>

                    <script>
                        document.getElementById('checkAll').onclick = function() {
                            let checkboxes = document.querySelectorAll('input[name="siswa_ids[]"]');
                            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
                        };
                    </script>
                </div>
            </div>
        </div>
    </body>
    </html>
</x-layout-admin>
