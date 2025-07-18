<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<x-layout-tupusat>
    <x-slot name="header">

    </x-slot>
    <html lang="en">
        <head>
            <meta charset="utf-8" />
            <meta content="width=device-width, initial-scale=1.0" name="viewport" />
            <title>Tabungan Siswa Seluruh Unit Pendidikan</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet" />
        </head>
        <body class="bg-gray-100"> 
              <div class="flex h-screen">
                <!-- Main Content -->
                <div class="flex-1 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div class="text-2xl font-bold">Tabungan Siswa Seluruh Unit Pendidikan</div>
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
                        <div class="flex justify-end items-center mb-6">
                          <button type="button" class="flex items-center gap-2 bg-yellow-400 text-black font-bold text-sm rounded px-4 py-2">
                            <i class="fas fa-dollar-sign"></i> Riwayat transaksi
                          </button>
                        </div>
                        <form
        class="bg-gray-300 rounded-lg p-4 grid grid-cols-1 md:grid-cols-3 gap-4 items-center"
        action="#"
      >
        <div class="flex flex-col space-y-1 col-span-1 md:col-span-1 max-w-xs">
          <label for="unit" class="text-sm font-medium text-black-500">Unit Pendidikan</label>
          <select
            id="unit"
            name="unit"
            class="rounded-md px-3 py-2 border border-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400"
          >
            <option value="" disabled selected></option>
          </select>
        </div>

        <div class="flex flex-col space-y-1 col-span-1 md:col-span-1 max-w-xs">
          <label for="kelas" class="text-sm font-medium text-black-500">Kelas</label>
          <select
            id="kelas"
            name="kelas"
            class="rounded-md px-3 py-2 border border-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400"
          >
            <option value="" disabled selected></option>
          </select>
        </div>

        <div class="flex flex-col space-y-1 col-span-1 md:col-span-1 max-w-full md:max-w-none">
          <label for="nisn" class="text-sm font-medium text-black-500">NISN/NIS/Nama Siswa</label>
          <input
            id="nisn"
            name="nisn"
            type="text"
            class="rounded-md px-3 py-2 border border-transparent focus:outline-none focus:ring-2 focus:ring-yellow-400 w-full"
          />
        </div>
      </form>

      <div class="flex justify-end mt-6">
        <button
          type="submit"
          class="bg-yellow-500 hover:bg-yellow-600 text-black font-bold text-sm rounded px-6 py-2"
        >
          Proses Transaksi
        </button>
                    </div>

      
      </div>
  </div>
</body>
</html>

  </x-layout-tupusat>