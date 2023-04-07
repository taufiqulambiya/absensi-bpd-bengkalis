<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Cuti</title>

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
            <h1>Data Cuti {{ $user->nama }}</h1>
            <p>Badan Pendapatan Daerah Bengkalis</p>
        </div>
        <div class="content">
            <h4>Data Pegawai</h4>
            <table class="table table-bordered">
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td>{{ $user->nama }}</td>
                </tr>
                <tr>
                    <td>NIP</td>
                    <td>:</td>
                    <td>{{ $user->nip }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $user->jabatan }}</td>
                </tr>
                <tr>
                    <td>Bidang</td>
                    <td>:</td>
                    <td>{{ $user->bidangs->nama ?? '-' }}</td>
                </tr>
            </table>

            <h4>Data Cuti</h4>
            <table class="table table-bordered">
                <tr>
                    <td>Tanggal</td>
                    <td>:</td>
                    <td>{{ $cuti->tanggal }}</td>
                </tr>
                <tr>
                    <td>Jumlah Hari</td>
                    <td>:</td>
                    <td>{{ $cuti->total }}</td>
                </tr>
                <tr>
                    <td>Jenis Cuti</td>
                    <td>:</td>
                    <td>{{ strtoupper($cuti->jenis) }}</td>
                </tr>
                <tr>
                    <td>Keterangan</td>
                    <td>:</td>
                    <td>{{ $cuti->keterangan }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td>{{ $cuti->status }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>