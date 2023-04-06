<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Absensi Harian</title>

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
            padding: 1.5rem;
            background-color: #e9ecef;
            border-radius: 0.3rem;
            text-align: center;
        }

        .title {
            margin-bottom: 0;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            border-collapse: collapse;
            /* table-layout: fixed; */
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            text-align: left;
            padding: 0.5rem;
            font-size: 0.8rem;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h4>
                Data Absensi Semua Pegawai tanggal {{ $tgl }}
            </h4>
            <p>
                Badan Pendapatan Daerah Kota Bengkalis
            </p>
        </div>
        <div class="content">
            <div class="section">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th>Tanggal</th>
                            <th>Jam Kerja</th>
                            <th>
                                <div>Waktu Masuk</div>
                                <div>--</div>
                                <div>Waktu Keluar</div>
                            </th>
                            <th>Total Jam</th>
                            <th>
                                <div>Lokasi Masuk</div>
                                <div>--</div>
                                <div>Lokasi Keluar</div>
                            </th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->nip }}</td>
                            <td>{{ $item->absensiRaw->tanggal ?? '-' }}</td>
                            <td>{{ $item->absensiRaw->shift ?? '-' }}</td>
                            <td>
                                <div>{{ $item->absensiRaw->waktu_masuk ?? '-' }}</div>
                                <div>--</div>
                                <div>{{ $item->absensiRaw->waktu_keluar ?? '-' }}</div>
                            </td>
                            <td>{{$item->absensiRaw->total_jam??'-'}}</td>
                            <td>
                                <div>{{ $item->absensiRaw->lokasi_masuk ?? '-' }}</div>
                                <div>--</div>
                                <div>{{ $item->absensiRaw->lokasi_keluar ?? '-' }}</div>
                            </td>
                            <td>
                                {!! $item->statusHtml !!}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>

</html>