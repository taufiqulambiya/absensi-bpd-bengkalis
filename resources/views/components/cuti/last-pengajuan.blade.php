<style>
    .lpc-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        padding: 1em;
        grid-gap: 1rem;
        grid-gap: 14px;
        margin-bottom: 14px;
    }
    @media screen and (max-width: 991px) {
        .lpc-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<div class="lpc-grid">
    <x-cuti.detail-card />

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
    </style>
    <div class="card">
        <div class="card-body">
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <h5>Pengajuan Cuti terakhir</h5>
                @if (Route::current()->uri != 'panel/cuti')
                <a href="{{ route('cuti.index') }}" class="float-right more"><i class="fas fa-eye"></i></a>
                @endif
            </div>
            <table class="table">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Total</th>
                        <th>Status</th>
                        @if (Route::current()->uri == 'panel/cuti')
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td scope="row" class="position-relative">
                            <div class="color-label bg-{{ $data->status['color'] }}"></div>
                            {{ join(", ", $data->tanggal) }}
                        </td>
                        <td>{{ count($data->tanggal) }} Hari</td>
                        <td>{{ $data->status['text'] }}</td>
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
    @endif
</div>