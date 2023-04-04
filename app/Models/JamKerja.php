<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class JamKerja extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
    protected $table = 'jam_kerja';

    private static $days = ['minggu', 'senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu'];

    static function getAktif() {
        $data = JamKerja::where('status', 'aktif')
            ->where('days', 'like', '%' . self::$days[date('w')] . '%')
            ->get()
            ->each(function($item) {
                $item->mulai = Carbon::parse($item->mulai)->format('H:i');
                $item->selesai = Carbon::parse($item->selesai)->format('H:i');
                $item->formatted = $item->mulai . ' - ' . $item->selesai . ' WIB';
                $item->is_absen_time = Carbon::now()->between(Carbon::parse($item->mulai), Carbon::parse($item->selesai));
            })
            ->first();
        return $data;
    }
}
