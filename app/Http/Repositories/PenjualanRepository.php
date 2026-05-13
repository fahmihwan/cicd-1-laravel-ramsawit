<?php

namespace App\Http\Repositories;

use App\Models\M_karyawan;
use App\Models\Penjualan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class PenjualanRepository
{

    public function makeTKBM_agg($karyawanIds)
    {
        $get_tkbm_agg =  M_karyawan::withTrashed()->whereIn('id', $karyawanIds)->select('nama')->pluck('nama')->toArray();
        $tkbm_agg = implode('~', $get_tkbm_agg);

        return $tkbm_agg;
    }
    public function handleArrayObjTKBM(array $listArrayTkbm, $penjualanId, $tkbm_agg, $jumlah_uang_tkbm): array
    {
        $data = [];
        foreach ($listArrayTkbm as $d) {
            $data[] = [
                'id' => (string) Str::uuid(),
                'karyawan_id' => $d,
                'penjualan_id' => $penjualanId,
                'type_karyawan_id' => 2, //TKBM
                'model_kerja_id' => $penjualanId['model_kerja_id'],
                'tarif_id' => $penjualanId['tarif_tkbm_id'] ? $penjualanId['tarif_tkbm_id'] : null,
                'tarif_tkbm_borongan' =>  null,
                'tarif_sopir_borongan' => null,
                'is_gaji_dibayarkan' => false,
                'tkbm_agg' => $tkbm_agg,
                'jumlah_tkbm' => count($listArrayTkbm),
                'jumlah_uang' => ceil($jumlah_uang_tkbm)
            ];
        }
        return $data;
    }


    public function getPenjualansFromRangePeriode($periode_awal, $periode_berakhir)
    {
        // Langkah 2: Ambil ID penjualans berdasarkan periode baru
        $penjualanInRangeIds = Penjualan::whereBetween('tanggal_penjualan', [
            $periode_awal,
            $periode_berakhir
        ])->select('id')->pluck('id')->toArray();

        return $penjualanInRangeIds;
    }



    public function penjualanLaba($periodeId)
    {
        return collect(DB::select("WITH penjualan_periode AS (
								SELECT 
									p.id as periode_id,
                                    pj.do_type_id,
                                    pj.netto,
                                    pj.harga,
                                    p.periode
                                from penjualans pj 
                                LEFT JOIN periodes p ON pj.periode_id = p.id 
                                WHERE p.deleted_at IS null and p.id = ?
                            )
                           	SELECT 
							    dot.delivery_order_type,
                                COALESCE(SUM(pj.netto), 0) AS netto,
                                COALESCE(pj.harga, 0) AS harga,
                                COALESCE(SUM(pj.netto), 0) * COALESCE(pj.harga, 0) AS uang
							FROM m_delivery_order_types dot
							LEFT JOIN penjualan_periode pj on pj.do_type_id = dot.id AND pj.periode_id = ?
							GROUP BY dot.delivery_order_type, pj.harga
                            ORDER BY dot.delivery_order_type", [$periodeId, $periodeId]));
    }

    public function penjualanGroupByOpsLaba($periodeId)
    {
        $rows = collect(DB::select("WITH penjualan_periode AS (
								SELECT 
									pj.periode_id,
                                    pj.do_type_id,
                                    pj.ops,
                                    pj.netto,
                                    pj.harga,
                                    p.periode
                                from penjualans pj 
                                LEFT JOIN periodes p ON pj.periode_id = p.id 
                                WHERE p.deleted_at IS null and p.id = ? and pj.deleted_at is null
                            )
                           	SELECT 
                           		coalesce(sum(pj.netto),0) as netto,
                                COALESCE(pj.harga, 0) AS harga,
                               	coalesce(sum(pj.netto * pj.harga),0) as uang,
                               	dot.delivery_order_type,
                               	coalesce (sum(pj.netto * pj.ops),0) total_ops,
                               	coalesce(pj.ops,0) as ops
							FROM m_delivery_order_types dot
							LEFT JOIN penjualan_periode pj on pj.do_type_id = dot.id AND pj.periode_id = ?
							group by pj.harga,dot.delivery_order_type,pj.ops", [$periodeId, $periodeId]));

        $result = $rows
            ->groupBy(fn($row) => (int) $row->ops)
            ->filter(fn($items, $ops) => (int) $ops !== 0)
            ->map(function (Collection $items, $ops) {
                return [
                    'ops'    => (int) $ops,
                    'values' => $items->values(),     // biarkan tetap Collection
                ];
            })
            ->sortBy('ops', SORT_NUMERIC)             // urutkan ops (opsional)
            ->values();                               // reset index jadi [0,1,2,...]

        return $result;
    }
}
