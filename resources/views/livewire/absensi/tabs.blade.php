<div class="card">
    <div class="card-header">
        <ul class="nav nav-tabs nav-stacked mb-3">
            <li class="nav-item">
                <a href="#" wire:click="setActiveTab('harian')" class="nav-link @if ($activeTab == 'harian')
                    active
                @endif">Harian</a>
            </li>
            <li class="nav-item">
                <a href="#" wire:click="setActiveTab('bulanan')" class="nav-link @if ($activeTab == 'bulanan')
                    active
                @endif">Bulanan</a>
            </li>
        </ul>
    </div>
    <div class="card-body">
        @if ($activeTab == 'harian')
            <livewire:absensi.list-harian />
        @else
            <livewire:absensi.list-bulanan />
        @endif
    </div>
</div>