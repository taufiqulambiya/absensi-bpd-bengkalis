<div class="card mb-3 {{ $bgClass }} {{ $textClass }}">
    <div class="card-body">
        <div>
            <h4 class="card-title d-inline-block">{{ $title }}</h4>
            <i class="{{ $iconClass }} float-right" style="font-size: 32px"></i>
        </div>
        <hr />
        <div class="display-4 font-weight-bold">{{ $count }}</div>
        @if ($pendingCount > 0)
        <div class="text-primary">{{ $pendingCount }} Pengajuan Pending</div>
        @endif
        <hr />
        <a href="{{ $link }}" class="btn btn-light btn-sm"><i class="fas fa-database fa-fw"></i>
            Detail</a>
    </div>
</div>