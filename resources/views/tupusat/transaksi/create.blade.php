<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<x-layout-tupusat>
    <x-slot name="header"></x-slot>

    <div class="max-w-2xl mx-auto mt-10 px-6">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">
                Tambah Transaksi untuk: <span class="text-green-600">{{ $tabungan->siswa->nama }}</span>
            </h3>

            <form action="{{ route('tupusat.transaksi.store', $tabungan->id) }}" method="POST" class="space-y-5">
                @csrf

                <!-- Jenis Transaksi -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi</label>
                    <select name="jenis_transaksi"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-green-300"
                            required>
                        <option value="Setoran">Setoran</option>
                        <option value="Penarikan">Penarikan</option>
                    </select>
                </div>

          
                <div class="flex flex-col space-y-1 w-full">

                      <!-- Jumlah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input type="number" name="jumlah"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-green-300"
                            required>
                    </div>
                    <p class="text-sm text-gray-500 ml-[5%]">
                        Angka ditulis tanpa tanda baca contoh 1000000.
                        </p>
                 </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                    <textarea name="keterangan"
                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-green-300"
                              rows="3"></textarea>
                </div>

                <input type="hidden" name="petugas" value="{{ Auth::user()->username }}">

              <!-- Tombol Simpan & Batal -->
        <div class="flex justify-between">
            <a href="{{ route('tabungan.show', $tabungan->id) }}"
            class="bg-red-300 hover:bg-red-400 text-red-800 font-semibold py-2 px-4 rounded-md transition duration-200">
                Batal
            </a>
            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                Simpan Transaksi
            </button>
        </div>

            </form>
        </div>
    </div>
</x-layout-tupusat>
