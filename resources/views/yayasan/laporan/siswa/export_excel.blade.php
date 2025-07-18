<table>
    <thead>
        <tr>
            <th>No</th>
            <th>NIS</th>
            <th>NISN</th>
            <th>Nama</th>
            <th>Jenis Kelamin</th>
            <th>Kelas</th>
            <th>Status</th>
            <th>Agama</th>
            <th>Nama Orang Tua / Wali</th>
            <th>Alamat Ortu</th>
            <th>No Telp / WA Ortu</th>
            <th>Email</th>
            <th>Unit Pendidikan Formal</th>
            <th>Unit Pendidikan Informal</th>
            <th>Status Pondok</th>
        </tr>
    </thead>
    <tbody>
        @foreach($siswas as $no => $data)
            <tr>
                <td>{{ $no + 1 }}</td>
                <td>{{ $data->nis }}</td>
                <td>{{ $data->nisn }}</td>
                <td>{{ $data->nama }}</td>
                <td>{{ $data->jenis_kelamin }}</td>
                <td>{{ $data->kelas->nama_kelas ?? '-' }}</td>
                <td>{{ $data->status }}</td>
                <td>{{ $data->agama }}</td>
                <td>{{ $data->namaOrtu }}</td>
                <td>{{ $data->alamatOrtu }}</td>
                <td>{{ $data->noTelp }}</td>
                <td>{{ $data->email }}</td>
                <td>{{ optional($unitpendidikan->firstWhere('id', $data->unitpendidikan_id))->namaUnit ?? '-' }}</td>
                <td>{{ optional($unitpendidikan->firstWhere('id', $data->unitpendidikan_idInformal))->namaUnit ?? '-' }}</td>
                <td>{{ optional($unitpendidikan->firstWhere('id', $data->unitpendidikan_idPondok))->namaUnit ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
