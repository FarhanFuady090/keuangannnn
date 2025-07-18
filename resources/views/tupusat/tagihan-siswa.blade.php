<x-layout-tupusat>
    <x-slot name="header">
    </x-slot>

    <html lang="en">
        <head>
            <meta charset="utf-8" />
            <meta content="width=device-width, initial-scale=1.0" name="viewport" />
            <title>Transaksi Tagihan Siswa Seluruh Unit Pendidikan</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
        </head>
        <body class="bg-gray-100"> 
            <div class="flex h-screen">
                <!-- Main Content -->
                <div class="flex-1 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="text-2xl font-bold">Transaksi Tagihan Siswa Seluruh Unit Pendidikan</div>
                    </div>
                    <div class="bg-white p-4 rounded shadow">
                        @if (session('success'))
                            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                                <strong>Sukses!</strong> {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="mb-4 p-4 bg-red-100 text-red-800 rounded">
                                <strong>Terjadi Kesalahan!</strong> {{ session('error') }}
                            </div>
                        @endif
                        @if (session('warning'))
                            <div class="mb-4 p-4 bg-yellow-100 text-yellow-800 rounded">
                                <strong>Peringatan!</strong> {{ session('warning') }}
                            </div>
                        @endif

                        <!-- Form Filter -->
                        <form class="bg-gray-300 rounded-lg p-4 grid grid-cols-1 md:grid-cols-3 gap-4 items-center" method="GET" action="{{ route('tupusat.tagihan-siswa.index') }}">
                            <div class="flex flex-col space-y-1 col-span-1 md:col-span-1 max-w-xs">
                                <label for="unit" class="text-sm font-medium text-black-500">Unit Pendidikan</label>
                                <select
                                    id="unit"
                                    name="unit"
                                    class="rounded-md px-3 py-2 border border-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400"
                                >
                                    <option value="">-- Pilih Unit Pendidikan --</option>
                                    @foreach($units as $unit)
                                        <option value="{{ $unit->id }}" {{ request('unit') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->namaUnit }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="flex flex-col space-y-1 col-span-1 md:col-span-1 max-w-xs">
                                <label for="kelas" class="text-sm font-medium text-black-500">Kelas</label>
                                <select
                                    id="kelas"
                                    name="kelas"
                                    class="rounded-md px-3 py-2 border border-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400"
                                >
                                    <option value="">-- Pilih Kelas --</option>
                                    <!-- Kelas akan diisi lewat AJAX -->
                                </select>
                            </div>

                            <div class="flex flex-col space-y-1 col-span-1 md:col-span-1 max-w-full md:max-w-none">
                                <label for="nisn" class="text-sm font-medium text-black-500">NIS/Nama Siswa</label>
                                <select
                                    id="siswa"
                                    name="siswa"
                                    class="rounded-md px-3 py-2 border border-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400"
                                >
                                    <option value="">-- Pilih NIS/Nama Siswa --</option>
                                    <!-- Siswa akan diisi lewat AJAX -->
                                </select>
                            </div>
                            
                            {{-- Tombol submit filter --}}
                            <div class="col-span-1 md:col-span-3 flex justify-end mt-4">
                                <button
                                    type="submit"
                                    id="btnProses"
                                    class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold text-sm rounded px-6 py-2"
                                >
                                    Proses Transaksi
                                </button>
                            </div>
                        </form>

                        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

                        <script>
                            // Ketika Unit Pendidikan dipilih
                            $('#unit').change(function() {
                                var unit_id = $(this).val();
                                if (unit_id) {
                                    $.ajax({
                                        url: '/tupusat/api/kelas-by-unit/' + unit_id, // Pastikan URL sudah benar
                                        type: 'GET',
                                        success: function(response) {
                                            $('#kelas').empty().append('<option value="">-- Pilih Kelas --</option>');
                                            $.each(response, function(index, kelas) {
                                                $('#kelas').append('<option value="' + kelas.id + '">' + kelas.nama_kelas + '</option>');
                                            });
                                        },
                                        error: function(xhr, status, error) {
                                            console.error("AJAX error: ", error);
                                        }
                                    });
                                } else {
                                    $('#kelas').empty().append('<option value="">-- Pilih Kelas --</option>');
                                }
                                // Kosongkan dropdown siswa
                                $('#siswa').empty().append('<option value="">-- Pilih Siswa --</option>');
                            });

                            // Ketika Kelas dipilih
                            $('#kelas').change(function() {
                                var kelas_id = $(this).val();
                                if (kelas_id) {
                                    $.ajax({
                                        url: '/tupusat/api/siswa-by-kelas/' + kelas_id, // Pastikan URL sudah benar
                                        type: 'GET',
                                        success: function(response) {
                                            $('#siswa').empty().append('<option value="">-- Pilih Siswa --</option>');
                                            $.each(response, function(index, siswa) {
                                                $('#siswa').append('<option value="' + siswa.id + '">' + siswa.nis + ' - ' + siswa.nama + '</option>');
                                            });
                                        },
                                        error: function(xhr, status, error) {
                                            console.error("AJAX error: ", error);
                                        }
                                    });
                                } else {
                                    $('#siswa').empty().append('<option value="">-- Pilih Siswa --</option>');
                                }
                            });

                            // Redirect ke halaman detail tagihan saat tombol "Proses Transaksi" ditekan
                            $('#btnProses').click(function(event) {
                                event.preventDefault();
                                const siswaId = $('#siswa').val(); // Ambil nilai siswa yang dipilih
                                if (siswaId) {
                                    // Jika siswa dipilih, redirect ke halaman detail tagihan
                                    window.location.href = '/tupusat/tagihan/' + siswaId;
                                } else {
                                    alert('Pilih siswa terlebih dahulu!');
                                }
                            });
                        </script>

                    </div>
                </div>
            </div>
        </body>
    </html>
</x-layout-tupusat>
