<?php

namespace App\View\Components\Cuti;

use Illuminate\View\Component;

class CutiAll extends Component
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
        return view('components.cuti.cuti-all', ['data' => $this->data]);
    }
}
