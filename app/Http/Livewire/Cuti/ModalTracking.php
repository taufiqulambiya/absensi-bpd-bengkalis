<?php

namespace App\Http\Livewire\Cuti;

use Livewire\Component;

class ModalTracking extends Component
{
    public $data;

    protected $listeners = [
        'updateTrackingModal' => 'updateTrackingModal',
    ];

    public function updateTrackingModal($data)
    {
        // if no key 'date' inside $data, then return empty array
        if (!array_key_exists('date', $data[0] ?? $data)) {
            $this->data = [];
            return;
        }
        // dd($data);
        $this->data = array_map(function ($item) {
            $obj = (object) $item;
            $obj->date_formatted = date('d/m/Y', strtotime($obj->date));
            $obj->datetime_formatted = date('d M Y H:i', strtotime($obj->date));
            return $obj;
        }, $data);
    }

    public function render()
    {
        return view('livewire.cuti.modal-tracking');
    }
}
