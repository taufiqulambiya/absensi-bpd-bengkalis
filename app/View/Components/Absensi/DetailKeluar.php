<?php

namespace App\View\Components\Absensi;

use Illuminate\View\Component;

class DetailKeluar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $current_absensi = [];
    public function __construct($current)
    {
        $this->current_absensi = $current;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.absensi.detail-keluar');
    }
}
