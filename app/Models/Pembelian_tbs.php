<?php

namespace App\Models;

use App\Helpers\Utils;
use App\Traits\UsesUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Pembelian_tbs extends BaseModel
{

    use HasFactory, SoftDeletes, UsesUuid;

    protected $guarded = ['id'];

    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function getFormattedTglPembelianAttribute()
    {
        return Carbon::parse($this->tanggal_pembelian)
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
}
