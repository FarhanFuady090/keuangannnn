<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<x-layout-tupusat>
    <x-slot name="header"></x-slot>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-4xl mx-auto mt-4 px-6">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="max-w-4xl mx-auto mt-4 px-6">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif
<div class="max-w-7xl mx-auto mt-10 px-6">
    <div class="flex justify-center">
        <div class="w-full max-w-xl bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-2xl font-semibold text-gray-800 mb-6">
                Edit Setoran Awal untuk: <span class="text-green-600">{{ $tabungan->siswa->nama }}</span>
            </h3>

            <form action="{{ route('tupusat.tabungan.update', $tabungan->id) }}" method="POST" class="space-y-5" id="editForm">
                @csrf
                @method('PUT')

                <!-- Jumlah -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Saldo Awal</label>
                    <input type="number" name="saldo_awal" id="saldo_awal" required value="{{ $tabungan->saldo_awal }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-green-300"
                           onchange="trackChanges()" oninput="trackChanges()">
                </div>

                <input type="hidden" name="updated_by" value="{{ Auth::user()->username }}">

                <!-- Tombol -->
                <div class="flex justify-between">
                    <a href="{{ route('tabungan.show', $tabungan->id) }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-md transition duration-200 inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                        <i class="fas fa-save mr-2"></i>Update Tabungan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</x-layout-tupusat>