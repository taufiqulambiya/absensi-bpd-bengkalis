<div class="card-body">
    <div class="row">
        <div class="col-12">
            <div class="form-group col-3 p-0">
                <label for="filter">Filter Bulan</label>
                <select name="filter-bulan" class="form-control"
                    onchange="window.location.href = '?view=bulanan&bulan='+event.target.options[event.target.options.selectedIndex].value">
                    @for ($i = 1; $i <= 12; $i++) @if (!empty($_GET['bulan'])) <option value="{{ $i }}" 
                        @if ($i==$_GET['bulan']) selected @endif>{{ $i }}</option>
                        @else
                        <option value="{{ $i }}" @if ($i==date('m')) selected @endif>{{ $i }}</option>
                        @endif
                        @endfor
                </select>
            </div>
            <h4 class="my-3">Tanggal Hari Ini : {{ date('d - F - Y') }}</h4>
        </div>
        <div class="col-12">
            <div class="border d-inline-block p-1">
                <div class="bg-secondary d-inline-block" style="width: 24px; height: 24px; vertical-align: middle">
                </div>
                <p class="d-inline">Belum Absen</p>
            </div>
            <div class="border d-inline-block p-1">
                <div class="bg-primary d-inline-block" style="width: 24px; height: 24px; vertical-align: middle"></div>
                <p class="d-inline">Sudah Absen Masuk</p>
            </div>
            <div class="border d-inline-block p-1">
                <div class="bg-success d-inline-block" style="width: 24px; height: 24px; vertical-align: middle"></div>
                <p class="d-inline">Sudah Absen Keluar</p>
            </div>
        </div>
        <div class="col-12 p-3">
            <button id="print-all-bulanan" class="btn btn-success">
                <i class="fa fa-print" aria-hidden="true"></i> Cetak
            </button>
            <hr />
        </div>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Pegawai / Tanggal</th>
                            @for ($i = 1; $i <= count($days); $i++) <th>{{
                                $i }}</th>
                                @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($absensi as $item)
                        <tr>
                            <td>{{ $item->nama }}</td>
                            @foreach ($days as $date)
                            @php
                            $abs = $item->absensi->where('tanggal', $date)->first();
                            @endphp
                            {{-- jika ada absen --}}
                            @if($abs)
                            {{-- jika sudah absen keluar --}}
                            @if ($abs->has_out)
                            <td class="bg-success text-white" style="cursor: pointer"
                                onclick="window.location.href = `/panel/absensi/<?= $abs->id ?>`">
                                {{ $abs->waktu_keluar }}
                            </td>
                            {{-- jika belum absen keluar --}}
                            @else
                            <td class="bg-primary text-white" style="cursor: pointer"
                                onclick="window.location.href = `/panel/absensi/<?= $abs->id ?>`">
                                {{ $abs->waktu_masuk }}
                            </td>
                            @endif
                            {{-- jika tidak ada absen --}}
                            @else
                            {{-- jika date item bigger than current date --}}
                            <td @if ($date <=date('Y-m-d')) class="bg-secondary" @endif></td>
                            @endif
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    class ListBulanan {
        constructor() {
            this.absensi = JSON.parse(`<?= $absensi ?? [] ?>`);
            this.days = JSON.parse(`<?= json_encode($days) ?? [] ?>`);
            this.start = this.days[0];
            this.end = this.days.slice(-1)[0];
            this.title = `Laporan Absensi Periode, ${moment(this.start).format('DD/MM/YYYY')} - ${moment(this.end).format('DD/MM/YYYY')}`;
            console.log(this);
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

            this.absensi.forEach(item => {
                const toPush = [
                    item.nip,
                    item.nama,
                    item.tanggal,
                    "-",
                    "-",
                    "-",
                ];
                item.absensi.forEach(abs => {
                    toPush[3] = abs.waktu_masuk;
                    toPush[4] = abs.waktu_keluar;
                    toPush[5] = abs.total_jam;
                    tableBody.push(
                        toPush.map((item) => ({
                            text: item,
                            style: "tableBody",
                        }))
                    );
                });
            })


            console.log(tableBody);
        }
    }

    const bulanan = new ListBulanan();

    $('#print-all-bulanan').click(() => bulanan.print());
</script>