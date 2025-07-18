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

    <div class="max-w-4xl mx-auto mt-10 px-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Form Edit -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-2xl font-semibold text-gray-800 mb-6">
                    Edit Transaksi untuk: <span class="text-green-600">{{ $transaksi->tabungan->siswa->nama }}</span>
                </h3>

                <form action="{{ route('tupusat.transaksi.update', $transaksi->id) }}" method="POST" class="space-y-5" id="editForm">
                    @csrf
                    @method('PUT')

                    <!-- Jenis Transaksi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Transaksi</label>
                        <select name="jenis_transaksi" id="jenis_transaksi"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-green-300"
                                required onchange="trackChanges()">
                            <option value="Setoran" {{ $transaksi->jenis_transaksi == 'Setoran' ? 'selected' : '' }}>Setoran</option>
                            <option value="Penarikan" {{ $transaksi->jenis_transaksi == 'Penarikan' ? 'selected' : '' }}>Penarikan</option>
                        </select>
                    </div>

                    <!-- Jumlah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input type="number" name="jumlah" id="jumlah" required value="{{ $transaksi->jumlah }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-green-300"
                               onchange="trackChanges()" oninput="trackChanges()">
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
                        <textarea name="keterangan" id="keterangan"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring focus:ring-green-300"
                                  rows="3" onchange="trackChanges()" oninput="trackChanges()">{{ $transaksi->keterangan }}</textarea>
                    </div>

                    <input type="hidden" name="updated_by" value="{{ Auth::user()->username }}">
                    {{-- <input type="hidden" name="information" value="{{ $user->information ?? '' }}">
                    <input type="hidden" name="redirect_back" value="true"> --}}

                    <!-- Tombol Update dan Cancel -->
                    <div class="flex justify-between">
                        <button type="button" onclick="goBack()"
                                class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </button>
                        <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md transition duration-200">
                            <i class="fas fa-save mr-2"></i>Update Transaksi
                        </button>
                    </div>
                </form>
            </div>

            <!-- Informasi Perubahan -->
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">
                    <i class="fas fa-info-circle text-blue-500 mr-2"></i>
                    Informasi Perubahan Data
                </h3>

                <!-- Data Asli -->
                <div class="mb-6">
                    <h4 class="text-lg font-medium text-gray-700 mb-3 border-b pb-2">Data Asli</h4>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jenis Transaksi:</span>
                            <span class="font-medium text-gray-800" id="original-jenis">{{ $transaksi->jenis_transaksi }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Jumlah:</span>
                            <span class="font-medium text-gray-800" id="original-jumlah">Rp {{ number_format($transaksi->jumlah, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Keterangan:</span>
                            <span class="font-medium text-gray-800" id="original-keterangan">{{ $transaksi->keterangan ?: '-' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tanggal Dibuat:</span>
                            <span class="font-medium text-gray-800">{{ $transaksi->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Dibuat Oleh:</span>
                            <span class="font-medium text-gray-800">{{ $transaksi->petugas }}</span>
                        </div>
                        @if($transaksi->updated_at != $transaksi->created_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Terakhir Diupdate:</span>
                            <span class="font-medium text-gray-800">{{ $transaksi->updated_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Diupdate Oleh:</span>
                            <span class="font-medium text-gray-800">{{ $transaksi->updated_by ?? '-' }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Perubahan yang Akan Dilakukan -->
                <div id="changes-section" style="display: none;">
                    <h4 class="text-lg font-medium text-orange-600 mb-3 border-b pb-2">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Perubahan yang Akan Dilakukan
                    </h4>
                    <div id="changes-list" class="space-y-2 text-sm">
                        <!-- Perubahan akan ditampilkan di sini -->
                    </div>
                </div>

                <!-- Ringkasan Dampak -->
                <div id="impact-section" style="display: none;" class="mt-6 p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                    <h5 class="text-sm font-medium text-yellow-800 mb-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Dampak Perubahan
                    </h5>
                    <div id="impact-list" class="text-xs text-yellow-700">
                        <!-- Dampak akan ditampilkan di sini -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Data asli untuk perbandingan
        const originalData = {
            jenis_transaksi: `{{ $transaksi->jenis_transaksi }}`,
            jumlah: {{ $transaksi->jumlah }},
            keterangan: `{{ addslashes($transaksi->keterangan ?? '') }}`
        };

        function trackChanges() {
            const currentData = {
                jenis_transaksi: document.getElementById('jenis_transaksi').value,
                jumlah: parseFloat(document.getElementById('jumlah').value) || 0,
                keterangan: document.getElementById('keterangan').value.trim()
            };

            console.log('Original:', originalData);
            console.log('Current:', currentData);

            const changes = [];
            const impacts = [];

            // Cek perubahan jenis transaksi
            if (currentData.jenis_transaksi !== originalData.jenis_transaksi) {
                changes.push({
                    field: 'Jenis Transaksi',
                    from: originalData.jenis_transaksi,
                    to: currentData.jenis_transaksi
                });
                impacts.push('Perubahan jenis transaksi akan mempengaruhi perhitungan saldo tabungan');
            }

            // Cek perubahan jumlah
            if (currentData.jumlah !== originalData.jumlah) {
                const difference = currentData.jumlah - originalData.jumlah;
                changes.push({
                    field: 'Jumlah',
                    from: formatRupiah(originalData.jumlah),
                    to: formatRupiah(currentData.jumlah),
                    difference: difference
                });
                
                if (difference > 0) {
                    impacts.push(`Saldo akan bertambah Rp ${formatRupiah(Math.abs(difference))}`);
                } else {
                    impacts.push(`Saldo akan berkurang Rp ${formatRupiah(Math.abs(difference))}`);
                }
            }

            // Cek perubahan keterangan
            if (currentData.keterangan !== originalData.keterangan) {
                changes.push({
                    field: 'Keterangan',
                    from: originalData.keterangan || '-',
                    to: currentData.keterangan || '-'
                });
            }

            // Tampilkan perubahan
            displayChanges(changes, impacts);
        }

        function displayChanges(changes, impacts) {
            const changesSection = document.getElementById('changes-section');
            const changesList = document.getElementById('changes-list');
            const impactSection = document.getElementById('impact-section');
            const impactList = document.getElementById('impact-list');

            if (changes.length > 0) {
                changesSection.style.display = 'block';
                
                let changesHtml = '';
                changes.forEach(change => {
                    let changeText = `
                        <div class="p-3 bg-orange-50 rounded border-l-4 border-orange-400">
                            <div class="font-medium text-orange-800">${change.field}</div>
                            <div class="text-orange-600">
                                <span class="line-through">${change.from}</span> 
                                <i class="fas fa-arrow-right mx-2"></i> 
                                <span class="font-medium">${change.to}</span>
                            </div>
                    `;
                    
                    if (change.difference !== undefined) {
                        const diffColor = change.difference > 0 ? 'text-green-600' : 'text-red-600';
                        const diffIcon = change.difference > 0 ? 'fa-arrow-up' : 'fa-arrow-down';
                        changeText += `
                            <div class="${diffColor} text-xs mt-1">
                                <i class="fas ${diffIcon} mr-1"></i>
                                Selisih: Rp ${formatRupiah(Math.abs(change.difference))}
                            </div>
                        `;
                    }
                    
                    changeText += '</div>';
                    changesHtml += changeText;
                });
                
                changesList.innerHTML = changesHtml;

                // Tampilkan dampak
                if (impacts.length > 0) {
                    impactSection.style.display = 'block';
                    impactList.innerHTML = impacts.map(impact => `<div class="flex items-start"><i class="fas fa-dot-circle mr-2 mt-1"></i>${impact}</div>`).join('');
                } else {
                    impactSection.style.display = 'none';
                }
            } else {
                changesSection.style.display = 'none';
                impactSection.style.display = 'none';
            }
        }

        function formatRupiah(number) {
            return new Intl.NumberFormat('id-ID').format(number);
        }

        // Inisialisasi tracking saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Delay sedikit untuk memastikan DOM sudah fully loaded
            setTimeout(() => {
                trackChanges();
            }, 100);
        });

        // Fungsi untuk kembali ke halaman sebelumnya
        function goBack() {
            if (document.referrer) {
                window.history.back();
            } else {
                window.location.href = "{{ route('tupusat.tabungan.index') }}";
            }
        }

        // Handle form submission
        document.getElementById('editForm').addEventListener('submit', function(e) {
            // Tampilkan loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Menyimpan...';
            submitBtn.disabled = true;
            
            // Form akan submit secara normal, tidak perlu preventDefault
        });
    </script>

    <style>
        .line-through {
            text-decoration: line-through;
        }
        
        #changes-section, #impact-section {
            animation: fadeIn 0.3s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</x-layout-tupusat>