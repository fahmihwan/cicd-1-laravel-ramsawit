<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class M_karyawan extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];


    public function penjualan()
    {
        return $this->hasMany(Penjualan::class, 'sopir_id');
    }

    public function jobs()
    {
        return $this->hasMany(M_jobs::class, 'karyawan_id', 'id');
    }

    public function main_type_karyawan()
    {
        return $this->belongsTo(M_type_karyawan::class, 'main_type_karyawan_id', 'id');
    }
}
