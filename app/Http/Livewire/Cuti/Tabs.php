<?php

namespace App\Http\Livewire\Cuti;

use Livewire\Component;
use \App\Models\Cuti;

class Tabs extends Component
{
    public $active_tab = 'pending';
    public $dataCuti;
    public $editData;
    public $trackData;

    // listeners
    protected $listeners = ['accCuti', 'rejectCuti', 'deleteCuti'];

    public $filter = [
        'date_start' => '',
        'date_end' => '',
    ];

    public function updatedFilter()
    {
        $this->dataCuti = Cuti::getByStatusAndRole($this->active_tab, $this->filter);
    }

    public function changeTab($tab)
    {
        session(['activeTabCuti' => $tab]);
        $this->active_tab = $tab;
        $this->dataCuti = Cuti::getByStatusAndRole($tab, $this->filter);

        if (count($this->dataCuti) > 0) {
            $this->emit('initDataTable');
        }
    }

    public function edit($id)
    {
        $found = $this->dataCuti->where('id', $id)->first();
        $this->emit('updateEditModal', $found);
        $this->dataCuti = Cuti::getByStatusAndRole($this->active_tab, $this->filter);
    }

    public function track($id)
    {
        $found = $this->dataCuti->where('id', $id)->first();
        $this->emit('updateTrackingModal', json_decode($found->tracking));
        $this->dataCuti = Cuti::getByStatusAndRole($this->active_tab, $this->filter);
    }

    public function render()
    {
        $level = session('user')->level;
        $data = [
            'level' => $level,
            'active_tab' => $this->active_tab,
            'dataCuti' => $this->dataCuti,
        ];
        return view('livewire.cuti.tabs')->with($data);
    }

    public function accCuti($id)
    {
        $level = session('user')->level;
        $cuti = Cuti::find($id);

        $switch = [
            'admin' => 'accepted_admin',
            'kabid' => 'accepted_kabid',
            'atasan' => 'accepted_pimpinan',
        ];
        $tracking = json_decode($cuti->tracking);
        $track = [
            'status' => 'Pengajuan diterima oleh ' . strtoupper($level),
            'date' => date('Y-m-d H:i:s'),
        ];
        $tracking[] = $track;
        $cuti->tracking = json_encode($tracking);

        $cuti->status = $switch[$level];

        $cuti->save();

        $this->dataCuti = Cuti::getByStatusAndRole($this->active_tab, $this->filter);
        $this->emit('successAccCuti');
    }

    public function rejectCuti($id)
    {
        $level = session('user')->level;
        $cuti = Cuti::find($id);

        $tracking = json_decode($cuti->tracking);
        $track = [
            'status' => 'Pengajuan ditolak oleh ' . strtoupper($level),
            'date' => date('Y-m-d H:i:s'),
        ];
        $tracking[] = $track;
        $cuti->tracking = json_encode($tracking);

        $cuti->status = "rejected";

        $cuti->save();

        $this->dataCuti = Cuti::getByStatusAndRole($this->active_tab, $this->filter);
        $this->emit('successRejectCuti');
    }

    public function deleteCuti($id)
    {
        $cuti = Cuti::find($id);
        $cuti->delete();

        $this->dataCuti = Cuti::getByStatusAndRole($this->active_tab, $this->filter);
        $this->emit('successDeleteCuti');
        $this->emit('rerenderJatahCuti');
    }

    public function mount()
    {
        $this->active_tab = session('activeTabCuti') ?? 'pending';
        $this->dataCuti = Cuti::getByStatusAndRole($this->active_tab);
        if (count($this->dataCuti) > 0) {
            $this->emit('initDataTable');
        }
    }
}