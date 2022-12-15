<?php

namespace App\View\Components\Absensi;

use Illuminate\View\Component;

class DetailMasuk extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $current_absensi = null;
    public function __construct($dataAbsensi)
    {
        $this->current_absensi = $dataAbsensi;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.absensi.detail-masuk');
    }
}
