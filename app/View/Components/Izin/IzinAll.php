<?php

namespace App\View\Components\Izin;

use Illuminate\View\Component;

class IzinAll extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $data = [],
    )
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $data = $this->data;
        return view('components.izin.izin-all', compact('data'));
    }
}
