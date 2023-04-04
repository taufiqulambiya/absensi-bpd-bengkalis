<?php

namespace App\View\Components\Absensi;

use Illuminate\View\Component;
use Carbon\Carbon;

class Detail extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $type = 'in',
        public $data = null,
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
        $type = $this->type;
        $data = $this->data;

        if (!$data) {
            return '';
        }

        $switch_data = [
            'in' => [
                'title' => 'Absensi Masuk',
                'color' => 'primary',
                'icon' => 'fa-sign-in-alt',
                'waktu' => Carbon::parse($data->waktu_masuk)->format('H:i \W\I\B'),
                'lokasi' => $data->lokasi_masuk,
                'jarak' => $data->jarak_masuk,
                'dok' => $data->dok_masuk,
            ],
            'out' => [
                'title' => 'Absensi Keluar',
                'color' => 'success',
                'icon' => 'fa-sign-out-alt',
                'waktu' => Carbon::parse($data->waktu_keluar)->format('H:i \W\I\B'),
                'lokasi' => $data->lokasi_keluar,
                'jarak' => $data->jarak_keluar,
                'dok' => $data->dok_keluar,
            ],
        ];

        return view('components.absensi.detail', compact('type', 'data', 'switch_data'));
    }
}