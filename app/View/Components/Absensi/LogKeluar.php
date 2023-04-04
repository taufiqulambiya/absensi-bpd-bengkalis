<?php

namespace App\View\Components\Absensi;

use App\Models\Absensi;
use App\Models\JamKerja;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\View\Component;

class LogKeluar extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $disable_log = false;
    public function __construct(
        public $disableLog = false,
        public $missedOut = null,
        public $jamKerja = null,
        public $currentAbsensi = null,
    )
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $current_absensi = $this->currentAbsensi;

        // if (Carbon::parse($current_absensi->waktu_keluar)->format('H:i') == '00:00') {
        //     $current_absensi = null;
        // }
        return view('components.absensi.log-keluar', compact('current_absensi'));
    }
}
