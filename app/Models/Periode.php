<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Periode extends BaseModel
{
    /** @use HasFactory<\Database\Factories\PeriodeFactory> */

    use HasFactory, SoftDeletes, UsesUuid;

    protected $guarded = ['id'];

    public function penjualans()
    {
        return $this->hasMany(Penjualan::class);
    }

    public function pembelians()
    {
        return $this->hasMany(Pembelian_tbs::class);
    }



    public function getLabelPeriodeAttribute()
    {

        // $mulai = Carbon::parse($this->periode_mulai)->translatedFormat('dmy');
        // $akhir = Carbon::parse($this->periode_berakhir)->translatedFormat('dmy');

        return "Periode ke-$this->periode ";
        // return "Periode ke-$this->periode ($mulai - $akhir)";
    }


    public function getFormattedMulaiAttribute()
    {
        if (is_null($this->periode_mulai)) {
            return null;
        }

        return Carbon::parse($this->periode_mulai)->translatedFormat('d F Y');
    }

    public function getFormattedBerakhirAttribute()
    {
        if (is_null($this->periode_berakhir)) {
            return null;
        }

        return Carbon::parse($this->periode_berakhir)->translatedFormat('d F Y');
    }
}
