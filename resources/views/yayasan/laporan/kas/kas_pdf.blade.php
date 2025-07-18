<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Kas</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
    <h2 style="text-align: center; margin-bottom: 20px;">Laporan Transaksi Kas Yayasan</h2>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kas</th>
                <th>Tipe</th>
                <th>Nominal</th>
                <th>Unit Pendidikan</th>
                <th>Created By</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($transaksiKas as $key => $trx)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $trx->kas->namaKas ?? '-' }}</td>
                    <td>{{ $trx->tipe }}</td>
                    <td>Rp {{ number_format($trx->nominal, 0, ',', '.') }}</td>
                    <td>{{ $trx->unitpendidikan->namaUnit ?? '-' }}</td>
                    <td>{{ $trx->created_by }}</td>
                    <td>{{ \Carbon\Carbon::parse($trx->created_at)->format('d/m/Y') }}</td>
                    <td>{{ $trx->keterangan }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
