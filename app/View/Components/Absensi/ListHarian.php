<?php

namespace App\View\Components\Absensi;

use App\Models\Absensi;
use App\Models\Cuti;
use App\Models\Izin;
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
    public function __construct()
    {
        //
    }

    private function get_absensi()
    {
        $date = request()->tgl ?? date('Y-m-d');
        $db_user = User::all();
        $absensi = [];
        foreach ($db_user as $key => $value) {
            $find_absensi = Absensi::with(['user', 'shift'])
                ->where('tanggal', $date)
                ->where('id_user', $value->id)
                ->get()
                ->each(function ($x) {
                    $x->tanggal = Carbon::parse($x->tanggal)->format('d/m/Y');
                    $waktu_masuk = Carbon::parse($x->waktu_masuk);
                    $x->waktu_masuk = $waktu_masuk->format('H:i \W\I\B');
                    $waktu_keluar = Carbon::parse($x->waktu_keluar);
                    $x->waktu_keluar = $waktu_keluar->isMidnight() ? '-' : $waktu_keluar->format('H:i \W\I\B');
                    $x->total_jam = $waktu_masuk->diffInHours($waktu_keluar);

                    if ($x->shift) {
                        $x->jam_kerja = Carbon::parse($x->shift->mulai)->format('H:i') . ' - ' . Carbon::parse($x->shift->selesai)->format('H:i \W\I\B');

                        if ($x->forgotten) {
                            $x->total_jam = Carbon::parse($x->shift->selesai)->diff($waktu_masuk)->h;
                        }
                    }

                    $x->done = !$waktu_masuk->isMidnight() and !$waktu_keluar->isMidnight();
                })
                ->first();

            $status = $find_absensi->status ?? 'belum absen';
            if ($find_absensi and ($find_absensi->waktu_keluar != '-')) {
                $status = 'done';
            }
            $find_izin = Izin::where(['id_user' => $value->id, 'status' => 'accepted_pimpinan'])->get()->filter(function ($x) {
                $date = request()->tgl ?? date('Y-m-d');
                return $x->tgl_mulai <= $date and $x->tgl_selesai >= $date;
            });
            if ($find_izin->count() > 0) {
                $status = 'izin';
            }
            $find_cuti = Cuti::where(['id_user' => $value->id, 'status' => 'accepted_pimpinan'])->get()->filter(function ($x) {
                $tanggal = explode(',', $x->tanggal);
                $found = array_filter($tanggal, function ($y) {
                    $date = request()->tgl ?? date('Y-m-d');
                    return $y === $date;
                });
                return count($found) > 0;
            });
            if ($find_cuti->count() > 0) {
                $status = 'cuti';
            }

            $switch_color = [
                'belum absen' => 'black',
                'hadir' => 'info',
                'izin' => 'warning',
                'cuti' => 'warning',
                'dinas' => 'primary',
                'done' => 'success',
            ];

            $absensi[$key] = [
                'id' => $value->id,
                'nip' => $value->nip,
                'nama' => $value->nama,
                'absensi' => $find_absensi,
                'user' => $find_absensi->user ?? null,
                'status' => $status,
                'color' => $switch_color[$status],
            ];
        }

        if (request('status') == 'izin') {
            $absensi = array_filter($absensi, function ($x) {
                return $x['status'] == 'izin';
            });
        }
        if (request('status') == 'cuti') {
            $absensi = array_filter($absensi, function ($x) {
                return $x['status'] == 'cuti';
            });
        }
        if (request('status') == 'dinas_luar') {
            $absensi = array_filter($absensi, function ($x) {
                return $x['status'] == 'dinas';
            });
        }

        return $absensi;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $days_in_current_month = Carbon::now()->daysInMonth;
        $absensi = $this->get_absensi();
        $filter_status = [
            ['hadir', 'Hadir'],
            ['izin', 'Izin'],
            ['cuti', 'Cuti'],
            ['dinas_luar', 'Dinas Luar']
        ];

        return view('components.absensi.list-harian', compact('absensi', 'days_in_current_month', 'filter_status'));
    }
}
