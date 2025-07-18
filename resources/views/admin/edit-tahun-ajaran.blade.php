<x-layout-admin>
    <x-slot name="header"></x-slot>

    <div class="max-w-4xl mx-auto mt-10">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-2xl font-semibold mb-6">Ubah Data Tahun Ajaran</h2>

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if ($errors->any())
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: '{{ $errors->first() }}',
        });
    </script>
@endif


            <form action="{{ route('admin.updateTahunAjaran', $tahunajaran->id) }}" method="POST" id="tahunAjaranForm">
                @csrf
                <div class="space-y-6">
                    <!-- Tahun Ajaran -->
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium text-gray-700">Tahun Ajaran:</label>
                        <input id="tahunAjaranInput" class="w-2/3 p-2 border border-gray-300 rounded-md" type="text" name="tahun_ajaran" value="{{ $tahunajaran->tahun_ajaran }}" required />
                    </div>

                    <!-- Awal -->
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium text-gray-700">Awal:</label>
                        <input id="awalInput" class="w-2/3 p-2 border border-gray-300 rounded-md" type="date" name="awal" value="{{ $tahunajaran->awal }}" required />
                    </div>

                    <!-- Akhir -->
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium text-gray-700">Akhir:</label>
                        <input id="akhirInput" class="w-2/3 p-2 border border-gray-300 rounded-md" type="date" name="akhir" value="{{ $tahunajaran->akhir }}" required />
                    </div>

                    <!-- Semester -->
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium text-gray-700">Semester:</label>
                        <input id="semesterInput" class="w-2/3 p-2 border border-gray-300 rounded-md bg-gray-100" type="text" value="{{ $tahunajaran->semester }}" readonly />
                    </div>

                    <!-- Status -->
                    <div class="flex items-center">
                        <label class="w-1/3 text-sm font-medium text-gray-700">Status:</label>
                        <select class="w-2/3 p-2 border border-gray-300 rounded-md" name="status">
                            <option value="Aktif" {{ $tahunajaran->status == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="Non Aktif" {{ $tahunajaran->status == 'Non Aktif' ? 'selected' : '' }}>Non Aktif</option>
                        </select>
                    </div>
                </div>

                <!-- Button Kembali dan Perbarui -->
                <div class="flex justify-end mt-6 space-x-4">
                    <a href="{{ route('admin.manage-tahun-ajaran') }}">
                        <button class="bg-red-500 text-white px-6 py-2 rounded-md hover:bg-red-700" type="button">Kembali</button>
                    </a>

                    <button class="bg-green-500 text-white px-6 py-2 rounded-md hover:bg-green-700" type="submit">Perbarui</button>
                </div>
            </form>
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    const awalInput = document.getElementById("awalInput");
                    const akhirInput = document.getElementById("akhirInput");
                    const tahunAjaranInput = document.getElementById("tahunAjaranInput");
                    const semesterInput = document.getElementById("semesterInput");

                    awalInput.addEventListener("change", function () {
                        const awalDate = new Date(awalInput.value);
                        const month = awalDate.getMonth() + 1;
                        const year = awalDate.getFullYear();

                        let semester = "";
                        let akhir = "";
                        let tahunAjaran = "";

                        if (month >= 7 && month <= 12) {
                            semester = "Ganjil";
                            akhir = new Date(year, 11, 31);
                            tahunAjaran = `${year}/${year + 1}`;
                        } else if (month >= 1 && month <= 6) {
                            semester = "Genap";
                            akhir = new Date(year, 5, 30);
                            tahunAjaran = `${year - 1}/${year}`;
                        } else {
                            semester = "Tidak valid";
                            Swal.fire({
                                icon: 'error',
                                title: 'Tanggal tidak valid',
                                text: 'Tanggal awal tidak sesuai semester Ganjil atau Genap',
                            });
                        }

                        semesterInput.value = semester;
                        tahunAjaranInput.value = tahunAjaran;
                        akhirInput.value = akhir.toISOString().split("T")[0];
                    });
                });
            </script>

        </div>
    </div>
</x-layout-admin>
