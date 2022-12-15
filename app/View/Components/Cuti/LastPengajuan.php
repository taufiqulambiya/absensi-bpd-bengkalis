<?php

namespace App\View\Components\Cuti;

use App\Models\Cuti;
use Carbon\Carbon;
use Illuminate\View\Component;

class LastPengajuan extends Component
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

    private function filterDate($x)
    {
        $tanggal = explode(',', $x->tanggal);
        $found = array_filter($tanggal, function ($y) {
            $current_date = date('Y-m-d');
            return $y > $current_date;
        });
        return count($found) > 0;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $user = session('user');
        $data = Cuti::where(['id_user' => $user->id])
            ->get()
            ->filter(function ($x) {
                return $this->filterDate($x);
            })
            ->each(function ($x) {
                $tgl = explode(',', $x->tanggal);
                $x->tanggal = array_map(function($y) {
                    return Carbon::parse($y)->format('d/m/Y');
                }, $tgl);
                $x->status = mapStatus($x->status);
                $x->can_cancel = Carbon::parse($x->tgl_mulai)->isAfter(Carbon::now());
            })
            ->last();
        return view('components.cuti.last-pengajuan', compact('data'));
    }
}
