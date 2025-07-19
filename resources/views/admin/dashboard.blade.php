<x-layout-admin>
    <x-slot name="header">

    </x-slot>

    @php
        $unitMap = [
            2 => 'TK',
            3 => 'SD',
            4 => 'SMP',
            5 => 'SMA',
            6 => 'MADIN',
            7 => 'TPQ',
            8 => 'PONDOK',
        ];
        $roleColors = [
            'admin' => 'border-pink-500',
            'tuunit' => 'border-green-500',
            'tupusat' => 'border-blue-500',
            'yayasan' => 'border-yellow-500',
        ];

        $formalUnits = [2, 3, 4, 5];
        $listOfFormalSiswa = collect($listOfAllSiswa)->only($formalUnits);
    @endphp

    <div class="bg-gray-100 py-6">
        <div class="px-8 max-w-7xl mx-auto">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-6">
                @foreach ($listOfAllRole as $role => $jumlah)
                    <div class="p-4 rounded-lg shadow-xl bg-white border-l-4 {{ $roleColors[$role] ?? 'border-gray-300' }} transition hover:scale-105 duration-200">
                        <div>
                            <p class="text-sm uppercase text-gray-600">{{ strtoupper($role) }}</p>
                            <p class="text-xl font-bold text-gray-800">{{ $jumlah }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex flex-col md:flex-row gap-6 mb-6">
                <div class="flex-1 bg-white p-6 rounded-lg shadow-xl">
                    <p class="text-lg font-semibold mb-4 text-gray-800">Role User</p>
                    <canvas id="contextualChart"></canvas>
                </div>
                <div class="flex-1 bg-white p-6 rounded-lg shadow-xl">
                    <p class="text-lg font-semibold mb-4 text-gray-800">User by Unit</p>
                    <canvas id="impressionChart"></canvas>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-6 mb-6">
                <div class="flex-1 bg-white p-6 rounded-lg shadow-xl">
                    <p class="text-lg font-semibold mb-4 text-gray-800">Siswa Formal by Unit</p>
                    <canvas id="resonanceChart"></canvas>
                </div>
                <div class="flex-1 bg-white p-6 rounded-lg shadow-xl">
                    <p class="text-lg font-semibold mb-4 text-gray-800">Top 4 Unit Formal by Siswa</p>
                    <table class="min-w-full text-sm">
                        <thead class="bg-gray-200 text-gray-700">
                            <tr>
                                <th class="p-3 text-left">Unit</th>
                                <th class="p-3 text-right">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($listOfFormalSiswa->sortDesc()->take(5) as $unitId => $jumlah)
                                <tr class="border-t border-gray-300">
                                    <td class="p-3 text-gray-700">{{ $unitMap[$unitId] ?? 'Unit-' . $unitId }}</td>
                                    <td class="p-3 text-right text-gray-800 font-semibold">{{ $jumlah }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            @php
                $maxUnit = $listOfFormalSiswa->sortDesc()->keys()->first();
            @endphp
            <div class="bg-white text-green-800 px-6 py-4 rounded shadow-xl border-l-4 border-green-500">
                <strong>Insight:</strong> Unit formal dengan siswa terbanyak saat ini adalah <strong>{{ $unitMap[$maxUnit] }}</strong>.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const dataRole = @json($listOfAllRole);
        const ctxRole = document.getElementById('contextualChart');
        new Chart(ctxRole, {
            type: 'doughnut',
            data: {
                labels: ['ADMIN', 'TU UNIT', 'TU PUSAT', 'YAYASAN'],
                datasets: [{
                    data: [dataRole.admin, dataRole.tuunit, dataRole.tupusat, dataRole.yayasan],
                    backgroundColor: ['#f472b6', '#34d399', '#818cf8', '#fbbf24'],
                    hoverOffset: 8
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#374151'
                        }
                    },
                    tooltip: {
                        backgroundColor: '#f3f4f6',
                        titleColor: '#111827',
                        bodyColor: '#111827',
                        callbacks: {
                            label: function(context) {
                                return `${context.label}: ${context.raw} user`;
                            }
                        }
                    }
                }
            }
        });

        const dataUnit = @json($listOfAllUnit);
        const ctxUnit = document.getElementById('impressionChart');
        new Chart(ctxUnit, {
            type: 'line',
            data: {
                labels: Object.keys(dataUnit),
                datasets: [{
                    label: 'User per Unit',
                    data: Object.values(dataUnit),
                    borderColor: '#60a5fa',
                    backgroundColor: 'rgba(96, 165, 250, 0.2)',
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                scales: {
                    x: {
                        ticks: { color: '#374151' },
                        grid: { color: '#e5e7eb' }
                    },
                    y: {
                        ticks: { color: '#374151' },
                        grid: { color: '#e5e7eb' }
                    }
                }
            }
        });

        const dataSiswaFormal = @json($listOfFormalSiswa);
        const ctxSiswa = document.getElementById('resonanceChart');
        new Chart(ctxSiswa, {
            type: 'polarArea',
            data: {
                labels: ['TK', 'SD', 'SMP', 'SMA'],
                datasets: [{
                    data: [
                        dataSiswaFormal[2], dataSiswaFormal[3], dataSiswaFormal[4], dataSiswaFormal[5]
                    ],
                    backgroundColor: [
                        '#f472b6', '#60a5fa', '#34d399', '#fbbf24'
                    ]
                }]
            },
            options: {
                scales: {
                    r: {
                        pointLabels: {
                            color: '#374151'
                        },
                        grid: {
                            color: '#e5e7eb'
                        },
                        ticks: {
                            color: '#374151'
                        }
                    }
                }
            }
        });
    </script>
</x-layout-admin>
