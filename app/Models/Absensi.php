<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    public function shift()
    {
        return $this->hasOne(JamKerja::class, 'id', 'id_jam');
    }

    static function getCurrentAbsensi($user_id)
    {
        $data = Absensi::with('shift')
            ->where([
                ['id_user', $user_id],
                ['tanggal', date('Y-m-d')],
            ])
            ->get()
            ->map(function ($item) {
                $item->formatted_shift = Carbon::parse($item->shift->mulai)->format('H:i') . ' - ' . Carbon::parse($item->shift->selesai)->format('H:i') . ' WIB';
                $item->formatted_tanggal = Carbon::parse($item->tanggal)->format('d/m/Y');

                $waktu_keluar = Carbon::parse($item->waktu_keluar);
                $item->has_out = $waktu_keluar->format('H:i') != '00:00';

                return $item;
            })
            ->last();
        return $data;
    }

    static function getMissedOut($user_id)
    {
        $data = Absensi::with('shift')
            ->where('id_user', $user_id)
            ->get()
            ->filter(function ($item) {
                $item->has_out = Carbon::parse($item->waktu_keluar)->format('H:i') != '00:00';
                $current_date = Carbon::now();
                $item_date = Carbon::parse($item->tanggal);
                $item->is_missed_out = $item->has_out == false && $current_date->diffInDays($item_date) > 0;

                return $item->is_missed_out;
            })
            ->last();
        return $data;
    }

    static function getRiwayat($isPaginate = false)
    {
        $userId = session('user')->id;
        $data = Absensi::with(['user', 'user.bidangs', 'shift'])
            ->where('id_user', $userId)
            ->orderBy('tanggal', 'desc');

        if ($isPaginate) {
            $data = $data->paginate(10);
        } else {
            $data = $data->paginate(10)
                ->map(function ($x) {
                    return formatAbsensi($x);
                });
        }

        return $data;
    }

    static function getRiwayatV2($isPaginate = false)
    {
        $userId = session('user')->id;
        $data = User::with([
            'absensi' => function ($q) {
                $q->orderBy('tanggal', 'desc');
            },
            'absensi.shift',
            'izin' => function ($q) {
                $q->where('status', 'accepted_pimpinan');
            },
            'cuti' => function ($q) {
                $q->where('status', 'accepted_pimpinan');
            },
            'dinas_luar',
        ])
            ->where('id', $userId)
            ->first();
        $data->absensi->each(function ($a) use ($data) {
            $a->status = 'belum absen keluar';
            
            $izin = $data->izin->filter(function ($i) use ($a) {
                return $i->tgl_mulai <= $a->tanggal && $i->tgl_selesai >= $a->tanggal;
            })->first();
            $dinas_luar = $data->dinas_luar->filter(function ($i) use ($a) {
                return $i->mulai <= $a->tanggal && $i->selesai >= $a->tanggal;
            })->first();
            $cuti = $data->cuti->filter(function ($i) use ($a) {
                $tgls = explode(',', $i->tanggal);
                return in_array($a->tanggal, $tgls);
            })->first();

            if ($izin) {
                $a->status = 'izin';
            } elseif ($cuti) {
                $a->status = 'cuti';
            } elseif ($dinas_luar) {
                $a->status = 'dinas luar';
            } elseif ($a->waktu_keluar != '00:00:00') {
                $a->status = 'hadir';
            }

            // format
            $a->formatted_shift = Carbon::parse($a->shift->mulai)->format('H:i') . ' - ' . Carbon::parse($a->shift->selesai)->format('H:i') . ' WIB';
            $a->formatted_tanggal = Carbon::parse($a->tanggal)->format('d/m/Y');
            $a->formatted_waktu_masuk = Carbon::parse($a->waktu_masuk)->format('H:i \W\I\B');
            $a->formatted_waktu_keluar = Carbon::parse($a->waktu_keluar)->format('H:i \W\I\B');
            // $a->total_jam = Carbon::parse($a->waktu_keluar)->diffInHours(Carbon::parse($a->waktu_masuk));
        });
        
        return $data;
    }

    static function getRiwayatById($idAbsensi, $filterUser = true)
    {
        $userId = session('user')->id;
        $data = Absensi::with(['user', 'user.bidangs', 'shift'])
            ->when($filterUser, function ($q) use ($userId) {
                $q->where('id_user', $userId);
            })
            ->where('id', $idAbsensi)
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($x) {
                return formatAbsensi($x);
            })
            ->first();

        return $data;
    }

    static function getByDateAdmin($date, $status)
    {
        $query = User::with([
            'absensi' => function ($q) use ($date) {
                $q->where('tanggal', $date);
            },
            'absensi.shift',
            'bidangs',
            'izin' => function ($q) use ($date) {
                $q->where('tgl_mulai', '<=', $date)
                    ->where('tgl_selesai', '>=', $date)
                    ->where('status', 'accepted_pimpinan');
            },
            'cuti' => function ($q) use ($date) {
                $q->where('tanggal', 'like', $date . '%')
                    ->where('status', 'accepted_pimpinan');
            },
            'dinas_luar' => function ($q) use ($date) {
                $q->where('mulai', '<=', $date)
                    ->where('selesai', '>=', $date);
            },
        ]);

        $actions = [
            'belum_absen_keluar' => ['info'],
            'hadir' => ['info', 'print'],
            'izin' => [],
            'cuti' => [],
            'dinas_luar' => [],
        ];
        $data = $query->get()
            ->each(function ($x) use ($date, $actions) {
                $x->formatted_tanggal = Carbon::parse($date)->format('d/m/Y');
                $x->absensiRaw = $x->absensi->first() ?? null;
                $x->absensiRaw = $x->absensiRaw ? Absensi::formatAbsensi($x->absensiRaw) : null;

                $x->status = Absensi::getStatus($x, $date);
                $x->actions = $actions[$x->status] ?? [];
                $x->statusHtml = Absensi::getStatusHtml($x, $date);
            });
        if ($status != 'all') {
            $data = $data->filter(function ($item) use ($status) {
                return $item->status == $status;
            });
        }

        return $data;
    }

    static function getByMonthAdmin($year, $month) {
        // year = "2023", month = "04" - format
        $datesInMonth = Carbon::parse($year . '-' . $month)->daysInMonth;
        $dates = [];
        for ($i = 1; $i <= $datesInMonth; $i++) {
            $dates[] = $year . '-' . $month . '-' . str_pad($i, 2, '0', STR_PAD_LEFT);
        }

        $data = User::with('bidangs')->get()->map(function ($x) use ($dates) {
            $x->absensi = collect();
            $x->izin = collect();
            $x->cuti = collect();
            $x->dinas_luar = collect();
            foreach ($dates as $date) {
                $a = Absensi::with('shift')->where('id_user', $x->id)->where('tanggal', $date)->first();
                $i = Izin::where('id_user', $x->id)->where('tgl_mulai', '<=', $date)->where('tgl_selesai', '>=', $date)->where('status', 'accepted_pimpinan')->first();
                $x->izin->push($i);
                $c = Cuti::where('id_user', $x->id)->where('tanggal', 'like', $date . '%')->where('status', 'accepted_pimpinan')->first();
                $x->cuti->push($c);
                $d = DinasLuar::where('id_user', $x->id)->where('mulai', '<=', $date)->where('selesai', '>=', $date)->first();
                $x->dinas_luar->push($d);

                if ($i) {
                    $a = (object) [
                        'status' => 'izin',
                        'td_class' => 'bg-info text-white',
                        'izin' => $i,
                        'show_text' => 'Izin',
                    ];
                } else if ($c) {
                    $a = (object) [
                        'status' => 'cuti',
                        'td_class' => 'bg-info text-white',
                        'cuti' => $c,
                        'show_text' => 'Cuti',
                    ];
                } else if ($d) {
                    $a = (object) [
                        'status' => 'dinas_luar',
                        'td_class' => 'bg-info text-white',
                        'dinas_luar' => $d,
                        'show_text' => 'Dinas Luar',
                    ];
                } else if ($a) {
                    $hasOut = $a->waktu_keluar != null && Carbon::parse($a->waktu_keluar)->format('H:i') != '00:00';
                    $a->status = $hasOut ? 'hadir' : 'belum_absen_keluar';
                    $a->td_class = $hasOut ? 'bg-success text-white cursor-pointer' : 'bg-primary text-white cursor-pointer';
                    $format_waktu_masuk = Carbon::parse($a->waktu_masuk)->format('H:i \W\I\B');
                    $format_waktu_keluar = Carbon::parse($a->waktu_keluar)->format('H:i \W\I\B');
                    $a->show_text = $hasOut ? $format_waktu_keluar : $format_waktu_masuk;
                    $a->clickable = true;
                } else {
                    $currentDate = Carbon::now()->format('Y-m-d');
                    $isAfterOrEq = Carbon::parse($date)->isAfter($currentDate) || Carbon::parse($date)->eq($currentDate);
                    $a = (object) [
                        'status' => $isAfterOrEq ? 'belum_absen' : 'tidak_hadir',
                        'td_class' => $isAfterOrEq ? '' : 'bg-secondary',
                        'show_text' => '',
                    ];
                }
                $a = ($a->tanggal ?? false) ? Absensi::formatAbsensi($a) : $a;
                $x->absensi->push($a);
            }
            return $x;
        });
        // dd($data);
        return $data;
    }

    static function formatAbsensi($data)
    {
        $data->tanggal = Carbon::parse($data->tanggal)->format('d/m/Y');

        $waktu_masuk = Carbon::parse($data->waktu_masuk);
        $waktu_keluar = Carbon::parse($data->waktu_keluar);
        $data->waktu_masuk = $waktu_masuk->format('H:i \W\I\B');
        $data->waktu_keluar = $waktu_keluar->format('H:i \W\I\B');
        $data->total_jam = $waktu_masuk->diffInHours($waktu_keluar) . ' jam';
        $data->show_waktu_keluar = $waktu_keluar->format('H:i') != '00:00';

        $shift = JamKerja::find($data->id_jam);
        $data->shift = $shift ? Carbon::parse($shift->mulai)->format('H:i') . ' - ' . Carbon::parse($shift->selesai)->format('H:i') . ' WIB' : null;

        return $data;
    }

    static function getStatus($data, $date) {
        if ($data->absensi->count() > 0) {
            $raw = $data->absensi->first();
            $waktu_keluar = Carbon::parse($raw->waktu_keluar);
            
            if ($waktu_keluar->format('H:i') == '00:00') {
                return 'belum_absen_keluar';
            }

            return 'hadir';
        }

        if ($data->izin->count() > 0) {
            return 'izin';
        }

        if ($data->cuti->count() > 0) {
            return 'cuti';
        }

        if ($data->dinas_luar->count() > 0) {
            return 'dinas_luar';
        }

        return 'tidak_hadir';
    }

    static function getStatusHtml($data, $date) {
        if ($data->absensi->count() > 0) {
            $raw = $data->absensi->first();
            $waktu_keluar = Carbon::parse($raw->waktu_keluar);
            
            if ($waktu_keluar->format('H:i') == '00:00') {
                return '<span class="badge badge-secondary">Belum Absen Keluar</span>';
            }

            return '<span class="badge badge-primary">Hadir</span>';
        }

        if ($data->izin->count() > 0) {
            return '<span class="badge badge-info">Izin</span>';
        }

        if ($data->cuti->count() > 0) {
            return '<span class="badge badge-warning">Cuti</span>';
        }

        if ($data->dinas_luar->count() > 0) {
            return '<span class="badge badge-success">Dinas Luar</span>';
        }


        return '<span class="badge badge-danger">Tidak Hadir</span>';
    }
}