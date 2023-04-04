<?php

namespace App\View\Components\Cuti;

use App\Models\Cuti;
use App\Models\Settings;
use Carbon\Carbon;
use Illuminate\View\Component;

class JatahCutiCard extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $data = null,
        public $enableAdd = false,
    )
    {
        //
    }

    // private function totalMapper($x)
    // {
    //     $tanggal = explode(',', $x->tanggal);
    //     $x->total_tahunan = 0;
    //     $x->total_besar = 0;
    //     $x->total_melahirkan = 0;
    //     $x->total_penting = 0;
    //     $x->total_ctln = 0;
    //     switch ($x->jenis) {
    //         case 'tahunan':
    //             $x->total_tahunan = count($tanggal);
    //             break;
    //         case 'besar':
    //             $x->total_besar = count($tanggal);
    //             break;
    //         case 'melahirkan':
    //             $x->total_melahirkan = count($tanggal);
    //             break;
    //         case 'penting':
    //             $x->total_penting = count($tanggal);
    //             break;
    //         case 'ctln':
    //             $x->total_ctln = count($tanggal);
    //             break;
    //         default:
    //             $x->total_tahunan = count($tanggal);
    //             break;
    //     }
    // }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $level = session()->get('user')->level;
        $data = $this->data;
        $enableAdd = $this->enableAdd;
        return view('components.cuti.jatah-cuti-card', compact('data', 'level', 'enableAdd'));
    }
}
