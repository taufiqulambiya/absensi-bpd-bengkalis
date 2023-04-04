@if ($data)
<style>
    .color-label {
        position: absolute;
        left: 0;
        top: 0;
        height: 100%;
        width: 4px;
        content: "";
    }

    tr td {
        vertical-align: middle;
    }

    a.more {
        font-size: 24px;
    }

    .lpi-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        padding: 1em;
        grid-gap: 14px;
        margin-bottom: 14px;
    }

    @media screen and (max-width: 991px) {
        .lpi-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<div class="lpi-grid">
    <div class="card">
        <div class="card-body">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h5>Pengajuan Izin terakhir</h5>
                @if (Route::current()->uri != 'panel/izin')
                <a href="{{ route('izin.index') }}" class="float-right more"><i class="fas fa-eye"></i></a>
                @endif
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Mulai</th>
                        <th>Hingga</th>
                        <th>Total Hari</th>
                        <th>Status</th>
                        @if (Route::current()->uri == 'panel/izin')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row">
                            <div class="color-label bg-{{ $data->status_color }}"></div>

                            {{ $data->tgl_mulai }}
                        </td>
                        <td>{{ $data->tgl_selesai }}</td>
                        <td>{{ $data->total_hari }}</td>
                        <td>{{ $data->status_text }}</td>
                        @if (Route::current()->uri == 'panel/izin')
                        <td>
                            @if ($data->can_cancel)
                            <button class="btn btn-danger" id="btn-cancel-incoming" data-id="{{ $data->id }}">
                                <i class="fas fa-times"></i>
                            </button>
                            @else
                            <button class="btn btn-secondary" onclick="showErrorAlert('Izin tidak dapat dibatalkan.')">
                                <i class="fas fa-times"></i>
                            </button>
                            @endif
                        </td>
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endif