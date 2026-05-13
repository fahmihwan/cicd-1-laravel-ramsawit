<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pinjaman_uang extends BaseModel
{
    /** @use HasFactory<\Database\Factories\PinjamanUangFactory> */
    use HasFactory, SoftDeletes, UsesUuid;

    protected $guarded = ['id'];

    public function karyawan()
    {
        return $this->belongsTo(M_karyawan::class);
    }

    public function getTanggalFormattedAttribute()
    {
        Carbon::setLocale('id'); // untuk hari dan bulan dalam bahasa Indonesia

        return Carbon::parse($this->tanggal)
            ->timezone('Asia/Jakarta') // pastikan waktu dalam zona WIB
            ->translatedFormat('l, d-F-Y');
    }

    public function getNominalPeminjamanFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nominal_peminjaman ?? 0, 0, ',', '.');
    }

    public function getNominalPengembalianFormattedAttribute()
    {
        return 'Rp ' . number_format($this->nominal_pengembalian ?? 0, 0, ',', '.');
    }
}
