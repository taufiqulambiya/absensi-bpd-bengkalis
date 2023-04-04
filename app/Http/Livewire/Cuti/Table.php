<?php

namespace App\Http\Livewire\Cuti;

use Livewire\Component;

class Table extends Component
{
    public $data;
    public $editData;

    public $filter = [
        'start_date' => '',
        'end_date' => '',
    ];

    public function render()
    {
        return view('livewire.cuti.table');
    }

    public function edit($id)
    {
        $this->editData = $this->data->where('id', $id)->first();
    }
}