<?php

namespace App\View\Components\Absensi;

use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\View\Component;

class ListBulanan extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $data = [],
        public $days = [],
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
        $days = $this->days;
        return view('components.absensi.list-bulanan', compact('data', 'days'));
    }
}
