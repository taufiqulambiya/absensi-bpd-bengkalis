<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Data Cuti</title>

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

        .content .user {
            margin-bottom: 0.5rem;
            padding: 1rem;
            border: 1px solid #dee2e6;
        }

        .content .user h4 {
            margin-bottom: 0;
        }

        table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
            /* table-layout: fixed; */
        }

        table th,
        table td {
            font-size: 0.8rem;
            padding: 0.5rem;
            border: 1px solid #dee2e6;
            max-width: 100%;
            word-wrap: break-word;
            text-align: left;
            vertical-align: top;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h4>Laporan Data Cuti <br />
                Tanggal {{ $tanggal_awal }} - {{ $tanggal_akhir }}
            </h4>
            <p>Badan Pendapatan Daerah Kabupaten Bengkalis</p>
        </div>
        <div class="content">
            @foreach ($data as $item)
            <div class="user">
                <h4>{{ $item['user']->nama }}</h4>
                <p>{{ $item['user']->nip }}</p>
            </div>
            <div class="absensi">
                <table>
                    <thead>
                        <tr>
                            <th>Jenis Cuti</th>
                            <th>Tanggal</th>
                            <th>Durasi</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($item['cuti'] as $cuti)
                        <tr>
                            <td>{{ $cuti->jenis }}</td>
                            <td>{{ $cuti->formatted_tanggal }}</td>
                            <td>{{ $cuti->durasi }}</td>
                            <td>{{ $cuti->status }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
        </div>
    </div>

</body>

</html>