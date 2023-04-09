<div>
    <style>
        .form-group {
            margin-right: 10px;
            width: 100%;
        }

        .cursor-pointer {
            cursor: pointer;
        }
    </style>
    <div class="row">
        <div class="col-md-6">
            <div class="d-flex">
                <div class="form-group" style="flex: 1">
                    <label for="bulan">Bulan</label>
                    <select class="form-control" id="bulan" wire:model="filter.bulan">
                        @foreach ($monthIndo as $idx => $item)
                        <option value="{{$idx}}">{{$item}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="flex: 1">
                    <label for="tahun">Tahun</label>
                    <select class="form-control" id="tahun" wire:model="filter.tahun">
                        @foreach ($years as $item)
                        <option value="{{$item}}">{{$item}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @if ($isFiltered)
        <div class="col-12">
            <div class="alert alert-info col-6" role="alert">
                <p class="mb-0">Difilter berdasarkan {{$filterString}}
                    <a href="javascript:void(0)" wire:click="clearFilter" class="float-right text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                            class="bi bi-x" viewBox="0 0 16 16">
                            <path
                                d="M11.854 4.646a.5.5 0 0 1 0 .708L9.207 8l2.647 2.646a.5.5 0 0 1-.708.708L8.5 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.793 8 5.146 5.354a.5.5 0 0 1 .708-.708L8.5 7.293l2.646-2.647z" />
                        </svg>
                    </a>
                </p>
            </div>
        </div>
        @endif
        <div class="col-12">
            <a href="?print=all&month={{$filter['bulan']+1}}&year={{$filter['tahun']}}&mode=bulanan" target="_blank"
                class="btn btn-success">
                <i class="fa fa-print" aria-hidden="true"></i> Cetak
            </a>
            <hr />
        </div>
        <div class="col-12">
            <div class="alert alert-secondary" role="alert">
                <p class="mb-0">Keterangan warna:
                    <span class="badge badge-success">Hadir</span>
                    <span class="badge badge-danger">Tidak Hadir</span>
                    <span class="badge badge-warning">Izin</span>
                    <span class="badge badge-info">Cuti</span>
                    <span class="badge badge-primary">Dinas Luar</span>
                    <span class="badge badge-secondary">Libur</span>
                </p>
            </div>
        </div>
        <div class="col-12 pb-3">
            <h4>{{$monthIndo[$filter['bulan']]}} {{$filter['tahun']}}</h4>
            <div class="table-responsive">
                <table class="table table-bordered table-striped no-tabledata">
                    <thead>
                        <tr>
                            <th style="position: sticky; left: 0; background: white; z-index: 1">Nama</th>
                            @foreach ($dates as $item)
                            <th>{{ $item }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($data as $item)
                        <tr>
                            <td style="position: sticky; left: 0; background: white; z-index: 1">{{$item->nama}}</td>
                            @foreach ($item->absensi as $a)
                            <td class="{{$a->td_class}}" @if (isset($a->clickable)) wire:click="detail({{$a->id}})"
                                @endif>
                                {{$a->show_text}}
                            </td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>