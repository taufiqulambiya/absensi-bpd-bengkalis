<div class="table-responsive">
    <table class="datatable table table-striped table-bordered" style="width: 100%">
        <thead>
            <tr>
                <th>#</th>
                @if ($role == 'admin' OR $role == 'atasan')
                    <th>Nama</th>
                    <th>NIP</th>
                @endif
                <th>Mulai</th>
                <th>Selesai</th>
                <th>Durasi</th>
                <th>Maksud Perjalanan</th>
                <th>Lokasi</th>
                <th>Keterangan</th>
                <th>Surat Jalan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                @if ($role == 'admin' OR $role == 'atasan')
                    <td>{{ $item->user->nama }}</td>
                    <td>{{ $item->user->nip }}</td>
                @endif
                <td>{{ $item->mulai }}</td>
                <td>{{ $item->selesai }}</td>
                <td>{{ $item->durasi }} Hari</td>
                <td>{{ $item->maksud }}</td>
                <td>{{ $item->lokasi }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>
                    @if (Storage::disk('public')->has('uploads/'.$item->file))
                    <a href="{{ Storage::url('public/uploads/'.$item->file) }}" target="_blank">Download</a>
                    @endif
                </td>
                <td>
                    <button class="btn btn-success btn-print-detail" data-item="{{ $item }}">
                        <i class="fa fa-print" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
    class DinasLuarDone {
        printPerItem(item) {
            const mapData = $obj => Object.entries($obj).map(([key, val]) => ({
                    margin: [0, 0, 0, 14],
                    columns: [
                        {
                            width: 80,
                            text: key,
                        },
                        {
                            alignment: 'center',
                            width: 30,
                            text: ':',
                        },
                        val,
                    ]
                }));
                
                
            const profile = {
                Nama: item.user.nama,
                NIP: item.user.nip,
                'Jenis Kelamin': item.user.jk,
                'Alamat': item.user.alamat,
                'No. Telp': item.user.no_telp,
            }

            const dinas_luar = {
                'Tanggal Mulai': item.mulai,
                'Tanggal Selesai': item.selesai,
                Durasi: `${item.durasi} Hari`,
                Tujuan: item.tujuan,
                Keterangan: item.keterangan || '-',
            }

            const dd = {
                pageSize: 'A4',
                pageOrientation: 'portrait',
                pageMargins: [ 40, 60, 40, 60 ],
                header: {
                    margin: [40, 20, 40, 0],
                    stack: [
                        {
                            text: 'Laporan Dinas Luar Pegawai',
                            bold: true,
                            alignment: 'center',
                            fontSize: 14,
                        },
                        {
                            text: 'Badan Pendapatan Daerah Kabupaten Bengkalis',
                            alignment: 'center',
                            fontSize: 12,
                            margin: [0,0],
                        },
                        {
                            alignment: 'center',
                            canvas : [
                                {
                                    type: 'line',
                                    x1: 0,
                                    y1: 0,
                                    x2: 400,
                                    y2: 0,
                                    lineWidth: 1,
                                }
                            ]
                        }
                    ]
                },
                content: [
                    {
                        margin: [12,14,0,14],
                        fontSize: 14,
                        bold: 'true',
                        text: 'Detail Pegawai :',
                        underline: 'true'
                    },
                    {
                        type: 'none',
                        ol: mapData(profile),
                        fontSize: 12,
                    },
                    {
                        margin: [12,14,0,14],
                        fontSize: 14,
                        bold: 'true',
                        text: 'Detail Dinas Luar :',
                    },
                    {
                        type: 'none',
                        ol: mapData(dinas_luar),
                    },
                    {
                        text: `Pekanbaru, ${moment().format('DD MMMM YYYY')}`,
                        alignment: 'right',
                        margin: [0,64,0,64]
                    },
                    {
                        text: 'Administrator',
                        alignment: 'right',
                    },
                ]
            }

            pdfMake.createPdf(dd).open();
        }
    }

    const dinasLuarDone = new DinasLuarDone();

    $('.btn-print-detail').each(function() {
        $(this).click(function() {
            const item = $(this).data('item');
            dinasLuarDone.printPerItem(item);
        })
    })
</script>