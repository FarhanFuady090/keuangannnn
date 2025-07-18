<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<x-layout-tupusat>
    <x-slot name="header">

    </x-slot>
    <html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Proses Transaksi Tagihan</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
</head>
<body class="bg-gray-100">
<div class="flex h-screen">
   <!-- Main Content -->
   <div class="flex-1 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="text-2xl font-bold">Proses Transaksi Tagihan Siswa Seluruh Unit Pendidikan</div>

                        <button class="flex items-center gap-2 bg-[#f7e600] text-black font-bold rounded px-4 py-2 shadow-md" type="button">
                          <i class="fas fa-dollar-sign"></i> Riwayat transaksi
                        </button>
                    </div>

        <div class="flex flex-col md:flex-row gap-6">
          <!-- Left side: Informasi Siswa and Total -->
          <div class="flex flex-col gap-6 w-full md:w-1/3">
            <div>
              <div class="bg-[#3ad25a] rounded-t-md px-4 py-2 text-black text-base md:text-lg font-semibold">
                Informasi Siswa
              </div>
              <div class="bg-[#d9d9d9] rounded-b-md p-4 text-sm md:text-base space-y-2">
                <div class="flex justify-between">
                  <span class="font-semibold">Tahun ajaran</span>
                  <span>{{ $tahunAjaranAktif->tahun ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="font-semibold">NISN</span>
                  <span>{{ $siswa->nisn }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="font-semibold">NIS</span>
                  <span>{{ $siswa->nis }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="font-semibold">Nama Siswa</span>
                  <span>{{ $siswa->nama }}</span>
                </div>
                <div class="flex justify-between">
                  <span class="font-semibold">Kelas</span>
                  <span>{{ $siswa->kelas->nama_kelas ?? '-' }}</span>
                </div>
              </div>
            </div>

            <div>
              <div class="bg-[#3498db] rounded-t-md px-4 py-2 text-black font-extrabold text-lg">
                TOTAL
              </div>
              <div
                class="bg-[#d9d9d9] rounded-b-md p-6 flex flex-col items-center gap-6"
              >
              <span id="total-bayar" class="text-3xl md:text-4xl font-normal">Rp. 0</span>
                <div class="flex gap-4">
                  <button onclick="window.history.back()"
                    class="bg-[#f56565] text-white rounded px-6 py-1 text-sm font-semibold"
                    type="button"
                  >
                    Kembali
                  </button>
                  <button
                    class="bg-[#fbbf24] text-black rounded px-6 py-1 text-sm font-semibold"
                    type="button"
                  >
                    Bayar
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Right side: Daftar Tagihan Siswa -->
          <div class="flex-1">
            <div class="bg-[#f7e600] rounded-t-md px-4 py-2 flex justify-between items-center text-black font-semibold text-base md:text-lg">
              <span>Daftar Tagihan Siswa</span>
              <select
                class="bg-white border border-black rounded-md text-black text-sm md:text-base px-3 py-1 focus:outline-none"
                aria-label="Jenis Tagihan"
                name="jenis-tagihan"
                id="jenis-tagihan"
              >
                <option value="">-- Pilih Jenis Tagihan --</option>
                @foreach($jenisPembayaran as $jenis)
                    <option value="{{ $jenis->idjenispembayaran }}">{{ $jenis->nama_pembayaran }}</option>
                @endforeach
              </select>
            </div>
            <div class="bg-[#d9d9d9] rounded-b-md overflow-auto max-h-[480px]">
              <table class="w-full text-sm md:text-base border-collapse">
                <thead>
                  <tr class="text-left text-black border-b border-black">
                    <th class="pl-4 pr-2 py-2 w-8">
                      <input type="checkbox" aria-label="Select all" />
                    </th>
                    <th class="pr-4 py-2 w-8 text-center">No.</th>
                    <th class="pr-4 py-2 w-8 text-center">Bulan</th>
                    <th class="pr-4 py-2 w-32 text-center">Jenis Tagihan</th>
                    <th class="pr-4 py-2 w-8 text-center">Tagihan</th>
                    <th class="pr-4 py-2 w-20 text-center">Status</th>
                    <th class="pr-4 py-2 w-20 text-center">Tgl. Bayar</th>
                    <th class="pr-4 py-2 w-8 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                @foreach($tagihan as $i => $t)
<tr class="border-b border-black">
    <td class="pl-4 pr-2 py-2">
        <input type="checkbox" class="tagihan-checkbox" data-nominal="{{ $t->nominal }}" />
    </td>
    <td class="pr-4 py-2 text-center">{{ $i + 1 }}</td>
    <td class="pr-4 py-2 text-center">{{ $t->bulan }}</td>
    <td class="pr-4 py-2 text-center">{{ $t->jenisPembayaran->nama_pembayaran }}</td>
    <td class="pr-4 py-2 text-center">Rp. {{ number_format($t->nominal, 0, ',', '.') }}</td>
    <td class="pr-4 py-2 text-center {{ $t->status == 'Lunas' ? 'text-[#3ad25a]' : 'text-[#e04e4e]' }}">
        {{ $t->status }}
    </td>
    <td class="pr-4 py-2 text-center">{{ $t->tanggal_bayar ?? '-' }}</td>
    <td class="pr-4 py-2 text-center text-[#a56a2a] cursor-pointer">
        <i class="fas fa-print"></i>
    </td>
</tr>
@endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </section>
    </main>
  </div>
  <script>
    function formatRupiah(angka) {
        return 'Rp. ' + angka.toLocaleString('id-ID');
    }

    function updateTotal() {
        let total = 0;
        document.querySelectorAll('.tagihan-checkbox:checked').forEach(function (checkbox) {
            total += parseInt(checkbox.dataset.nominal);
        });
        document.getElementById('total-bayar').textContent = formatRupiah(total);
    }

    document.querySelectorAll('.tagihan-checkbox').forEach(function (checkbox) {
        checkbox.addEventListener('change', updateTotal);
    });
</script>

</body>
</html>

  </x-layout-tupusat>