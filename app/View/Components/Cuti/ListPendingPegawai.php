<?php

namespace App\View\Components\Cuti;

use App\Models\Cuti;
use Illuminate\View\Component;

class ListPendingPegawai extends Component
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
        $data = Cuti::where('id_user', $user->id)
            ->where('status', 'pending')
            ->orWhere('status', 'accepted_kabid')
            ->get()
            ->filter(function ($x) {
                $tanggal = explode(',', $x->tanggal);
                return date('Y-m-d') < $tanggal[0];
            })
            ->each(function ($x) {
                $x->status = mapStatus($x->status);
                $x->tanggal = explode(',', $x->tanggal);
            });
        return view('components.cuti.list-pending-pegawai', compact('data'));
    }
}
