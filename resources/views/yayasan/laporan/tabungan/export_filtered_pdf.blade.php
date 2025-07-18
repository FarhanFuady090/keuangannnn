<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Rekap Tabungan Siswa</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px 8px;
            text-align: center;
        }
        th {
            background-color: #e5e5e5;
        }
        h2, h4 {
            text-align: center;
            margin: 0;
        }
        .small-text {
            font-size: 11px;
        }
    </style>
</head>
<body>

    <h2>Rekap Tabungan Siswa</h2>
    <h4>Yayasan Nurul Huda</h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Siswa</th>
                <th>Unit</th>
                <th>Kelas</th>
                <th>Setoran Awal</th>
                <th>Total Setoran</th>
                <th>Total Penarikan</th>
                <th>Saldo Akhir</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tabungans as $index => $tabungan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $tabungan->siswa->nama ?? '-' }}</td>
                    <td>{{ $tabungan->siswa->kelas->unitpendidikan->namaUnit ?? '-' }}</td>
                    <td>{{ $tabungan->siswa->kelas->nama_kelas ?? '-' }}</td>
                    <td>Rp {{ number_format($tabungan->saldo_awal, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($tabungan->total_setoran ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($tabungan->total_penarikan ?? 0, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($tabungan->saldo_akhir, 0, ',', '.') }}</td>
                    <td>{{ $tabungan->siswa->status ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9">Tidak ada data tabungan sesuai filter.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
