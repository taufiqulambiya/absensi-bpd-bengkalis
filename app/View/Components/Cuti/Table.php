<?php

namespace App\View\Components\Cuti;

use Illuminate\View\Component;

class Table extends Component
{
    public $user_role;
    public $data_cuti;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($data, $role)
    {
        $this->user_role = $role;
        $this->data_cuti = $data;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.cuti.table');
    }
}
