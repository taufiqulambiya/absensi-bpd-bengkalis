@if ($role == 'admin' OR $role == 'atasan')
<div class="table-responsive">
    <table class="datatable table table-striped table-bordered" style="width: 100%">
        <thead>
            <tr>
                <th>#</th>
                <th>Pegawai</th>
                <th>NIP</th>
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
                <td>{{ $item->user->nama }}</td>
                <td>{{ $item->user->nip }}</td>
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
                    @if ($role == 'admin')
                    <button class="btn btn-info btn-edit" title="Perbarui" data-toggle="modal" data-target="#add"
                        data-item="{{ $item }}">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                    </button>
                    <button class="btn btn-danger btn-cancel" title="Batalkan" data-id="{{ $item->id }}">
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                    @else
                    <button class="btn btn-success btn-print-detail" title="Cetak" data-item="{{ $item }}">
                        <i class="fa fa-print" aria-hidden="true"></i>
                    </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@if ($role == 'pegawai')
<div class="table-responsive">
    <table class="datatable table table-striped table-bordered" style="width: 100%">
        <thead>
            <tr>
                <th>#</th>
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
                    <button class="btn btn-success">
                        <i class="fa fa-print" aria-hidden="true"></i>
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

<script>
    class DinasLuarComing {
        printPerItem(item) {
            console.log(item);
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
                content: [
                    {
                        text: 'Laporan Dinas Luar Pegawai',
                        bold: true,
                        alignment: 'center',
                        fontSize: 16,
                        margin: [0,24],
                    },
                    {
                        margin: [12,0,0,14],
                        fontSize: 14,
                        bold: 'true',
                        text: 'Detail Pegawai :',
                        underline: 'true'
                    },
                    {
                        type: 'none',
                        ol: mapData(profile),
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

    const dinasLuarComing = new DinasLuarComing();

    $('.btn-print-detail').each(function() {
        $(this).click(function() {
            const item = $(this).data('item');
            dinasLuarComing.printPerItem(item);
        })
    })
</script>