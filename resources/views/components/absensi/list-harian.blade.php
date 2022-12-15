<div class="card-body">
    <div class="row">
        <div class="col-12">
            <div class="form-group col-3 p-0">
                <label for="filter">Filter Status</label>
                <select name="filter-status" id="filter-status" class="form-control"
                    onchange="window.location.href = `?view=harian&status=${event.target.options[event.target.options.selectedIndex].value}&tgl={{ request()->tgl }}`">
                    @foreach ($filter_status as $item)
                    <option value="{{ $item[0] }}" @if (request()->status === $item[0]) selected @endif>
                        {{ $item[1] }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-12">
            <div class="form-group col-3 p-0">
                <label for="filter">Filter Tanggal</label>
                <input type="date" class="form-control" id="filter-tgl"
                    onchange="window.location.href = `?view=harian&status={{ request()->status }}&tgl=${event.target.value}`"
                    value="{{ request()->tgl ?? date('Y-m-d') }}">
            </div>

            @if (request()->status OR request()->tgl)
            <h4 class="my-3">Difilter berdasarkan status: <script>
                    document.write($('#filter-status option:selected').text())
                </script>, tanggal: <script>
                    document.write(moment(new Date($('#filter-tgl').val())).format('DD/MM/YYYY'))
                </script>
            </h4>
            <a href="?" class="btn btn-primary">Clear filter</a>
            @else
            <h4 class="my-3">Tanggal Hari Ini : {{ date('d - F - Y') }}</h4>
            @endif
        </div>
        <div class="col-12">
            <hr />
            <button class="btn btn-success mb-3" id="print-all-harian">
                <i class="fa fa-print mr-2" aria-hidden="true"></i>Cetak
            </button>
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="datatable table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Tanggal</th>
                            <th>Waktu Masuk</th>
                            <th>Waktu Keluar</th>
                            <th>Jam Absen</th>
                            <th>Total Jam</th>
                            <th>Dokumentasi</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($absensi as $item)
                        <tr class="text-{{ $item['color'] }}">
                            <th scope="row">{{ $loop->iteration }}</th>
                            <th>{{ $item['nip'] }}</th>
                            <th>{{ $item['nama'] }}</th>
                            {{-- @if (!empty($item['absensi'])) --}}
                            <th>{{ $item['absensi']->tanggal ?? '-' }}</th>
                            <th>{{ $item['absensi']->waktu_masuk ?? '-'}}
                            </th>
                            <th>{{ $item['absensi']->waktu_keluar ?? '-'}}
                            </th>
                            <th>{{ $item['absensi']->jam_kerja ?? '-'}}
                            </th>
                            <th>{{ $item['absensi']->total_jam ?? '-'}}
                            </th>
                            <th>
                                @if ($item['absensi']->dok_masuk ?? false AND
                                Storage::disk('public')->has('uploads/'.$item['absensi']->dok_masuk))
                                <a href="{{ Storage::url('public/uploads/'.$item['absensi']->dok_masuk) }}"
                                    target="_blank">
                                    <p>Dok. Masuk</p>
                                </a>
                                @endif
                                @if ($item['absensi']->dok_keluar ?? false AND
                                Storage::disk('public')->has('uploads/'.$item['absensi']->dok_keluar))
                                <a href="{{ Storage::url('public/uploads/'.$item['absensi']->dok_keluar) }}"
                                    target="_blank">
                                    <p>Dok. Keluar</p>
                                </a>
                                @endif
                            </th>
                            <th>{{ strtoupper($item['status']) }}</th>
                            <th>
                                @if ($item['status'] != 'belum absen')
                                <button class="btn btn-success btn-print-item" data-item="{{ json_encode($item) }}"><i
                                        class="fa fa-print" aria-hidden="true"></i></button>
                                @endif
                            </th>
                            {{-- @else
                            @for ($i = 0; $i < 8; $i++) <th>-</th> @endfor
                                @endif --}}
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    class ListHarian {
        constructor() {
            this.absensi = JSON.parse(`<?= json_encode($absensi) ?? [] ?>`);
            this.filterTanggal = `<?= $_GET['tgl'] ?? null ?>`;
        }

        getTitle () {
            if (this.filterTanggal === '') {
                return 'Laporan Absensi Harian - ' + moment().format('DD/MM/YYYY');
            }
            const $date = parseInt(this.filterTanggal, 10) < 10 ? `${moment().format('YYYY-MM')}-0${this.filterTanggal}` : `${moment().format('YYYY-MM')}-${this.filterTanggal}`;
            return 'Laporan Absensi Harian - ' + moment($date).format('DD/MM/YYYY');
        }

        print() {
            const tableBody = [
                [
                    { text: "NIP", style: "tableHeader" },
                    { text: "Nama", style: "tableHeader" },
                    { text: "Tanggal", style: "tableHeader" },
                    { text: "Waktu Masuk", style: "tableHeader" },
                    { text: "Waktu Keluar", style: "tableHeader" },
                    { text: "Total Jam", style: "tableHeader" },
                ],
            ];

            this.absensi.forEach((item) => {
                const toPush = [
                    item.nip,
                    item.nama,
                    _.getOr('-', 'absensi.tanggal', item),
                    _.getOr('-', 'absensi.waktu_masuk', item),
                    _.getOr('-', 'absensi.waktu_keluar', item),
                    _.getOr('-', 'absensi.total_jam', item),
                ];
                tableBody.push(
                    toPush.map((item) => ({
                        text: item,
                        style: "tableBody",
                    }))
                );
            });

            const title = this.getTitle();
            const dd = {
                content: [
                    {
                        text: title,
                        alignment: "center",
                        margin: [0, 32, 0, 24],
                        fontSize: 12,
                    },
                    {
                        style: "tableStyle",
                        headerRows: 1,
                        table: {
                            // widths: Array.from({ length: 6 }).map(() => 60),
                            widths: "*",
                            body: tableBody,
                        },
                        layout: "lightHorizontalLines",
                    },
                ],
                styles: {
                    tableHeader: {
                        fontSize: 10,
                        bold: true,
                        margin: [4, 4, 4, 4],
                    },
                    tableBody: {
                        fontSize: 10,
                        margin: [4, 4, 4, 4],
                    },
                },
            };

            pdfMake.createPdf(dd).open();
        }

        printItem(item, title) {
            const mapData = ($obj) =>
                Object.entries($obj).map(([key, val]) => ({
                    margin: [0, 0, 0, 14],
                    columns: [
                        {
                            width: 80,
                            text: key,
                        },
                        {
                            alignment: "center",
                            width: 30,
                            text: ":",
                        },
                        val,
                    ],
                }));

            const profile = {
                Nama: item.nama,
                NIP: item.nip,
                "Jenis Kelamin": item.jk,
                Alamat: item.alamat,
                "No. Telp": item.no_telp,
            };

            const absensi = {
                Tanggal: _.getOr('-', 'absensi[0].tanggal', item),
                "Jam Absen": _.getOr('-', 'absensi[0].jam_kerja', item),
                "Jam Masuk": _.getOr('-', 'absensi[0].waktu_masuk', item),
                "Jam Keluar": _.getOr('-', 'absensi[0].waktu_keluar', item),
                "Total Jam": _.getOr('-', 'absensi[0].total_jam', item),
            };

            const dd = {
                content: [
                    {
                        text: "Laporan Absensi Pegawai Harian",
                        bold: true,
                        alignment: "center",
                        fontSize: 16,
                    },
                    {
                        text: title,
                        bold: true,
                        alignment: "center",
                        fontSize: 16,
                        margin: [0, 0, 0, 32],
                    },
                    {
                        margin: [12, 0, 0, 14],
                        fontSize: 14,
                        bold: "true",
                        text: "Detail Pegawai :",
                        underline: "true",
                    },
                    {
                        type: "none",
                        ol: mapData(profile),
                    },
                    {
                        margin: [12, 14, 0, 14],
                        fontSize: 14,
                        bold: "true",
                        text: "Detail Absensi :",
                    },
                    {
                        type: "none",
                        ol: mapData(absensi),
                    },
                    {
                        text: "Bengkalis, " + moment().format('DD/MM/YY'),
                        alignment: "right",
                        margin: [0, 64, 0, 64],
                    },
                    {
                        text: "Administrator",
                        alignment: "right",
                    },
                ],
            };
            pdfMake.createPdf(dd).open();
        }
    }

    const harian = new ListHarian();

    $('#print-all-harian').click(() => {
        harian.print();
    });
    $.each($('.btn-print-item'), function() {
        $(this).click(function() {
            const item = $(this).data('item');
            harian.printItem(item, `${item.nama} - ${_.getOr(moment().format('DD/MM/YY'), 'absensi[0].tanggal', item)}`);
        })
    })
</script>