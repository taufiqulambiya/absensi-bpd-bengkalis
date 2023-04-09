<html>

<head>
    <title>Riwayat Absensi {{ $user->nama }}</title>
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
            table-layout: fixed;
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            text-align: center;
            font-size: 1rem;
            padding: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="container">
        {{-- header --}}
        <div class="header">
            <h4 class="title">
                Riwayat Absensi {{ $user->nama }} - {{ $user->nip }} - {{ date('d-m-Y') }}
            </h4>
            <p>
                Badan Pendapatan Daerah Kota Bengkalis
            </p>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tanggal</th>
                    <th>Waktu Masuk</th>
                    <th>Waktu Keluar</th>
                    <th>Lokasi Masuk</th>
                    <th>Lokasi Keluar</th>
                    <th>Jarak Masuk</th>
                    <th>Jarak Keluar</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($absensi as $item)
                <tr>
                    <td scope="row">{{ $loop->iteration }}</td>
                    <td>{{ $item->formatted_tanggal }}</td>
                    <td>{{ $item->formatted_waktu_masuk }}</td>
                    <td>{{ $item->formatted_waktu_keluar }}</td>
                    <td>{{ $item->lokasi_masuk }}</td>
                    <td>{{ $item->lokasi_keluar }}</td>
                    <td>{{ $item->jarak_masuk }}</td>
                    <td>{{ $item->jarak_keluar }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>