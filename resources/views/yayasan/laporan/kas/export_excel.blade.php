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
                <td>{{ number_format($trx->nominal, 0, ',', '.') }}</td>
                <td>{{ $trx->unitpendidikan->namaUnit ?? '-' }}</td>
                <td>{{ $trx->created_by }}</td>
                <td>{{ \Carbon\Carbon::parse($trx->created_at)->format('d/m/Y') }}</td>
                <td>{{ $trx->keterangan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
