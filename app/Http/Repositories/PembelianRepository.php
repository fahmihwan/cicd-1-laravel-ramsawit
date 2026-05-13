<?php

namespace App\Http\Repositories;

use Illuminate\Support\Facades\DB;

class PembelianRepository
{
    public function pembelianLaba($periodeId)
    {

        //   return collect(DB::select("SELECT mt.id,mt.type_tbs,	
        //         COALESCE(SUM(x.netto), 0) AS netto,
        //         COALESCE(x.harga, 0) AS harga,
        //         COALESCE(SUM(x.netto), 0) * COALESCE(x.harga, 0) AS uang
        //         from m_type_tbs mt
        //         left join (
        //             SELECT 
        //                 p.id as periode_id,pt.tbs_type_id,pt.harga,pt.netto,p.periode
        //                 FROM pembelian_tbs pt
        //                     LEFT JOIN periodes p ON pt.periode_id = p.id
        //                 WHERE pt.deleted_at IS null and p.id = ?
        //         ) as x on x.tbs_type_id = mt.id
        //         GROUP by mt.id, x.harga,mt.type_tbs
        //         order by id asc", [$periodeId]));


        return collect(DB::select("WITH pembelian_periode AS (
                                            SELECT 
                                            	p.id as periode_id,
                                                pt.tbs_type_id,
                                                pt.harga,
                                                pt.netto,
                                                p.periode
                                            FROM pembelian_tbs pt
                                            LEFT JOIN periodes p ON pt.periode_id = p.id
                                            WHERE pt.deleted_at IS null and p.id = ?
                                        )
                                        SELECT 
                                            mt.type_tbs,
                                            COALESCE(SUM(pp.netto), 0) AS netto,
                                            COALESCE(pp.harga, 0) AS harga,
                                            COALESCE(SUM(pp.netto), 0) * COALESCE(pp.harga, 0) AS uang
                                        FROM m_type_tbs mt
                                        LEFT JOIN pembelian_periode pp ON pp.tbs_type_id = mt.id AND pp.periode_id = ?
                                        GROUP BY mt.type_tbs, pp.harga
                                        ORDER BY mt.type_tbs;", [$periodeId, $periodeId]));
    }
}
