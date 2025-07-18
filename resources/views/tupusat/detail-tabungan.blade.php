<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<x-layout-tupusat>
    <x-slot name="header">

    </x-slot>
    <html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tabungan Siswa</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
  />
</head>
<body class="bg-white font-sans">
  <div class="flex min-h-screen">
    <!-- Main content -->
    <main class="flex-1 p-6">
      <header class="flex justify-between items-center mb-6">
        <div class="text-2xl font-bold">Proses Transaksi Tabungan Siswa SMP</div>
      </header>

      <div class="flex flex-col md:flex-row gap-6">
        <section class="flex flex-col gap-6 w-full md:w-[360px]">
          <div>
            <h2 class="bg-[#40c057] text-black font-semibold px-4 py-2 rounded-t-md">
              Informasi Siswa
            </h2>
            <div class="bg-gray-300 rounded-b-md p-4 space-y-2 text-sm text-black">
              <div class="flex justify-between">
                <span>Tahun ajaran</span>
                <span>2024/2025</span>
              </div>
              <div class="flex justify-between">
                <span>NISN</span>
                <span>31287318582151341</span>
              </div>
              <div class="flex justify-between">
                <span>NIS</span>
                <span>3138826728</span>
              </div>
              <div class="flex justify-between">
                <span>Nama Siswa</span>
                <span>M. Halili</span>
              </div>
              <div class="flex justify-between">
                <span>Kelas</span>
                <span>7 A</span>
              </div>
            </div>
          </div>

          <div>
            <h2 class="bg-[#339af0] text-black font-semibold px-4 py-2 rounded-t-md">
              Aksi
            </h2>
            <div class="bg-gray-300 rounded-b-md p-6 flex flex-col gap-4">
              <button
                class="bg-[#40c057] text-white font-bold py-3 rounded-md flex items-center justify-center gap-3"
              >
                <i class="fas fa-money-bill-wave fa-lg"></i>
                Setor
              </button>
              <button
                class="bg-[#fab005] text-white font-bold py-3 rounded-md flex items-center justify-center gap-3"
              >
                <i class="fas fa-file-invoice-dollar fa-lg"></i>
                Tarik
              </button>
            </div>
          </div>
        </section>

        <section
          class="bg-gray-300 rounded-md flex-1 p-6 flex flex-col justify-between"
        >
          <div></div>
          <div class="text-black font-bold text-lg">TOTAL</div>
        </section>
      </div>
    </main>
  </div>
</body>
</html>

  </x-layout-tupusat>