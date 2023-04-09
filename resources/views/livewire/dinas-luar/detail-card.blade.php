<div class="card mb-3">
    <div class="card-body">
        <div class="alert alert-info">
            <h4 class="alert-heading">Informasi</h4>
            <p class="mb-0">Anda memiliki dinas luar yang sedang berlangsung.</p>
        </div>
        <ul class="list-group">
            <li class="list-group-item">
                <div class="row">
                    <div class="col-4">Tanggal Mulai</div>
                    <div class="col-8">{{ $data->mulai }}</div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-4">Tanggal Selesai</div>
                    <div class="col-8">{{ $data->selesai }}</div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-4">Durasi</div>
                    <div class="col-8">{{ $data->durasi }}</div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <div class="col-4">File</div>
                    <div class="col-8">
                        @if (Storage::exists('public/dinas-luar/'.$data->file))
                            <a href="{{Storage::url('public/dinas-luar/'.$data->file)}}">File</a>    
                        @endif
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>