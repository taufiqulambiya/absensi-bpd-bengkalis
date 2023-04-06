<?php

namespace App\Http\Livewire\Absensi;

use Livewire\Component;

class Tabs extends Component
{
    public $activeTab = 'harian';
    public function render()
    {
        return view('livewire.absensi.tabs');
    }
    public function mount()
    {
        $this->activeTab = session('activeTabAbsensi') ?? 'harian';
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        session(['activeTabAbsensi' => $tab]);
    }

}