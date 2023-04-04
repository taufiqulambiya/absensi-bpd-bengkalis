<?php

namespace App\View\Components\Absensi;

use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\View\Component;

class ListHarian extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        public $data = [],
    )
    {
        //
    }

    private function get_absensi()
    {
        $query = User::with(
            [
                'absensi' => function ($query) {
                    $query->where('tanggal', 'like', '%' . (request()->tgl ?? date('Y-m-d')) . '%');
                },
                'absensi.shift',
                'izin' => function ($query) {
                    $query->where('status', 'accepted_pimpinan')
                        ->where('tgl_mulai', '<=', request()->tgl ?? date('Y-m-d'));
                },
                'cuti' => function ($query) {
                    $query->where('status', 'accepted_pimpinan')
                        ->where('tanggal', 'like', '%' . (request()->tgl ?? date('Y-m-d')) . '%');
                },
                'dinas_luar' => function ($query) {
                    $query->where('mulai', '<=', request()->tgl ?? date('Y-m-d'))
                        ->where('selesai', '>=', request()->tgl ?? date('Y-m-d'));
                },
            ]
        );
        $query = $query->get()->map(function($q) {
            $q->formatted_tanggal = Carbon::parse(request()->tgl ?? date('Y-m-d'))->format('d/m/Y');

            $q->absensi = $q->absensi->map(function($abs) use($q) {
                $waktu_masuk = Carbon::parse($abs->waktu_masuk);
                $waktu_keluar = Carbon::parse($abs->waktu_keluar);
                $abs->formatted_waktu_masuk = $waktu_masuk->format('H:i \W\I\B');
                $abs->formatted_waktu_keluar = $waktu_keluar->isMidnight() ? '-' : $waktu_keluar->format('H:i \W\I\B');

                $shift_mulai = Carbon::parse($abs->shift->mulai);
                $shift_selesai = Carbon::parse($abs->shift->selesai);

                $abs->formatted_shift = $shift_mulai->format('H:i') . ' - ' . $shift_selesai->format('H:i \W\I\B');
                $abs->total_jam = '-';

                if ($abs->formatted_waktu_keluar != '-') {
                    $abs->total_jam = $waktu_masuk->diffInHours($waktu_keluar);
                }


                $status = [
                    'belum absen' => $waktu_masuk->isMidnight() && $waktu_keluar->isMidnight(),
                    'sudah masuk' => $waktu_masuk->isMidnight() == false && $waktu_keluar->isMidnight(),
                    'done' => $waktu_masuk->isMidnight() == false && $waktu_keluar->isMidnight() == false,
                    'izin' => $q->izin->count() > 0,
                    'cuti' => $q->cuti->count() > 0,
                    'dinas' => $q->dinas_luar->count() > 0,
                ];
                $status_color = [
                    'belum absen' => 'secodary',
                    'sudah masuk' => 'warning',
                    'done' => 'success',
                    'izin' => 'info',
                    'cuti' => 'info',
                    'dinas' => 'info',
                ];
                $abs->status = array_search(true, $status);
                $abs->status_color = $status_color[$abs->status];
                return $abs;
            });
            // return first data and to array
            $q->absensi = $q->absensi->first();
            return $q;
        });

        return $query;
        // return $absensi;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        // $days_in_current_month = Carbon::now()->daysInMonth;
        // $absensi_by_user = $this->get_absensi();
        // $filter_status = [
        //     ['hadir', 'Hadir'],
        //     ['izin', 'Izin'],
        //     ['cuti', 'Cuti'],
        //     ['dinas_luar', 'Dinas Luar']
        // ];
        // $data_json = json_encode($absensi_by_user);

        $data = $this->data;
        return view('components.absensi.list-harian', compact('data'));
    }
}
