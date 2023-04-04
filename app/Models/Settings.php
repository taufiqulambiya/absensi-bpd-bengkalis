<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    static public function getJatahCuti() {
        $keys = ['jatah_cuti_tahunan', 'jatah_cuti_besar', 'jatah_cuti_melahirkan', 'jatah_cuti_penting', 'jatah_cuti_ctln'];
        $setting = Settings::get()->last()->only($keys);
        
        foreach ($setting as $key => $value) {
            $replace = str_replace('jatah_cuti_', '', $key);
            $setting[$replace] = $value;
            unset($setting[$key]);
        }

        return $setting;
    }
}
