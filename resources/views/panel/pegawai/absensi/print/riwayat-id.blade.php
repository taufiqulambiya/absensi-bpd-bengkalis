<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Riwayat Absensi</title>

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
        }

        .table-bordered {
            border: 1px solid #dee2e6;
        }

        .table-bordered th {
            width: 20%;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
            text-align: left;
            padding: 0.5rem;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h4>
                Detail Absensi {{ $user->nama }} - {{ $user->nip }} - {{ $absensi->formatted_tanggal }}
            </h4>
            <p>
                Badan Pendapatan Daerah Kota Bengkalis
            </p>
        </div>
        <div class="content">
            <div class="section">
                <h4>Data Pegawai</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Nama</th>
                        <td>{{ $user->nama }}</td>
                    </tr>
                    <tr>
                        <th>NIP</th>
                        <td>{{ $user->nip }}</td>
                    </tr>
                    <tr>
                        <th>Jabatan</th>
                        <td>{{ $user->jabatan }}</td>
                    </tr>
                    <tr>
                        <th>Bidang</th>
                        <td>{{ $user->bidangs->nama }}</td>
                    </tr>
                </table>
            </div>

            <div class="section">
                <h4>Data Absensi</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Tanggal</th>
                        <td>{{ $absensi->formatted_tanggal }}</td>
                    </tr>
                    <tr>
                        <th>Jam Masuk</th>
                        <td>{{ $absensi->formatted_waktu_masuk }}</td>
                    </tr>
                    <tr>
                        <th>Jam Keluar</th>
                        <td>{{ $absensi->formatted_waktu_keluar }}</td>
                    </tr>
                    <tr>
                        <th>Total Jam</th>
                        <td>{{ $absensi->total_jam }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi Masuk</th>
                        <td>{{ $absensi->lokasi_masuk }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi Keluar</th>
                        <td>{{ $absensi->lokasi_keluar }}</td>
                    </tr>
                    <tr>
                        <th>Jarak Masuk</th>
                        <td>{{ $absensi->jarak_masuk }}</td>
                    </tr>
                    <tr>
                        <th>Jarak Keluar</th>
                        <td>{{ $absensi->jarak_keluar }}</td>
                    </tr>
                </table>
            </div>

            {{-- <div class="section">
                <h4>Foto</h4>
                <table class="table table-bordered">
                    <tr>
                        <th>Foto Masuk</th>
                        <th>Foto Keluar</th>
                    </tr>
                    <tr>
                        <td>
                            <img src="{{ Storage::url('public/uploads/'.$absensi->dok_masuk) }}" alt="Foto Masuk" width="200">
                        </td>
                        <td>
                            <img src="{{ Storage::url('public/uploads/'.$absensi->dok_keluar) }}" alt="Foto Keluar" width="200">
                        </td>
                    </tr>
                </table>
            </div> --}}
        </div>
    </div>
</body>

</html>