<?php

namespace App\Models;

use App\Helpers\Utils;
use App\Traits\UsesUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;


class Penjualan extends BaseModel
{
    /** @use HasFactory<\Database\Factories\PenjualanFactory> */
    use HasFactory, SoftDeletes, UsesUuid;

    protected $guarded = ['id'];

    public function pekerjaan_lain()
    {
        return $this->belongsTo(M_pekerjaanNonSawit::class);
    }

    public function tarif_sopir()
    {
        return $this->belongsTo(M_tarif::class);
    }

    public function tarif_tkbm()
    {
        return $this->belongsTo(M_tarif::class);
    }


    public function model_kerja()
    {
        return $this->belongsTo(M_modelkerja::class);
    }



    public function tkbms()
    {
        return $this->hasMany(Tkbm::class, 'penjualan_id');
    }

    public function sopir()
    {
        return $this->belongsTo(M_karyawan::class, 'sopir_id');
    }

    public function pabrik()
    {
        return $this->belongsTo(M_pabrik::class);
    }


    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }


    public function getFormattedTglPenjualanAttribute()
    {
        return Carbon::parse($this->tanggal_penjualan)
            ->timezone('Asia/Jakarta')
            ->translatedFormat('l, d-F-Y');
    }

    public function getTimbanganSecondFormattedAttribute()
    {
        return $this->timbangan_second . ' kg';
    }

    public function getTimbanganFirstFormattedAttribute()
    {
        return $this->timbangan_first . ' kg';
    }

    public function getSortasiFormattedAttribute()
    {
        return $this->sortasi . ' kg';
    }

    public function getNettoFormattedAttribute()
    {
        return $this->netto . ' kg';
    }

    public function getBrutoFormattedAttribute()
    {
        return $this->bruto . ' kg';
    }

    public function getHargaFormattedAttribute()
    {
        return Utils::formatRupiah($this->harga);
    }

    // Format harga
    public function getUangFormattedAttribute()
    {
        return Utils::formatRupiah($this->uang);
    }


    // UUID config;
}
