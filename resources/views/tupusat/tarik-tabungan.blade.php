<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<x-layout-tupusat>
    <x-slot name="header">

    </x-slot>

    <html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>TU UNIT</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
  />
</head>
<body class="bg-white font-sans">
  <div class="flex flex-col min-h-screen">
    <div class="flex flex-1">
      <!-- Main content -->
      <main class="flex-1 p-6">
      <div class="text-2xl font-bold mb-6">Proses Transaksi Tarik Tabungan Siswa SMP</div>
        <section
          class="bg-gray-300 rounded-lg p-6 max-w-4xl mx-auto"
          aria-label="Nominal input form"
        >
          <form class="flex flex-col gap-6">
            <div class="flex items-center gap-4">
              <label
                for="nominal"
                class="text-black text-sm w-20 select-none"
                >Nominal</label
              >
              <input
                id="nominal"
                name="nominal"
                type="text"
                class="flex-1 rounded-md border border-gray-300 px-4 py-2 text-black text-sm focus:outline-none focus:ring-2 focus:ring-green-600"
              />
            </div>
            <div class="flex justify-center gap-6">
              <button
                type="button"
                class="bg-red-500 hover:bg-red-600 text-white font-normal text-base rounded w-64 py-3"
              >
                Kembali
              </button>
              <button
                type="submit"
                class="bg-yellow-400 hover:bg-yellow-500 text-black font-bold text-base rounded w-64 py-3 flex items-center justify-center gap-2"
              >
                <i class="fas fa-credit-card"></i>
                Tarik
              </button>
            </div>
          </form>
        </section>
      </main>
    </div>
  </div>
</body>
</html>
  </x-layout-tupusat>