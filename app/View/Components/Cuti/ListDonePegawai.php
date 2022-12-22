<?php

namespace App\View\Components\Cuti;

use App\Models\Cuti;
use Illuminate\View\Component;

class ListDonePegawai extends Component
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
        $data = Cuti::where([
            ['id_user', $user->id],
            [function ($x) {
                return $x->where('status', '==', 'accepted_admin')->orWhere('status', '==', 'rejected');
            }]
        ])
            ->get()
            ->filter(function ($x) {
                $tanggal = explode(',', $x->tanggal);
                return date('Y-m-d') < $tanggal[0];
            })
            ->each(function ($x) {
                $x->status = mapStatus($x->status);
                $x->tanggal = explode(',', $x->tanggal);
            });
        return view('components.cuti.list-done-pegawai', compact('data'));
    }
}
