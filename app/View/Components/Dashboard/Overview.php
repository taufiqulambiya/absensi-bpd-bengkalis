<?php

namespace App\View\Components\Dashboard;

use Illuminate\View\Component;

class Overview extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $bgClass = 'bg-primary',
        public $textClass = 'text-white',
        public $iconClass = 'fas fa-chart-area',
        public $title = 'Overview',
        public $count = 0,
        public $pendingCount = 0,
        public $link = '#',
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
        return view('components.dashboard.overview');
    }
}
