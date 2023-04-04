<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Data Pegawai</title>

    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 100%;
            padding: 20px;
        }

        .header {
            margin-bottom: 1rem;
            padding: 1rem;
            background-color: #e9ecef;
            border-radius: 0.3rem;
            text-align: center;
        }

        .header h1 {
            margin-bottom: 0;
            font-size: 1.5rem;
        }

        .content h4 {
            margin-bottom: 0.5rem;
        }

        table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
            table-layout: fixed;
        }

        table th,
        table td {
            font-size: 0.8rem;
            padding: 0.5rem;
            border: 1px solid #dee2e6;
            max-width: 100%;
            word-wrap: break-word;
        }

    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h4>Laporan Data Pegawai</h4>
        </div>
        <div class="content">
            <table>
                <thead>
                    <tr>
                        <th style="width: 10%">No</th>
                        <th style="width: 15%">Nama</th>
                        <th style="width: 10%">NIP</th>
                        <th style="width: 10%">Golongan</th>
                        <th style="width: 10%">Jabatan</th>
                        <th style="width: 10%">Bidang</th>
                        <th style="width: 15%">Alamat</th>
                        <th style="width: 10%">No. HP</th>
                        <th style="width: 10%">Email</th>
                        <th style="width: 10%">Status</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->nip }}</td>
                            <td>{{ $item->golongan }}</td>
                            <td>{{ $item->jabatan }}</td>
                            <td>{{ $item->bidangs->nama }}</td>
                            <td>{{ $item->alamat }}</td>
                            <td>{{ $item->no_telp }}</td>
                            <td>{{ $item->email }}</td>
                            <td>{{ $item->status }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>