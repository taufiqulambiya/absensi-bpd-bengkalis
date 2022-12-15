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
        function filterMonth($x)
        {
            $s = Carbon::now()->startOfMonth()->format('Y-m-d');
            $e = Carbon::now()->endOfMonth()->format('Y-m-d');

            if (request()->has('bulan')) {
                $s = Carbon::now()->setMonth(request('bulan'))->startOfMonth();
                $e = Carbon::now()->setMonth(request('bulan'))->endOfMonth();
            }
            return $x->whereBetween('tanggal', [$s, $e]);
        }
        function mapAbsensi($x)
        {
            $x->each(function ($y) {
                $y->waktu_masuk = Carbon::parse($y->waktu_masuk)->format('H:i \W\I\B');
                $has_out = Carbon::parse($y->waktu_keluar)->isMidnight();
                if($has_out) {
                    $y->has_out = false;
                } else {
                    $y->waktu_keluar = Carbon::parse($y->waktu_keluar)->format('H:i \W\I\B');
                    $y->has_out = true;
                }
            });
        }

        $absensi = User::with(['absensi' => function ($x) {
            filterMonth($x);
        }])
            ->get()
            ->each(function ($x) {
                mapAbsensi($x->absensi);
            });
        $days = [];
        $period = CarbonPeriod::between(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());
        if (request()->has('bulan')) {
            $s = Carbon::now()->setMonth(request('bulan'))->startOfMonth();
            $e = Carbon::now()->setMonth(request('bulan'))->endOfMonth();
            $period = CarbonPeriod::between($s, $e);
        }
        foreach ($period as $key => $value) {
            array_push($days, Carbon::parse($value)->format('Y-m-d'));
        }

        return view('components.absensi.list-bulanan', compact('absensi', 'days'));
    }
}
