<?php

namespace App\View\Components\DinasLuar;

use App\Models\DinasLuar;
use Illuminate\View\Component;

class Aktif extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
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
        $user = session('user');
        $role = session('user')->level;
        $data = [];
        if ($role == 'pegawai') {
            $data = DinasLuar::where('id_user', $user->id)
                ->where('selesai', '>=', date('Y-m-d'))
                ->get()
                ->last();
            
        }
        return view('components.dinas-luar.aktif', compact('data'));
    }
}
