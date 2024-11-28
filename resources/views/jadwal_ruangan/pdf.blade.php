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
    <h2 class="text-center text-lg font-bold">Jadwal Ruangan</h2>

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
                <td style="width: 20%;">{{ $jadwal->pemetaan->nama_modul }}</td>
                <td style="width: 20%;">{{ $jadwal->pemetaan->mata_kuliah->nama_matakuliah }}</td>
                <td style="width: 15%;">{{ $jadwal->pemetaan->dosen->Nama }}</td>
                <td style="width: 5%;">{{ $jadwal->pemetaan->mata_kuliah->tingkat }}</td>
                <td style="width: 10%;">{{ $jadwal->pemetaan->hari }}</td>
                <td style="width: 10%;">{{ $jadwal->pemetaan->jam_mulai }}</td>
                <td style="width: 10%;">{{ $jadwal->pemetaan->jam_selesai }}</td>
                <td style="width: 15%;">{{ $jadwal->pemetaan->tanggal_mulai }}</td>
                <td style="width: 15%;">{{ $jadwal->pemetaan->tanggal_selesai }}</td>
                <td style="width: 10%;">{{ $jadwal->ruangan->nama_ruangan }}</td>
            </tr>

            @endforeach
        </tbody>
    </table>
</body>
</html>
