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
        session(['activeTabIzin' => $tab]);

        // if (count($this->data) > 0) {
        $this->emit('initDataTable');
        // }
    }

    public function render()
    {
        $level = session('user')->level;
        return view('livewire.izin.tabs', compact('level'));
    }

    public function mount()
    {
        $this->activeTab = session('activeTabIzin') ?? 'pending';
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
        $this->emit('success', 'Data berhasil dihapus', true);
    }

    private function getTracking($tracking, $type = 'acc') {
        $level = session('user')->level;
        $switch = [
            'kabid' => $type == 'acc' ? 'accepted_kabid' : 'rejected',
            'admin' => $type == 'acc' ? 'accepted_admin' : 'rejected',
            'atasan' => $type == 'acc' ? 'accepted_pimpinan' : 'rejected',
        ];
        $switchMessage = [
            'kabid' => $type == 'acc' ? 'Disetujui oleh Kabid' : 'Ditolak oleh Kabid',
            'admin' => $type == 'acc' ? 'Disetujui oleh Admin' : 'Ditolak oleh Admin',
            'atasan' => $type == 'acc' ? 'Disetujui oleh Pimpinan' : 'Ditolak oleh Pimpinan',
        ];
        $tracking = json_decode($tracking);
        array_push($tracking, [
            'date' => date('Y-m-d H:i:s'),
            'status' => $switchMessage[$level],
        ]);

        return [
            'tracking' => json_encode($tracking),
            'status' => $switch[$level],
        ];
    }

    public function procceedAccIzin($id) {
        $item = Izin::find($id);
        
        $item->tracking = $this->getTracking($item->tracking)['tracking'];
        $item->status = $this->getTracking($item->tracking)['status'];
        $item->save();
        $this->data = Izin::getByStatus($this->activeTab);
        $this->emit('success', 'Izin berhasil disetujui', true);
    }

    public function procceedRejectIzin($id) {
        $item = Izin::find($id);
        
        $item->tracking = $this->getTracking($item->tracking, 'reject')['tracking'];
        $item->status = $this->getTracking($item->tracking, 'reject')['status'];
        $item->save();
        $this->data = Izin::getByStatus($this->activeTab);
        $this->emit('success', 'Izin berhasil ditolak', true);
    }

    public function closeInfo() {
        $this->showInfo = false;
        $this->data = Izin::getByStatus($this->activeTab);
        // set cookie to close info, expire in 30 days
        setcookie('closeInfo', true, time() + (60*24*30), "/");
    }
}
