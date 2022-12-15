<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DinasLuar extends Model
{
    use HasFactory;
    protected $table = 'dinas_luar';

    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'id_user');
    }
}
