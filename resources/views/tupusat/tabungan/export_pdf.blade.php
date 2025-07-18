<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Tabungan Siswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 20px;
        }

        h3 {
            margin-bottom: 5px;
        }

        h4 {
            margin-top: 30px;
            margin-bottom: 10px;
        }

        p {
            margin: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table, th, td {
            border: 1px solid #555;
        }

        th {
            background-color: #f2f2f2;
            padding: 8px;
            text-align: left;
        }

        td {
            padding: 8px;
        }

        .saldo {
            margin-top: 15px;
        }
    </style>
</head>
<body>

    <h3>Laporan Tabungan Siswa</h3>
    <p><strong>Nama:</strong> {{ $tabungan->siswa->nama }}</p>

    <div class="saldo">
        <p><strong>Saldo Awal:</strong> Rp {{ number_format($tabungan->saldo_awal, 0, ',', '.') }}</p>
        <p><strong>Saldo Akhir:</strong> Rp {{ number_format($tabungan->saldo_akhir, 0, ',', '.') }}</p>
    </div>

    <h4>Riwayat Transaksi</h4>

    <table>
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Jumlah</th>
                <th>Saldo</th>
                <th>Petugas</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tabungan->transaksi as $trx)
                <tr>
                    <td>{{ $trx->created_at->translatedFormat('d F Y h:i A') }}</td>
                    <td>{{ $trx->jenis_transaksi }}</td>
                    <td class="py-2 px-4 border-b {{ $trx->jenis_transaksi == 'Penarikan' ? 'text-red-600' : 'text-green-700' }}">
                        Rp {{ number_format($trx->jumlah, 0, ',', '.') }}
                    </td>
                    <td>Rp {{ number_format($trx->saldo_berjalan, 0, ',', '.') }}</td>
                    <td>{{ $trx->petugas }}</td>
                    <td>{{ $trx->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
