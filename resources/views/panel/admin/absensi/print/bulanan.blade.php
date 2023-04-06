<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$title}}</title>

    <style>
        * {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .text-center {
            text-align: center !important;
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
            margin-bottom: 2rem;
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
                {{$title}}
            </h4>
            <p>
                Badan Pendapatan Daerah Kota Bengkalis
            </p>
        </div>
        <div class="content">
            <div class="section">
                @foreach ($data as $item)
                <table class="table table-bordered">
                    {{-- user info, $item is user --}}
                    <thead>
                        <tr>
                            <th colspan="7">
                                <div>Nama : {{$item->nama}}</div>
                                <div>NIP : {{$item->nip}}</div>
                                <div>Unit Kerja : {{$item->jabatan}} - {{$item->bidangs->nama ?? ''}}</div>
                            </th>
                        </tr>
                    </thead>
                    {{-- end user info --}}
                    <thead>
                        <tr>
                            <th>No</th>
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
                            {{-- <th>Status</th> --}}
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($item->absensi) == 0)
                        <tr>
                            <td colspan="7" class="text-center">Tidak ada data</td>
                        </tr>
                        @endif
                        @foreach ($item->absensi as $a)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $a->tanggal ?? '-' }}</td>
                            <td>{{ $a->shift ?? '-' }}</td>
                            <td>
                                <div>{{ $a->waktu_masuk ?? '-' }}</div>
                                <div>--</div>
                                <div>{{ $a->waktu_keluar ?? '-' }}</div>
                            </td>
                            <td>{{$a->total_jam??'-'}}</td>
                            <td>
                                <div>{{ $a->lokasi_masuk ?? '-' }}</div>
                                <div>--</div>
                                <div>{{ $a->lokasi_keluar ?? '-' }}</div>
                            </td>
                            {{-- <td>
                                {!! $item->statusHtml !!}
                            </td> --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>