<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penggajian extends BaseModel
{
    /** @use HasFactory<\Database\Factories\PenggajianFactory> */
    use HasFactory, SoftDeletes, UsesUuid;

    protected $guarded = ['id'];

    // public function penggajian_tkbms()
    // {
    //     return $this->hasMany(Penggajian_tkbm::class, 'penggajian_id');
    // }


    public function penggajian_penjualans()
    {
        return $this->hasMany(Penggajian_penjualan::class, 'penggajian_id');
    }

    public function penggajian_karyawans()
    {

        return $this->hasMany(Penggajian_karyawan::class);
    }

    public function getPeriodeAwalFormattedAttribute()
    {
        if (is_null($this->periode_awal)) {
            return null;
        }

        return Carbon::parse($this->periode_awal)->translatedFormat('d F Y');
    }

    public function getPeriodeBerakhirFormattedAttribute()
    {
        if (is_null($this->periode_berakhir)) {
            return null;
        }

        return Carbon::parse($this->periode_berakhir)->translatedFormat('d F Y');
    }
}
