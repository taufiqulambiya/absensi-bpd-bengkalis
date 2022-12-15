<?php

namespace App\View\Components\Cuti;

use App\Models\Cuti;
use Illuminate\Support\Facades\Route;
use Illuminate\View\Component;

class DetailCard extends Component
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
        $has_cuti = Cuti::where('id_user', $user->id)
            ->where('status', 'accepted_pimpinan')
            ->get()
            ->filter(function ($x) {
                $tanggal = explode(',', $x->tanggal);
                $found = array_filter($tanggal, function ($y) {
                    $current_route = Route::current();
                    $in_cuti_page = $current_route->uri == 'panel/cuti';
                    $current_date = date('Y-m-d');
                    if ($in_cuti_page) {
                        return $y > $current_date;
                    }
                    return $y == $current_date;
                });
                return count($found) > 0;
            })
            ->each(function ($x) {
                $x->tanggal = explode(',', $x->tanggal);
            });
        return view('components.cuti.detail-card', compact('has_cuti'));
    }
}
