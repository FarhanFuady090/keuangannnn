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
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap"
    rel="stylesheet"
  />
  <style>
    body {
      font-family: 'Inter', sans-serif;
    }
  </style>
</head>
<body class="bg-white">
  <div class="flex min-h-[calc(100vh-64px)]">
    <main class="flex-1 p-6">
    <div class="text-2xl font-bold mb-6">Proses Transaksi Setor Tabungan Siswa SMP</div>
      <form
        class="bg-[#d3d3d3] rounded-lg p-6 max-w-4xl mx-auto"
        action="#"
        method="POST"
      >
        <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-6">
          <label
            for="nominal"
            class="block text-black text-sm w-20 shrink-0 select-text"
            >Nominal</label
          >
          <input
            id="nominal"
            name="nominal"
            type="text"
            class="flex-1 rounded-md border border-gray-300 px-4 py-2 text-black text-sm focus:outline-none focus:ring-2 focus:ring-[#2f914d]"
          />
        </div>
        <div class="flex justify-center gap-6">
          <button
            type="button"
            class="bg-[#f75a5a] text-white font-normal text-base rounded-md px-16 py-3"
          >
            Kembali
          </button>
          <button
            type="submit"
            class="bg-[#3fdb6a] text-black font-bold text-base rounded-md px-16 py-3 flex items-center justify-center gap-2"
          >
            <i class="fas fa-money-bill-wave"></i>
            <span>Setor</span>
            <i class="fas fa-arrow-down text-xs"></i>
          </button>
        </div>
      </form>
    </main>
  </div>
</body>
</html>
  </x-layout-tupusat>