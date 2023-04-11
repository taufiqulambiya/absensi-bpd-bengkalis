<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Models\JamKerja;
use App\Models\Absensi as ModelsAbsensi;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Barryvdh\DomPDF\Facade\Pdf;

class Absensi extends BaseController
{

    private function printHarian()
    {
        $print = request()->print;
        
        if ($print == 'all') {
            $tgl = request()->tgl ?? date('Y-m-d');
            $status = request()->status;

            $data = ModelsAbsensi::getByDateAdmin($tgl, $status);
            $tgl = date('d/m/Y', strtotime($tgl));
            // dd($data);
            $pdf = PDF::loadView('panel.admin.absensi.print.harian-all', compact('data', 'tgl', 'status'));
            return $pdf->stream('absensi.pdf');
        } else {
            $id = request()->id;
            $absensi = ModelsAbsensi::getRiwayatById($id, false) ?? abort(404);
            $user = User::find($absensi->id_user);
            $jam_kerja = JamKerja::getAktif();
            $data = compact('absensi', 'user', 'jam_kerja');
            $pdf = PDF::loadView('panel.pegawai.absensi.print.riwayat-id', $data);
            return $pdf->stream('absensi.pdf');
        }
    }

    private function printBulanan() {
        $month = request()->month ?? date('m');
        $year = request()->year ?? date('Y');

        $data = ModelsAbsensi::getByMonthAdmin($year, $month);
        $data = $data->map(function($item) {
            $item->absensi = $item->absensi->filter(function($a) {
                return $a->waktu_masuk ?? false;
            });
            return $item;
        });
        
        $title = "Laporan Absensi Bulanan: " . Carbon::createFromDate($year, $month)->format('F Y');
        $pdf = PDF::loadView('panel.admin.absensi.print.bulanan', compact('data', 'title'));
        return $pdf->stream('absensi.pdf');
    }

    public function index()
    {
        $view = request()->view ?? 'harian';
        $mode = request()->mode;
        $print = request()->print;

        if (!empty($print)) {
            $mode = request()->mode ?? 'harian';
            switch($mode) {
                case 'harian':
                    return $this->printHarian();
                case 'bulanan':
                    return $this->printBulanan();
            }
        }

        $data = [];

        if ($view == 'harian') {
            $days_in_current_month = Carbon::now()->daysInMonth;
            $absensi = $this->getHarian();
            $data = compact('absensi', 'days_in_current_month');
        } else {
            $bulanan = $this->getBulanan();
            $days = $bulanan['days'];
            $absensi = $bulanan['absensi'];
            $data = compact('absensi', 'days');
        }

        if ($mode == 'json') {
            return response()->json($data);
        }
        return view('panel.admin.absensi.absensi', $data);
    }

    private function getHarian()
    {
        $status = request()->status;
        $query = User::with(
            [
                'bidangs',
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
                        ->where('mulai', '<=', request()->tgl ?? date('Y-m-d'));
                },
                'dinas_luar' => function ($query) {
                    $query->where('mulai', '<=', request()->tgl ?? date('Y-m-d'))
                        ->where('selesai', '>=', request()->tgl ?? date('Y-m-d'));
                },
            ]
        );
        $query = $query->get()->map(function ($q) {
            $q->formatted_tanggal = Carbon::parse(request()->tgl ?? date('Y-m-d'))->format('d/m/Y');

            $q->absensi = $q->absensi->map(
                function ($abs) use ($q) {
                    $abs->formatted_tanggal = Carbon::parse($abs->tanggal)->format('d/m/Y');

                    $waktu_masuk = Carbon::parse($abs->waktu_masuk);
                    $waktu_keluar = Carbon::parse($abs->waktu_keluar);
                    $abs->formatted_waktu_masuk = $waktu_masuk->format('H:i \W\I\B');
                    $abs->formatted_waktu_keluar = $waktu_keluar->isMidnight() ? '-' : $waktu_keluar->format('H:i \W\I\B');

                    $shift_mulai = Carbon::parse($abs->shift->mulai);
                    $shift_selesai = Carbon::parse($abs->shift->selesai);

                    $abs->formatted_shift = $shift_mulai->format('H:i') . ' - ' . $shift_selesai->format('H:i \W\I\B');
                    $abs->total_jam = '-';

                    if ($abs->formatted_waktu_keluar != '-') {
                        $diff = $waktu_masuk->diffInHours($waktu_keluar);
                        $diff = $waktu_masuk->diffInHours($waktu_keluar) == 0 ? $waktu_masuk->diffInMinutes($waktu_keluar) : $diff;
                        $diff = $waktu_masuk->diffInMinutes($waktu_keluar) == 0 ? $waktu_masuk->diffInSeconds($waktu_keluar) : $diff;
                        $abs->total_jam = $diff . ' ' . ($waktu_masuk->diffInHours($waktu_keluar) == 0 ? ($waktu_masuk->diffInMinutes($waktu_keluar) == 0 ? 'detik' : 'menit') : 'jam');
                    }

                    $status = [
                        // 'belum absen' => $waktu_masuk->isMidnight() && $waktu_keluar->isMidnight(),
                        // 'sudah masuk' => $waktu_masuk->isMidnight() == false && $waktu_keluar->isMidnight(),
                        // 'done' => $waktu_masuk->isMidnight() == false && $waktu_keluar->isMidnight() == false,
                        // update: belum absen, sudah masuk, and done now just 'hadir'
                        'hadir' => $waktu_masuk->isMidnight() == false && $waktu_keluar->isMidnight() == false,
                        'izin' => $q->izin->count() > 0,
                        'cuti' => $q->cuti->count() > 0,
                        'dinas' => $q->dinas_luar->count() > 0,
                    ];
                    $status_color = [
                        // 'belum absen' => 'secodary',
                        // 'sudah masuk' => 'warning',
                        // 'done' => 'success',
                        // update: belum absen, sudah masuk, and done now just 'hadir'
                        'hadir' => 'success',
                        'izin' => 'info',
                        'cuti' => 'info',
                        'dinas' => 'info',
                    ];
                    $abs->status = array_search(true, $status);
                    $abs->status_color = $status_color[$abs->status];
                    return $abs;
                }
            );
            // return first data and to array
            $q->absensi = $q->absensi->first();
            return $q;
        });

        if ($status) {
            $query = $query->filter(function ($q) use ($status) {
                if ($status == 'all') {
                    return $q->absensi || !$q->absensi;
                }
                return $q->absensi && $q->absensi->status == $status;
            });
            // to array
            $query = $query->values();
        }

        return $query;
    }

    private function getBulanan()
    {
        $month = request()->bulan ?? date('m');
        $year = request()->year ?? date('Y');
        $start_of_month = Carbon::create($year, $month, 1);
        $end_of_month = $start_of_month->copy()->endOfMonth();
        $days = CarbonPeriod::create($start_of_month, $end_of_month);

        $absensi_bulanan = User::with([
            'absensi' => function ($query) use ($month, $year) {
                $query->whereYear('tanggal', $year);
                $query->whereMonth('tanggal', $month);
            }
        ], 'absensi.shift')
            ->get()
            ->each(function ($x) use ($days) {
                $x->absensi = $x->absensi
                    ->each(function ($y) {
                            $waktu_masuk = Carbon::parse($y->waktu_masuk);
                            $waktu_keluar = Carbon::parse($y->waktu_keluar);
                            $total_jam = '-';
                            if ($y->waktu_masuk && $y->waktu_keluar) {
                                // diff in hours
                                $total_jam = $waktu_masuk->diffInHours($waktu_keluar) . ' jam';
                                if ($total_jam <= 0) {
                                    // diff in minutes
                                    $total_jam = $waktu_masuk->diffInMinutes($waktu_keluar) . ' menit';
                                }
                            }
                            $y->total_jam = $total_jam;

                            if ($y->shift) {
                                $y->jam_absen = Carbon::parse($y->shift->mulai)->format('H:i') . ' - ' . Carbon::parse($y->shift->selesai)->format('H:i') . ' WIB';
                            }
                        })
                    ->filter(function ($abs) use ($days) {
                            return $days->contains($abs->tanggal);
                        });
            });

        return [
            'days' => $days,
            'absensi' => $absensi_bulanan,
        ];
    }
}