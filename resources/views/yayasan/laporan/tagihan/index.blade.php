<x-layout-yayasan>
    <style>
    body {
        overflow-x: hidden;
    }
</style>

<div class="sticky top-0 z-50 h-12 bg-green-800 px-4 shadow mb-4 flex items-center">
    <!-- Logo Aplikasi -->
    <img src="{{ asset('images/logo-yysn.png') }}" alt="Application Logo" class="h-5 mr-3">

    <div class="text-white text-sm">
        Yayasan Nurul Huda
    </div>
</div>


    <div class="p-6 mx-auto max-w-[1270px]">
        <h4 class="text-xl font-semibold text-gray-700 mb-6">Laporan Tagihan</h4>

       <form id="filterForm" method="GET" action="{{ route('yayasan.laporan.tagihan.index') }}"
        class="mb-4 flex flex-wrap items-end gap-4">

<!-- Jenis Pembayaran -->
<div class="flex flex-col">
    <label for="jenis_pembayaran_id" class="text-sm font-medium text-gray-700 mb-1">Jenis Pembayaran</label>
    <select name="jenis_pembayaran_id" id="jenis_pembayaran_id"
        class="min-w-[225px] px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
        <option value="">-- Pilih Jenis --</option>
        @foreach($jenisPembayaran as $jenis)
            <option value="{{ $jenis->id }}" {{ request('jenis_pembayaran_id') == $jenis->id ? 'selected' : '' }}>
                {{ $jenis->nama_pembayaran }} {{ $jenis->unitPendidikan->namaUnit ?? 'Unit tidak ditemukan' }}
            </option>
        @endforeach
    </select>
</div>

<!-- Tipe Pembayaran -->
<div class="flex flex-col">
    <label for="type" class="text-sm font-medium text-gray-700 mb-1">Tipe Pembayaran</label>
    <select name="type" id="type"
        class="min-w-[200px] px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-500">
        <option value="" {{ request()->filled('type') ? '' : 'selected' }}>-- Semua Tipe --</option>
        @foreach($tipePembayaranList as $tipe)
            <option value="{{ $tipe }}" {{ request('type') == $tipe ? 'selected' : '' }}>
                {{ ucfirst($tipe) }}
            </option>
        @endforeach
    </select>
</div>

             <!-- Unit Pendidikan -->
  <div class="flex flex-col">
        <label for="unitpendidikan_id" class="text-sm font-medium text-gray-700 mb-1">Unit Pendidikan</label>
        <select name="unitpendidikan_id" id="unit_pendidikan_id"
            class="min-w-[200px] px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
            onchange="document.getElementById('filterForm').submit();">
            <option value="">-- Semua Unit --</option>
            @foreach($unitPendidikanList as $unit)
                <option value="{{ $unit->id }}" {{ request('unit_pendidikan_id') == $unit->id ? 'selected' : '' }}>
                    {{ $unit->namaUnit }}
                </option>
            @endforeach
        </select>
    </div>

            <!-- Kelas -->
  <div class="flex flex-col">
        <label for="kelas" class="text-sm font-medium text-gray-700 mb-1">Kelas</label>
        <select name="kelas" id="kelas"
            class="min-w-[200px] px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-500"
            onchange="document.getElementById('filterForm').submit();">
            <option value="">-- Semua Kelas --</option>
            @foreach($kelasList as $k)
                <option value="{{ $k->id }}" {{ request('kelas') == $k->id ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
    </div>

   <!-- Baris Kedua: Tanggal Bayar, Semester, Tahun Ajaran, Tombol -->
<div class="w-full flex flex-wrap gap-4 items-end mb-8">

    <!-- Filter Tanggal Bayar -->
    <div class="flex flex-col">
        <label for="tanggal_awal" class="text-sm font-medium text-gray-700 mb-1">Tanggal Bayar</label>
        <div class="min-w-[200px] flex items-center gap-1">
            <input type="date" id="tanggal_awal" name="tanggal_awal"
                class="border border-gray-300 rounded px-2 py-2 text-sm w-full"
                value="{{ request('tanggal_awal') }}">
            <span class="px-1 py-2">s/d</span>
            <input type="date" id="tanggal_akhir" name="tanggal_akhir"
                class="border border-gray-300 rounded px-2 py-2 text-sm w-full"
                value="{{ request('tanggal_akhir') }}">
        </div>
    </div>

    <!-- Semester -->
    <div class="flex flex-col">
        <label class="text-sm font-medium text-gray-700 mb-1">Semester</label>
        <select name="semester" class="border border-gray-300 rounded px-4 py-2 min-w-[200px]">
            <option value="">Pilih Semester</option>
            <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil (Jul–Des)</option>
            <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap (Jan–Jun)</option>
        </select>
    </div>

    <!-- Tahun Ajaran -->
    <div class="flex flex-col">
        <label class="text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
        <select name="tahun_ajaran" class="border border-gray-300 rounded px-4 py-2 min-w-[200px]">
            <option value="">Pilih Tahun Ajaran</option>
            @for ($i = now()->year + 1; $i >= 2024; $i--)
                <option value="{{ $i }}" {{ request('tahun_ajaran') == $i ? 'selected' : '' }}>
                    {{ $i }}/{{ $i+1 }}
                </option>
            @endfor
        </select>
    </div>

    <!-- Tombol Filter & Reset -->
    <div class="flex flex-col min-w-[200px]">
        <label class="text-sm font-medium text-gray-700 mb-1 invisible">Aksi</label>
        <div class="flex gap-2">
            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded w-full">
                Filter
            </button>

            @php
                $resetUrl = route('yayasan.laporan.tagihan.index');
                if (request()->get('trashed')) {
                    $resetUrl .= '?trashed=true';
                }
            @endphp

            <a href="{{ $resetUrl }}"
               class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded w-full text-center">
                Reset
            </a>
        </div>
    </div>
</div>

<div class="relative overflow-x-auto mb-10">
    <table class="text-sm text-left text-gray-700 min-w-[1220px] border border-gray-300 rounded">
        <thead class="bg-gray-100 text-gray-800 font-semibold">
            <tr class="border-b">
                <th class="px-4 py-2 border">No</th>
                <th class="px-4 py-2 border">Jenis Pembayaran</th>
                <th class="px-4 py-2 border">Tipe Pembayaran</th>
                <th class="px-4 py-2 border">Unit Pendidikan</th>
                <th class="px-4 py-2 border text-right">Total Tagihan Terbayar</th>
                <th class="px-4 py-2 border text-right">Total Tagihan Belum Terbayar</th>
                <th class="px-4 py-2 border text-right">Total Tagihan</th>
            </tr>
        </thead>
        <tbody>
            @php 
                $no = 1;
                $typeFilter = request()->filled('type') ? strtolower(request('type')) : null;
                $filteredRows = 0;
            @endphp

            @foreach ($totalPerJenisPembayaran as $jenisId => $item)
                @php
                    $jenis = collect($jenisPembayaranAktif)->firstWhere('id', $jenisId);
                @endphp

                @if ($jenis && (is_null($typeFilter) || strtolower($jenis->type) === $typeFilter))
                    @php $filteredRows++; @endphp
                    <tr class="bg-white hover:bg-gray-50 border-b">
                        <td class="border px-4 py-2 text-center">{{ $no++ }}</td>
                        <td class="border px-4 py-2">{{ $jenis->nama_pembayaran }}</td>
                        <td class="border px-4 py-2">{{ $jenis->type }}</td>
                        <td class="border px-4 py-2">{{ $jenis->unitPendidikan->namaUnit ?? '-' }}</td>
                        <td class="border px-4 py-2 text-right">Rp {{ number_format($item['total_terbayar'], 0, ',', '.') }}</td>
                        <td class="border px-4 py-2 text-right">Rp {{ number_format($item['total_belum_terbayar'], 0, ',', '.') }}</td>
                        <td class="border px-4 py-2 text-right">Rp {{ number_format($item['total_tagihan'], 0, ',', '.') }}</td>
                    </tr>
                @endif
            @endforeach

            @if ($filteredRows == 0)
                <tr>
                    <td colspan="7" class="text-center border px-4 py-2">Maaf, data tidak tersedia.</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>


  <div class="flex justify-center mb-2">
    <div class="flex flex-col items-center">
        <label for="search" class="text-sm font-medium text-gray-700 mb-1">Cari Nama / NIS</label>
     <input type="text" name="search" id="search" value="{{ $search }}"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-56"
            placeholder="Cari..." oninput="delaySubmit()">
    </div>
</div>
</form>

        <div class="relative overflow-x-auto rounded-lg shadow border border-gray-200">
            <table class="text-sm text-left text-gray-700 min-w-[1000px]">
                <thead class="bg-white">
                    <tr class="border-b">
                        <th class="sticky left-0 z-30 bg-white border-r px-4 py-2 min-w-[50px]">No</th>
                        <th class="sticky left-[50px] z-30 bg-white border-r px-4 py-2 min-w-[120px]">NIS</th>
                        <th class="sticky left-[170px] z-30 bg-white border-r px-4 py-2 min-w-[180px]">Nama Siswa</th>
                        <th class="sticky left-[350px] z-30 bg-white border-r px-4 py-2 min-w-[180px]">Unit Pendidikan</th>
                        <th class="sticky left-[530px] z-30 bg-white border-r px-4 py-2 min-w-[150px]">Kelas</th>
                        @foreach ($bulanList as $bulan)
                            <th class="bg-gray-50 px-4 py-2 whitespace-nowrap">{{ $bulan }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php $no = 1; @endphp
                    @foreach ($siswas as $siswa)
                        <tr class="border-b">
                            <td class="sticky left-0 z-20 bg-white border-r px-4 py-2 min-w-[50px] text-center">{{ $no++ }}</td>
                            <td class="sticky left-[50px] bg-white border-r px-4 py-2 min-w-[120px]">{{ $siswa->nis }}</td>
                            <td class="sticky left-[170px] z-20 bg-white border-r px-4 py-2 min-w-[180px]">{{ $siswa->nama }}</td>
                            <td class="sticky left-[350px] z-20 bg-white border-r px-4 py-2 min-w-[180px]">
                                {{ $siswa->unitPendidikan->namaUnit ?? '-' }}
                            </td>
                            <td class="sticky left-[530px] z-20 bg-white border-r px-4 py-2 min-w-[150px]">
                                {{ $siswa->kelas->nama_kelas ?? '-' }}
                            </td>
                            @foreach ($bulanList as $bulan)
                                @php $data = $pembayaranData[$siswa->id][$bulan]; @endphp
                                <td class="px-4 py-2 whitespace-nowrap">
                                    @if($data['terbayar'] > 0)
                                        Rp{{ number_format($data['terbayar'], 0, ',', '.') }}<br>
                                        <small class="text-gray-500">{{ \Carbon\Carbon::parse($data['tanggal_bayar'])->translatedFormat('d F Y') }}</small>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <!-- Pagination -->
<div class="mt-2 mb-4">
    {{ $rekapPaginated->links() }}
</div>

        </div>

        <div class="mt-6">
            {{ $siswas->appends(request()->query())->links() }}
        </div>
<script>
    document.getElementById('type').addEventListener('change', function () {
        const selectedValue = this.value;
        const url = new URL(window.location.href);

        // Set atau hapus parameter ?type=
        if (selectedValue) {
            url.searchParams.set('type', selectedValue);
        } else {
            url.searchParams.delete('type');
        }

        // Hapus paginasi jika ada
        url.searchParams.delete('page');

        // Redirect ke URL baru (otomatis reload)
        window.location.href = url.toString();
    });
</script>
           {{-- Script untuk Delay Submit pada Search --}}
        <script>
            let typingTimer;
            function delaySubmit() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => {
                    document.getElementById('filterForm').submit();
                }, 1000);
            }
        </script>
        <script>
    document.getElementById('jenis_pembayaran_id').addEventListener('change', function () {
        this.form.submit();
    });
</script>

    
        <script>
            document.getElementById('jenis_pembayaran_id').addEventListener('change', function () {
                const jenisId = this.value;
                const kelasSelect = document.getElementById('kelas');

                // Kosongkan dulu
                kelasSelect.innerHTML = '<option value="">-- Semua Kelas --</option>';

                if (jenisId) {
                    fetch({{ route('get.kelas.by.jenis') }}?jenis_pembayaran_id=${jenisId})
                        .then(response => response.json())
                        .then(data => {
                            data.forEach(kelas => {
                                const option = document.createElement('option');
                                option.value = kelas.id;
                                option.textContent = kelas.nama_kelas;
                                kelasSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Gagal mengambil data kelas:', error);
                        });
                }
            });
        </script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function () {
        function loadKelas(unitId, selectedKelas = null) {
            $('#kelas').empty().append('<option value="">-- Semua Kelas --</option>');
            if (unitId) {
                $.get("{{ route('yayasan.api.kelas') }}", { unit_id: unitId }, function (data) {
                    data.forEach(kelas => {
                        const isSelected = selectedKelas && selectedKelas.toString() === kelas.id.toString() ? 'selected' : '';
                        $('#kelas').append(<option value="${kelas.id}" ${isSelected}>${kelas.nama_kelas}</option>);
                    });
                });
            }
        }

        $('#unit').on('change', function () {
            const unitId = $(this).val();
            loadKelas(unitId);
            $('#filterForm').submit();
        });

        $('#kelas').on('change', function () {
            $('#filterForm').submit();
        });

        @if(request('unit_pendidikan_id'))
            loadKelas('{{ request('unit_pendidikan_id') }}', '{{ request('kelas') }}');
        @endif
    });
</script>

</x-layout-yayasan>