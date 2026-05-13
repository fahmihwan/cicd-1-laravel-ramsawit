<?php

namespace App\Http\Repositories;

use App\Models\Periode;

class PeriodeRepository
{

    public function validateIsCorrectPeriodeMulai($periode_id, $tanggal)
    {
        $periode = Periode::findOrFail($periode_id);

        // Jika tanggal lebih kecil dari periode mulai => tidak valid
        if ($tanggal < $periode->periode_mulai) {
            return true;
        }

        // Jika ada periode berakhir dan tanggal lebih besar => tidak valid
        if ($periode->periode_berakhir && $tanggal > $periode->periode_berakhir) {
            return true;
        }

        // Jika masih dalam periode => valid (return false karena "is not valid" = false)
        return false;
    }


    public function validateIsOverlapPeriode($periode_mulai, $periode_berakhir = null, $excludeId = null)
    {
        // Jika periode_berakhir null, asumsikan sama dengan periode_mulai (periode 1 hari)
        $periode_berakhir = $periode_berakhir ?? $periode_mulai;

        return Periode::when($excludeId, function ($query) use ($excludeId) {
            $query->where('id', '!=', $excludeId);
        })
            ->where(function ($query) use ($periode_mulai, $periode_berakhir) {
                $query->whereDate('periode_mulai', '<', $periode_berakhir)
                    ->whereDate('periode_berakhir', '>', $periode_mulai);
            })
            ->exists();
    }
}
