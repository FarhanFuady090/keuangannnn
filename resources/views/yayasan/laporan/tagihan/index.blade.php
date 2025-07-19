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

@if(empty(request('jenis_pembayaran_id')))

<!-- Unit Pendidikan -->
<div class="flex flex-col relative">
    <label for="unitpendidikan_id" class="text-sm font-medium text-gray-700 mb-1">Unit Pendidikan</label>
    <div class="relative">
        <select name="unitpendidikan_id" id="unit_pendidikan_id"
            class="min-w-[200px] px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-500 w-full">
            <option value="">-- Semua Unit --</option>
            @foreach($unitPendidikanList as $unit)
                <option value="{{ $unit->id }}" {{ request('unitpendidikan_id') == $unit->id ? 'selected' : '' }}>
                    {{ $unit->namaUnit }}
                </option>
            @endforeach
        </select>
        
        <!-- Loading indicator untuk unit -->
        <div id="loading-unit" class="hidden absolute right-3 top-1/2 transform -translate-y-1/2">
            <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Kelas -->
<div class="flex flex-col relative">
    <label for="kelas" class="text-sm font-medium text-gray-700 mb-1">Kelas</label>
    <div class="relative">
        <select name="kelas" id="kelas"
            class="min-w-[200px] px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300 focus:border-blue-500 w-full">
            <option value="">-- Semua Kelas --</option>
        </select>
        
        <!-- Loading indicator untuk kelas -->
        <div id="loading-kelas" class="hidden absolute right-3 top-1/2 transform -translate-y-1/2">
            <svg class="animate-spin h-4 w-4 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
</div>
@endif


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

<div class="relative overflow-x-auto mb-2">
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
<!-- Container utama -->
<div class="w-full mb-4">
    <!-- Paginasi rekap per jenis (tampilan seperti bawah) -->
    <div class="flex justify-between items-center">
        <div>
            {{ $paginatedRekapPerJenis->appends(request()->query())->links() }}
        </div>
    </div>

        <!-- Pencarian -->
    <div class="flex justify-center mt-6">
        <div class="flex flex-col items-center w-full max-w-xl px-4">
            <label for="search" class="text-sm font-semibold text-gray-700 mb-2">Cari Nama / NIS</label>
            <input
                type="text"
                name="search"
                id="search"
                value="{{ $search }}"
                class="w-full border border-gray-300 rounded-xl px-4 py-3 text-base shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                placeholder="Masukkan nama siswa atau NIS..."
                oninput="delaySubmit()"
            >
        </div>
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
                    @forelse ($siswas as $siswa)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="sticky left-0 z-20 bg-white border-r px-4 py-2 min-w-[50px] text-center">{{ ($siswas->currentPage() - 1) * $siswas->perPage() + $loop->iteration }}</td>
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
                                        <div class="text-green-600 font-medium">
                                            Rp{{ number_format($data['terbayar'], 0, ',', '.') }}
                                        </div>
                                        <small class="text-gray-500">{{ \Carbon\Carbon::parse($data['tanggal_bayar'])->translatedFormat('d F Y') }}</small>
                                    @else
                                        <span class="text-gray-400 text-sm">-</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="13" class="px-4 py-8 text-center text-gray-500">
                            {{-- <td colspan="{{ 5 + count($bulanList) }}" class="px-4 py-8 text-center text-gray-500"> --}}
                                <div class="space-y-2">
                                    <div class="text-lg">📄</div>
                                    <div class="font-medium">Data tidak ditemukan</div>
                                    @if(request()->hasAny(['unit_pendidikan_id', 'kelas', 'search']))
                                        <div class="text-sm">Coba ubah atau <a href="{{ request()->url() }}" class="text-blue-600 hover:underline">reset filter</a></div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $siswas->appends(request()->query())->links() }}
        </div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    // Function untuk load unit pendidikan berdasarkan jenis pembayaran
    function loadUnitPendidikan(jenisPembayaranId, selectedUnit = null) {
        const unitSelect = $('[name="unitpendidikan_id"]'); // Gunakan selector berdasarkan name
        
        // Show loading
        $('#loading-unit').removeClass('hidden');
        
        // Simpan options default
        const defaultOption = '<option value="">-- Semua Unit --</option>';
        
        // Clear existing options
        unitSelect.empty().append(defaultOption);
        
        if (jenisPembayaranId) {
            $.ajax({
                url: "{{ route('yayasan.api.unit-by-payment') }}", // Route baru yang perlu dibuat
                method: 'GET',
                data: { jenis_pembayaran_id: jenisPembayaranId },
                success: function(data) {
                    console.log('Unit data received:', data); // Debug log
                    
                    if (data && data.length > 0) {
                        data.forEach(unit => {
                            const isSelected = selectedUnit && selectedUnit.toString() === unit.id.toString() ? 'selected' : '';
                            // Sesuaikan dengan struktur data yang benar
                            const unitName = unit.namaUnit || unit.nama_unit || unit.name;
                            unitSelect.append(`<option value="${unit.id}" ${isSelected}>${unitName}</option>`);
                        });
                    } else {
                        unitSelect.append('<option value="" disabled>Tidak ada unit untuk jenis pembayaran ini</option>');
                    }
                    
                    // Hide loading
                    $('#loading-unit').addClass('hidden');
                    
                    // Reset kelas jika unit berubah
                    $('#kelas').empty().append('<option value="">-- Semua Kelas --</option>');
                },
                error: function(xhr, status, error) {
                    console.error('Gagal mengambil data unit pendidikan:', error);
                    console.error('Response:', xhr.responseText); // Debug error
                    unitSelect.append('<option value="" disabled>Gagal memuat unit pendidikan</option>');
                    $('#loading-unit').addClass('hidden');
                }
            });
        } else {
            // Jika tidak ada jenis pembayaran dipilih, load semua unit
            $.ajax({
                url: "{{ route('yayasan.api.all-units') }}", // Route untuk semua unit
                method: 'GET',
                success: function(data) {
                    console.log('All units data received:', data); // Debug log
                    
                    if (data && data.length > 0) {
                        data.forEach(unit => {
                            const isSelected = selectedUnit && selectedUnit.toString() === unit.id.toString() ? 'selected' : '';
                            const unitName = unit.namaUnit || unit.nama_unit || unit.name;
                            unitSelect.append(`<option value="${unit.id}" ${isSelected}>${unitName}</option>`);
                        });
                    }
                    $('#loading-unit').addClass('hidden');
                },
                error: function(xhr, status, error) {
                    console.error('Gagal mengambil data semua unit:', error);
                    console.error('Response:', xhr.responseText); // Debug error
                    $('#loading-unit').addClass('hidden');
                }
            });
        }
    }

    // Function untuk load kelas via AJAX
    function loadKelas(unitId, selectedKelas = null) {
        const kelasSelect = $('#kelas');
        
        // Show loading
        $('#loading-kelas').removeClass('hidden');
        
        // Clear existing options
        kelasSelect.empty().append('<option value="">-- Semua Kelas --</option>');
        
        if (unitId) {
            $.ajax({
                url: "{{ route('yayasan.api.kelas') }}",
                method: 'GET',
                data: { unit_id: unitId },
                success: function(data) {
                    data.forEach(kelas => {
                        const isSelected = selectedKelas && selectedKelas.toString() === kelas.id.toString() ? 'selected' : '';
                        kelasSelect.append(`<option value="${kelas.id}" ${isSelected}>${kelas.nama_kelas}</option>`);
                    });
                    
                    // Hide loading
                    $('#loading-kelas').addClass('hidden');
                },
                error: function(xhr, status, error) {
                    console.error('Gagal mengambil data kelas:', error);
                    $('#loading-kelas').addClass('hidden');
                }
            });
        } else {
            $('#loading-kelas').addClass('hidden');
        }
    }

    // Function untuk update URL parameters tanpa reload
    function updateUrlParams(jenisPembayaranId, unitId, kelasId) {
        const url = new URL(window.location.href);
        
        if (jenisPembayaranId) {
            url.searchParams.set('jenis_pembayaran_id', jenisPembayaranId);
        } else {
            url.searchParams.delete('jenis_pembayaran_id');
        }
        
        if (unitId) {
            url.searchParams.set('unit_pendidikan_id', unitId);
        } else {
            url.searchParams.delete('unit_pendidikan_id');
        }
        
        if (kelasId) {
            url.searchParams.set('kelas', kelasId);
        } else {
            url.searchParams.delete('kelas');
        }
        
        // Update URL tanpa reload
        window.history.replaceState({}, '', url.toString());
    }

    // Event handler untuk jenis pembayaran
    $('#jenis_pembayaran_id').on('change', function() {
        const jenisPembayaranId = $(this).val();
        
        // Load unit pendidikan berdasarkan jenis pembayaran
        loadUnitPendidikan(jenisPembayaranId);
        
        // Reset kelas
        $('#kelas').empty().append('<option value="">-- Semua Kelas --</option>');
        
        // Update URL parameters
        updateUrlParams(jenisPembayaranId, '', '');
        
        // Submit form untuk update data utama
        setTimeout(() => {
            $('#filterForm').submit();
        }, 500); // Increase delay untuk memastikan AJAX selesai
    });

    // Event handler untuk unit pendidikan - gunakan selector yang benar
    $('#unit_pendidikan_id, [name="unitpendidikan_id"]').on('change', function() {
        const unitId = $(this).val();
        const jenisPembayaranId = $('#jenis_pembayaran_id').val();
        
        // Load kelas berdasarkan unit yang dipilih
        loadKelas(unitId);
        
        // Update URL parameters
        updateUrlParams(jenisPembayaranId, unitId, '');
        
        // Submit form untuk update data utama
        setTimeout(() => {
            $('#filterForm').submit();
        }, 500); // Increase delay untuk memastikan AJAX selesai
    });

    // Event handler untuk kelas
    $('#kelas').on('change', function() {
        const jenisPembayaranId = $('#jenis_pembayaran_id').val();
        const unitId = $('#unit_pendidikan_id').val();
        const kelasId = $(this).val();
        
        // Update URL parameters
        updateUrlParams(jenisPembayaranId, unitId, kelasId);
        
        // Submit form
        $('#filterForm').submit();
    });

    // Restore data saat page load
    @if(request('jenis_pembayaran_id'))
        loadUnitPendidikan('{{ request('jenis_pembayaran_id') }}', '{{ request('unitpendidikan_id') }}');
        
        @if(request('unitpendidikan_id'))
            // Delay untuk memastikan unit sudah di-load dulu
            setTimeout(() => {
                loadKelas('{{ request('unitpendidikan_id') }}', '{{ request('kelas') }}');
            }, 800);
        @endif
    @else
        // Jika tidak ada jenis pembayaran dipilih, load semua unit
        loadUnitPendidikan(null, '{{ request('unitpendidikan_id') }}');
        
        @if(request('unitpendidikan_id'))
            setTimeout(() => {
                loadKelas('{{ request('unitpendidikan_id') }}', '{{ request('kelas') }}');
            }, 500);
        @endif
    @endif
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
    // Handler untuk dropdown lainnya
    document.getElementById('type')?.addEventListener('change', function () {
        const selectedValue = this.value;
        const url = new URL(window.location.href);

        if (selectedValue) {
            url.searchParams.set('type', selectedValue);
        } else {
            url.searchParams.delete('type');
        }

        url.searchParams.delete('page');
        window.location.href = url.toString();
    });

    document.getElementById('jenis_pembayaran_id')?.addEventListener('change', function () {
        this.form.submit();
    });
</script>

<script>
    function toggleFilters() {
        const jenisPembayaran = document.getElementById('jenis_pembayaran_id').value;
        const unitSection = document.getElementById('unit-section');
        const kelasSection = document.getElementById('kelas-section');

        if (jenisPembayaran !== '') {
            unitSection.classList.add('hidden');
            kelasSection.classList.add('hidden');
        } else {
            unitSection.classList.remove('hidden');
            kelasSection.classList.remove('hidden');
        }
    }

    document.getElementById('jenis_pembayaran_id').addEventListener('change', toggleFilters);

    // Jalankan saat halaman pertama kali load
    window.addEventListener('DOMContentLoaded', toggleFilters);
</script>



</x-layout-yayasan>