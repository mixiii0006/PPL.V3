<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jadwal Ruangan PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Jadwal Ruangan</h2>

    <table>
        <thead>
            <tr>
                <th>Nama Kuliah</th>
                <th>Modul</th>
                <th>Dosen</th>
                <th>Tingkat</th>
                <th>Hari</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Tanggal Masuk</th>
                <th>Tanggal Selesai</th>
                <th>Ruangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datas as $jadwal)
                <tr>
                    <td>{{ $jadwal->pemetaan->mata_kuliah->nama }}</td>
                    <td>{{ $jadwal->pemetaan->modul }}</td>
                    <td>{{ $jadwal->pemetaan->dosen->nama }}</td>
                    <td>{{ $jadwal->pemetaan->tingkat }}</td>
                    <td>{{ $jadwal->pemetaan->hari }}</td>
                    <td>{{ $jadwal->jam_mulai }}</td>
                    <td>{{ $jadwal->jam_selesai }}</td>
                    <td>{{ $jadwal->tanggal_mulai->format('d-m-Y') }}</td>
                    <td>{{ $jadwal->tanggal_selesai->format('d-m-Y') }}</td>
                    <td>{{ $jadwal->ruangan->nama }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
