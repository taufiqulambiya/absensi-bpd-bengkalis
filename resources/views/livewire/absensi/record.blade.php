<div>
    <style>
        h5 {
            font-size: 1rem;
        }
    </style>
    <div class="card-body" id="record-{{$mode}}">
        @if (!$showRecord)
        <div class="text-center p-3 no-data">
            <img src="/img/not_found.png" alt="No Data" class="img-fluid w-50">
            <h4 class="mb-3">Belum ada data.</h4>
            <button class="btn btn-primary d-block mx-auto mb-3 show-record" @if ($disable_log) disabled @endif
                wire:click="showRecord">Log
                Absen {{ $mode == 'in' ? 'Masuk' : 'Keluar' }}</button>
        </div>
        @else
        <div class="record">
            <ul class="list-group mb-3">
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Waktu <span class="float-right">:</span></h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-right">{{ date('H:i') }} WIB</h5>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Tanggal <span class="float-right">:</span></h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-right">{{ !empty($missedOut) ? date_format(date_create($missedOut->tanggal), 'd/m/Y') : date('d/m/Y') }}</h5>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Jam Absen <span class="float-right">:</span></h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-right">{{ $jamKerja }}</h5>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Lokasi <span class="float-right">:</span></h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-right location">
                                @if (optional($form)['place'])
                                {{ $form['place'] }}
                                @else
                                <button class="btn btn-primary btn-sm" wire:click="getLocation"
                                    wire:target="getLocation" @if (in_array('getLocation', $loadings)) disabled @endif>
                                    @if (in_array('getLocation', $loadings))
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                    @else
                                    <span>Dapatkan Lokasi</span>
                                    @endif
                                </button>
                                @endif
                            </h5>
                        </div>
                    </div>
                </li>
                <li class="list-group-item">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Jarak Ke Kantor <span class="float-right">:</span></h5>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-right distance">
                                @if (optional($form)['distance'])
                                {{ $form['distance'] }}
                                @else
                                Klik tombol 'Dapatkan
                                Lokasi' terlebih
                                dahulu.
                                @endif
                            </h5>
                        </div>
                    </div>
                </li>
                @if ($isLocationDone)
                <li class="list-group-item capture" style="min-height: 250px">
                    @if ($captureState == 'ready')
                    <div class="precapture-box">
                        <div class="inner">
                            <div class="text-center">
                                <h5 class="mb-3">Ambil Foto</h5>
                                <img src="/img/camera.png" alt="Camera" class="img-fluid w-50">
                            </div>
                        </div>
                    </div>
                    @endif
                    <video autoplay class="img-fluid mb-3" wire:ignore></video>
                    <img src="#" alt="#" class="img-fluid mb-3 d-none" wire:ignore>
                    <div class="actions d-flex justify-content-center">
                        {{-- @if ($captureState == 'ready') --}}
                        <button class="btn btn-primary btn-sm btn-start" id="btn-start-capture-{{$mode}}" wire:click="$emit('startCapture', '{{$mode}}')" wire:ignore.self>
                            <i class="fa fa-camera mr-2" aria-hidden="true"></i> Mulai</button>
                        </button>
                        {{-- @elseif ($captureState == 'started') --}}
                        {{-- capture --}}
                        <button class="btn btn-primary btn-sm btn-capture d-none" id="btn-capture-{{$mode}}" wire:click="$emit('capture', '{{$mode}}')">
                            <i class="fa fa-camera mr-2" aria-hidden="true"></i> Ambil Gambar</button>
                        </button>
                        {{-- @elseif ($captureState == 'captured') --}}
                        {{-- refresh --}}
                        <button class="btn btn-warning btn-sm btn-retake d-none" id="btn-recapture-{{$mode}}" wire:click="$emit('startCapture', '{{$mode}}')">
                            <i class="fa fa-refresh mr-2" aria-hidden="true"></i> Ulangi</button>
                        </button>
                        {{-- check --}}
                        <button class="btn btn-success btn-sm btn-finish d-none" id="btn-finish-capture-{{$mode}}" wire:click="finishCapture" wire:loading.attr="disabled" wire:target="finishCapture">
                            <i class="fa fa-check mr-2" aria-hidden="true"></i> Selesai</button>
                        </button>
                        {{-- @endif --}}
                    </div>
                </li>
                @endif
            </ul>
            @if ($captureState == 'finished')
            <div class="text-center">
                @csrf
                <button class="btn btn-success btn-submit" wire:click="submitRecord" wire:loading.attr="disabled">
                    <span wire:loading wire:target="submitRecord">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        Loading...
                    </span>
                    <span wire:loading.remove wire:target="submitRecord">
                        <i class=" fa fa-upload mr-2" aria-hidden="true"></i>
                        <span>Submit</span>
                    </span>
                </button>
            </div>
            @endif
        </div>
        @endif
    </div>
</div>