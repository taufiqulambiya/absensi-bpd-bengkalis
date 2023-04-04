<?php

namespace App\Http\Livewire\Izin;

use App\Models\Izin;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Tabs extends Component
{
    public $data = [];
    public $dataEdit = [];
    public $activeTab = 'pending';
    public $trackData = [];
    public $showInfo = true;
    
    protected $listeners = ['procceedDeleteIzin','procceedAccIzin', 'procceedRejectIzin'];

    public function changeTab($tab) {
        $this->activeTab = $tab;
        $this->data = Izin::getByStatus($this->activeTab);
        session(['activeTab' => $tab]);

        if (count($this->data) > 0) {
            $this->emit('initDataTable');
        }
    }

    public function render()
    {
        $level = session('user')->level;
        return view('livewire.izin.tabs', compact('level'));
    }

    public function mount()
    {
        $this->activeTab = session('activeTab') ?? 'pending';
        $this->data = Izin::getByStatus($this->activeTab);
        if (count($this->data) > 0) {
            $this->emit('initDataTable');
        }

        // unset cookie
        $userLevel = session('user')->level;
        if ($userLevel =='kabid') {
            $this->showInfo = !isset($_COOKIE['closeInfo']);
        }
    }

    public function showTracking($id) {
        $item = Izin::find($id);
        // convert json to object
        $tracking = json_decode($item->tracking);
        $this->data = Izin::getByStatus($this->activeTab);
        // emit event to show modal
        $this->emit('updateTrackingModal', $tracking);
    }

    public function updateModalEdit($id) {
        $item = Izin::find($id);
        $this->data = Izin::getByStatus($this->activeTab);
        $this->emit('updateEditModal', $item);
    }

    public function procceedDeleteIzin($id) {
        $item = Izin::find($id);

        // delete file, if exist
        $is_file_exist = Storage::disk('public')->exists('izin/'.$item->bukti);
        if ($is_file_exist) {
            Storage::disk('public')->delete('izin/'.$item->bukti);
        }
        // delete data
        $item->delete();
        // update data
        $this->data = Izin::getByStatus($this->activeTab);
        // emit success message
        $this->emit('success', 'Data berhasil dihapus');
    }

    public function procceedAccIzin($id) {
        $item = Izin::find($id);
        $level = session('user')->level;

        $switch = [
            'kabid' => 'accepted_kabid',
            'admin' => 'accepted_admin',
            'atasan' => 'accepted_pimpinan',
        ];
        $switchMessage = [
            'kabid' => 'Diterima oleh Kabid',
            'admin' => 'Diterima oleh Admin',
            'atasan' => 'Diterima oleh Atasan',
        ];
        $tracking = json_decode($item->tracking);
        array_push($tracking, [
            'date' => date('Y-m-d H:i:s'),
            'status' => $switchMessage[$level],
        ]);
        $item->tracking = json_encode($tracking);
        $item->status = $switch[$level];
        $item->save();
        $this->data = Izin::getByStatus($this->activeTab);
        $this->emit('success', 'Izin berhasil disetujui');
    }

    public function closeInfo() {
        $this->showInfo = false;
        $this->data = Izin::getByStatus($this->activeTab);
        // set cookie to close info, expire in 30 days
        setcookie('closeInfo', true, time() + (60*24*30), "/");
    }
}
