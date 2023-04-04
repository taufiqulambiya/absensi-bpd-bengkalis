<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Dinas Luar</title>

    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 100%;
            padding-right: 0;
            padding-left: 0;
            margin-right: auto;
            margin-left: auto;
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

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            font-size: 1rem;
            padding: 0.5rem;
        }

        .table tr td:first-child {
            width: 150px;
        }
        .table tr td:nth-child(2) {
            width: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Data Dinas Luar {{ $item->user->nama }}</h1>
            <p>Badan Pendapatan Daerah Bengkalis</p>
        </div>
        <div class="content">
            <h4>Data Pegawai</h4>
            <table class="table table-bordered">
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $item->user->nama }}</td>
                </tr>
                <tr>
                    <td>NIP</td>
                    <td>:</td>
                    <td>{{ $item->user->nip }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $item->user->jabatan }}</td>
                </tr>
                <tr>
                    <td>Bidang</td>
                    <td>:</td>
                    <td>{{ $item->user->nama }}</td>
                </tr>
            </table>

            <h4>Data Dinas Luar</h4>
            <table class="table table-bordered">
                <tr>
                    <td>Tanggal Mulai</td>
                    <td>:</td>
                    <td>{{ $item->mulai }}</td>
                </tr>
                <tr>
                    <td>Tanggal Selesai</td>
                    <td>:</td>
                    <td>{{ $item->selesai }}</td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                    <td>:</td>
                    <td>{{ $item->lokasi }}</td>
                </td>
                <tr>
                    <td>Maksud</td>
                    <td>:</td>
                    <td>{{ $item->maksud }}</td>
                </td>
                <tr>
                    <td>Keterangan</td>
                    <td>:</td>
                    <td>{{ $item->keterangan }}</td>
                </td>
            </table>
        </div>
    </div>
</body>

</html>