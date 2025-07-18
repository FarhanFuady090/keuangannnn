<!DOCTYPE html>
<html>
<head>
    <title>Laporan Data Siswa</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 5px; text-align: center; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2 style="text-align: center;">Laporan Data Siswa</h2>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIS</th>
                <th>NISN</th>
                <th>Nama</th>
                <th>Jenis Kelamin</th>
                <th>Kelas</th>
                <th>Unit Formal</th>
                <th>Informal</th>
                <th>Status Pondok</th>
                <th>Status</th>
                <th>Agama</th>
                <th>Nama Ortu/Wali</th>
                <th>Alamat Ortu</th>
                <th>No Telp Ortu</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($siswas as $no => $data)
                <tr>
                    <td>{{ $no + 1 }}</td>
                    <td>{{ $data->nis }}</td>
                    <td>{{ $data->nisn }}</td>
                    <td>{{ $data->nama }}</td>
                    <td>{{ $data->jenis_kelamin }}</td>
                    <td>{{ $data->kelas->nama_kelas ?? '-' }}</td>
                    <td>{{ $data->unitpendidikan->namaUnit ?? '-' }}</td>
                    <td>{{ optional($unitpendidikan->firstWhere('id', $data->unitpendidikan_idInformal))->namaUnit ?? '-' }}</td>
                    <td>{{ optional($unitpendidikan->firstWhere('id', $data->unitpendidikan_idPondok))->namaUnit ?? '-' }}</td>
                    <td>{{ $data->status }}</td>
                    <td>{{ $data->agama }}</td>
                    <td>{{ $data->namaOrtu }}</td>
                    <td>{{ $data->alamatOrtu }}</td>
                    <td>{{ $data->noTelp }}</td>
                    <td>{{ $data->email }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
