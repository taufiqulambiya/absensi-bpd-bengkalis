<?php

namespace App\View\Components\Modal;

use Illuminate\View\Component;

class Delete extends Component
{
    public $id;
    public $title;
    public $desc;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($id, $title, $desc)
    {
        $this->id = $id;
        $this->title = $title;
        $this->desc = $desc;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.modal.delete');
    }
}
