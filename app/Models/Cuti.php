<?php

namespace App\Models;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Settings;

class Cuti extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'cuti';

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }

    static public function lastPengajuan($user_id)
    {
        $data = Cuti::where('id_user', $user_id)
            ->where('status', 'accepted_pimpinan')
            // ->where('tanggal', 'like', '%' . date('Y-m-d') . '%')
            ->orderBy('id', 'desc')
            ->first();
        return $data;
    }

    static function getNotAllowedDates($user_id)
    {
        // get cuti forward from current date
        $current_date = date('Y-m-d');
        $cuti = Cuti::where('id_user', $user_id)
            ->where('status', 'accepted_pimpinan')
            ->get()
            ->filter(function ($item) use ($current_date) {
                $item->tanggal = explode(',', $item->tanggal);
                // get the lowest using math
                $min = min(array_map(function ($item) {
                    return strtotime($item);
                }, $item->tanggal));
                return $min >= strtotime($current_date);
            });

        $notAllowedDates = [];
        foreach ($cuti as $item) {
            $formatted = array_map(function ($item) {
                return Carbon::parse($item)->format('m/d/Y');
            }, $item->tanggal);
            $notAllowedDates = array_merge($notAllowedDates, $formatted);
        }

        return $notAllowedDates;
    }

    static function getIsAllowedAjukan($user_id)
    {
        $current_date = date('Y-m-d');
        $data = Cuti::where('id_user', $user_id)
            ->whereNotIn('status', ['accepted_pimpinan', 'rejected'])
            ->where('mulai', '>=', $current_date)
            ->get()
            ->count() > 0;
        return $data;
    }

    static function getDisableDates($user_id)
    {
        $incoming = Cuti::where('id_user', $user_id)
            ->where('status', 'accepted_pimpinan')
            // ->map(function ($item) {
            //     $item->tanggal = explode(',', $item->tanggal);
            //     return $item;
            // })
            // ->map(function ($item) {
            //     return array_map(function ($item) {
            //         return Carbon::parse($item)->format('m/d/Y');
            //     }, $item->tanggal);
            // })
            // ->filter(function ($item) {
            //     return strtotime($item) > strtotime(date('m/d/Y'));
            // })
            ->where('mulai', '>=', date('Y-m-d'))->get();
        $disableDates = [];
        foreach ($incoming as $item) {
            $mulai = Carbon::parse($item->mulai);
            $selesai = Carbon::parse($item->selesai);
            $period = CarbonPeriod::create($mulai, $selesai);
            foreach ($period as $date) {
                $disableDates[] = $date->format('d/m/Y');
            }
        }

        return $disableDates;
    }

    static function getPengajuanCutiAktif($user_id)
    {
        // conds: id_user => $user_id, status not in ['accepted_pimpinan', 'rejected']
        $data = Cuti::where('id_user', $user_id)
            ->whereNotIn('status', ['accepted_pimpinan', 'rejected'])
            ->get()
            ->map(function ($item) {
                // $item->tanggal = explode(',', $item->tanggal);
                $item->mulai_formatted = Carbon::parse($item->mulai)->format('d/m/Y');
                $item->selesai_formatted = Carbon::parse($item->selesai)->format('d/m/Y');
                $item->status_text = formatStatusCuti($item->status);
                $item->status_color = formatStatusCutiColor($item->status);
                return $item;
            });
        return $data;
    }

    static function getCutiAktif($user_id)
    {
        $current_date = date('Y-m-d');
        $data = Cuti::where('id_user', $user_id)
            ->where('status', 'accepted_pimpinan')
            // ->where('tanggal', 'like', '%' . date('Y-m-d') . '%')
            ->where('mulai', '<=', $current_date)
            ->where('selesai', '>=', $current_date)
            ->get()
            ->map(function ($item) {
                // $item->tanggal = explode(',', $item->tanggal);
                // $item->total_formatted = count($item->tanggal) . ' hari';
                $item->mulai_formatted = Carbon::parse($item->mulai)->format('d/m/Y');
                $item->selesai_formatted = Carbon::parse($item->selesai)->format('d/m/Y');
                $item->status_text = formatStatusCuti($item->status);
                $item->status_color = formatStatusCutiColor($item->status);

                return $item;
            });
        return $data;
    }
    static function hasCutiAktif($user_id)
    {
        return self::getCutiAktif($user_id)->count() > 0;
    }

    static function getAktif($user_id)
    {
        $current_date = date('Y-m-d');
        $data = Cuti::where('id_user', $user_id)
            ->where('status', 'accepted_pimpinan')
            // ->where('tanggal', 'like', '%' . date('Y-m-d') . '%')
            ->where('mulai', '<=', $current_date)
            ->where('selesai', '>=', $current_date)
            ->get()
            ->map(function ($item) {
                // $item->tanggal = explode(',', $item->tanggal);
                // $item->total_formatted = count($item->tanggal) . ' hari';
                $total = getDurationExceptWeekend($item->mulai, $item->selesai);
                $item->mulai_formatted = Carbon::parse($item->mulai)->format('d/m/Y');
                $item->selesai_formatted = Carbon::parse($item->selesai)->format('d/m/Y');
                // $item->total_formatted = $diff - $weekends . ' hari kerja';
                $item->total_formatted = $total . ' hari kerja';

                $item->status_text = formatStatusCuti($item->status);
                $item->status_color = formatStatusCutiColor($item->status);

                return $item;
            })
            ->last();
        return $data;
    }

    static function getJatahCuti($user_id)
    {
        $jenis_arr = ['tahunan', 'besar', 'melahirkan', 'penting', 'ctln'];
        $jatah = [];

        foreach ($jenis_arr as $jenis) {
            $setting = Settings::first();
            $key = 'jatah_cuti_' . $jenis;
            $jatah[$jenis] = $setting->$key;
        }

        $cuti = Cuti::where('id_user', $user_id)
            ->where('status', 'accepted_pimpinan')
            // ->where('tanggal', 'like', '%' . date('Y') . '%')
            // get cuti by year
            ->where('mulai', 'like', '%' . date('Y') . '%')
            ->get();

        foreach ($cuti as $c) {
            // $dates = explode(',', $c->tanggal);
            // $jatah[$c->jenis] -= count($dates);
            $jatah[$c->jenis] -= $c->total;
        }

        return $jatah;
    }

    static function getByStatusAndRole($status, $filter = [])
    {
        $user = session('user');
        $role = $user->level;
        // dd($status, $role);
        $statusCond = [
            'pegawai.pending' => ['pending', 'accepted_kabid', 'accepted_admin'],
            'pegawai.missed' => ['pending', 'accepted_kabid', 'accepted_admin'],
            'pegawai.done' => ['accepted_pimpinan', 'rejected'],
            'kabid.pending' => ['pending'],
            'kabid.missed' => ['pending'],
            'kabid.done' => ['accepted_kabid', 'accepted_admin', 'accepted_pimpinan', 'rejected'],
            'admin.pending' => ['accepted_kabid'],
            'admin.missed' => ['accepted_kabid'],
            'admin.done' => ['accepted_admin', 'accepted_pimpinan', 'rejected'],
            'atasan.pending' => ['accepted_admin'],
            'atasan.missed' => ['accepted_admin'],
            'atasan.done' => ['accepted_pimpinan', 'rejected'],
        ];

        $dateCond = [
            'pegawai.pending' => function ($date) {
                return $date >= date('Y-m-d');
            },
            'pegawai.missed' => function ($date) {
                return $date < date('Y-m-d');
            },
            'pegawai.done' => function ($date) {
                return true;
            },
            'kabid.pending' => function ($date) {
                return $date >= date('Y-m-d');
            },
            'kabid.missed' => function ($date) {
                return $date < date('Y-m-d');
            },
            'kabid.done' => function ($date) {
                return true;
            },
            'admin.pending' => function ($date) {
                return $date >= date('Y-m-d');
            },
            'admin.missed' => function ($date) {
                return $date < date('Y-m-d');
            },
            'admin.done' => function ($date) {
                return true;
            },
            'atasan.pending' => function ($date) {
                return $date >= date('Y-m-d');
            },
            'atasan.missed' => function ($date) {
                return $date < date('Y-m-d');
            },
            'atasan.done' => function ($date) {
                return true;
            },
        ];

        $newDateCond = [
            'pegawai.pending' => [
                ['mulai', '>=', date('Y-m-d')],
                ['selesai', '>=', date('Y-m-d')],
            ],
            'pegawai.missed' => [
                ['mulai', '<', date('Y-m-d')],
                ['selesai', '<', date('Y-m-d')],
            ],
            'pegawai.done' => [],
            'kabid.pending' => [
                ['mulai', '>=', date('Y-m-d')],
                ['selesai', '>=', date('Y-m-d')],
            ],
            'kabid.missed' => [
                ['mulai', '<', date('Y-m-d')],
                ['selesai', '<', date('Y-m-d')],
            ],
            'kabid.done' => [],
            'admin.pending' => [
                ['mulai', '>=', date('Y-m-d')],
                ['selesai', '>=', date('Y-m-d')],
            ],
            'admin.missed' => [
                ['mulai', '<', date('Y-m-d')],
                ['selesai', '<', date('Y-m-d')],
            ],
            'admin.done' => [],
            'atasan.pending' => [
                ['mulai', '>=', date('Y-m-d')],
                ['selesai', '>=', date('Y-m-d')],
            ],
            'atasan.missed' => [
                ['mulai', '<', date('Y-m-d')],
                ['selesai', '<', date('Y-m-d')],
            ],
            'atasan.done' => [],
        ];

        $actions = [
            'pegawai.pending' => ['edit', 'delete', 'track'],
            'pegawai.missed' => ['delete', 'track'],
            'pegawai.done' => ['print', 'track'],
            'kabid.pending' => ['accept', 'reject', 'track'],
            'kabid.missed' => ['track', 'delete'],
            'kabid.done' => ['print', 'track'],
            'admin.pending' => ['accept', 'reject', 'track'],
            'admin.missed' => ['track', 'delete'],
            'admin.done' => ['print', 'track'],
            'atasan.pending' => ['accept', 'reject', 'track'],
            'atasan.missed' => ['track', 'delete'],
            'atasan.done' => ['print', 'track'],
        ];

        $status = $role . '.' . $status;

        $query = Cuti::with('user')->whereIn('status', $statusCond[$status])
            ->where($newDateCond[$status]);


        if ($role == 'pegawai') {
            $query->where('id_user', session('user')->id);
        }

        if ($role == 'kabid') {
            $query->whereHas('user', function ($q) {
                $q->where('bidang', session('user')->bidang)->where('level', 'pegawai');
            });
        }

        // $data = $query->get()
        //     ->map(function ($item) use ($actions, $status) {
        //         $item->tanggal_arr = explode(',', $item->tanggal);
        //         $item->status_text = formatStatusCuti($item->status);
        //         $item->status_color = formatStatusCutiColor($item->status);

        //         $item->actions = $actions[$status];

        //         return $item;
        //     });

        // $data = $data->filter(function ($item) use ($dateCond, $status) {
        //     $exploded = explode(',', $item->tanggal);
        //     $date = $exploded[0];
        //     $date = date('Y-m-d', strtotime($date));
        //     return $dateCond[$status]($date);
        // });

        if (isset($filter['date_start']) && isset($filter['date_end'])) {
            $doQuery = $filter['date_start'] != '' && $filter['date_end'] != '';
            if ($doQuery) {
                // $data = $data->filter(function ($item) use ($filter) {
                //     $exploded = explode(',', $item->tanggal);
                //     $date = $exploded[0];
                //     $date = date('Y-m-d', strtotime($date));
                //     $date_start = date('Y-m-d', strtotime($filter['date_start']));
                //     $date_end = date('Y-m-d', strtotime($filter['date_end']));
                //     return $date >= $date_start && $date <= $date_end;
                // });
                $query->whereBetween('mulai', [$filter['date_start'], $filter['date_end']]);
            }
        }

        $data = $query->get()
            ->map(function ($item) use ($actions, $status) {
                $item->mulai_formatted = date('d/m/Y', strtotime($item->mulai));
                $item->selesai_formatted = date('d/m/Y', strtotime($item->selesai));

                $item->status_text = formatStatusCuti($item->status);
                $item->status_color = formatStatusCutiColor($item->status);
                $item->actions = $actions[$status];
                return $item;
            });
        return $data;
    }
}