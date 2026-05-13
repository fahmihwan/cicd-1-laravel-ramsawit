<?php

namespace App\Http\Controllers;

use App\Models\M_karyawan;
use App\Models\M_pabrik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

use function PHPUnit\Framework\returnSelf;

class PenggajianDespricatedController extends Controller
{

  public function detail_gaji()
  {
    $penggajianid = "8115f4a7-fa84-4319-ab3c-e0f1f0cad797";



    //         $items = DB::select("SELECT * from (
    // select 
    //     p2.id AS penjualan_id,mk.id,mk.nama,p2.tanggal_penjualan,p2.netto,x.tarif_perkg,x.tkbm_agg as tkbms,x.total,x.nama_pabrik,x.model_kerja_id, p2.created_at,
    //     case
    // 	    WHEN t.id IS null and t.type_karyawan_id = 2 THEN true
    // 	    WHEN t.id IS NOT NULL and t.type_karyawan_id = 2 THEN false
    // 		when t.type_karyawan_id = 1 then null
    //     END AS is_tkbm_alpha,    
    //     x.jumlah_uang,  x.type_karyawan_dinamis ,mk.main_type_karyawan_id
    // FROM penjualans p2
    // left JOIN tkbms t ON t.penjualan_id = p2.id AND t.deleted_at IS null and t.type_karyawan_id = 2 AND t.karyawan_id = :karyawanid --KARYAWAN_ID
    // left JOIN m_karyawans mk ON mk.id = :karyawanid --KARYAWAN_ID
    // left JOIN (
    // select t.penjualan_id, count(t.id) as total, mp.nama_pabrik,
    //     	string_agg(mk.nama,'~') as tkbm_agg,
    // 		mt.tarif_perkg,
    // 		case 
    // 			when p.model_kerja_id = 1 then p.netto * mt.tarif_perkg / count(t.id)
    // 			when p.model_kerja_id = 2 then p.tarif_tkbm_borongan
    // 		end as jumlah_uang,t.type_karyawan_id as type_karyawan_dinamis, p.model_kerja_id
    //   			from penjualans p 
    // 				inner join tkbms t on p.id  = t.penjualan_id 
    // 				inner join m_karyawans mk on mk.id  = t.karyawan_id 
    // 				left join m_tarifs mt on mt.id = t.tarif_id 
    // 				inner join m_pabriks mp on mp.id = p.pabrik_id
    // 			where t.deleted_at is null 
    // 			and t.type_karyawan_id = 2 
    // 			group by t.penjualan_id, p.netto,mt.tarif_perkg, p.tanggal_penjualan,mp.nama_pabrik,t.type_karyawan_id, p.model_kerja_id,p.tarif_tkbm_borongan 
    // 			order by p.tanggal_penjualan desc
    // ) x ON x.penjualan_id = p2.id 

    // union all 

    // select 
    //     p2.id AS penjualan_id,mk.id,mk.nama,p2.tanggal_penjualan,p2.netto,x.tarif_perkg,x.tkbm_agg as tkbms,x.total,x.nama_pabrik,x.model_kerja_id,  p2.created_at,
    //  	case
    // 	    WHEN t.id IS null and t.type_karyawan_id = 2 THEN true
    // 	    WHEN t.id IS NOT NULL and t.type_karyawan_id = 2 THEN false
    // 		when t.type_karyawan_id = 1 then null
    //     END AS is_tkbm_alpha,    
    //     x.jumlah_uang,  x.type_karyawan_dinamis,mk.main_type_karyawan_id
    // FROM penjualans p2
    // inner JOIN tkbms t ON t.penjualan_id = p2.id  
    // left JOIN m_karyawans mk ON mk.id = :karyawanid --karyawan_id
    // left JOIN (
    // 	    select t.penjualan_id, count(t.id) as total, mp.nama_pabrik,
    // 	    	null as tkbm_agg,
    // 			mt.tarif_perkg, 
    // 			case 
    // 				when p.model_kerja_id = 1 then p.netto * mt.tarif_perkg 
    // 				when p.model_kerja_id = 2 then p.tarif_sopir_borongan
    // 			end as jumlah_uang,t.type_karyawan_id as type_karyawan_dinamis, p.model_kerja_id
    //     			from penjualans p 
    // 					inner join tkbms t on p.id  = t.penjualan_id 
    // 					inner join m_karyawans mk on mk.id  = t.karyawan_id 
    // 					inner join m_tarifs mt on mt.id = t.tarif_id 
    // 					inner join m_pabriks mp on mp.id = p.pabrik_id
    // 				where t.deleted_at is null 
    // 				and t.type_karyawan_id = 1
    // 				group by t.penjualan_id, p.netto,mt.tarif_perkg, p.tanggal_penjualan,mp.nama_pabrik,t.type_karyawan_id, p.model_kerja_id,p.tarif_sopir_borongan
    // 				order by p.tanggal_penjualan desc
    // ) x ON x.penjualan_id = p2.id 
    // where t.karyawan_id = :karyawanid AND t.deleted_at IS null and t.type_karyawan_id = 2
    // ) as y
    // ORDER BY y.created_at DESC", [
    //             'karyawanid' => $karyawanid,
    //         ]);



    // $karyawanid = 3;

    // $karyawan = M_karyawan::with(['main_type_karyawan'])->findOrFail($karyawanid);

    //     $items = DB::select("WITH cte_tkbm_borongan as (
    // -- AMBIL TKBM + BORONGAN
    // 	        	select t.penjualan_id, p.tanggal_penjualan, count(t.id) as total, mp.nama_pabrik,
    // 			        	mt.tarif_perkg,p.netto,
    // 						string_agg(mk.nama,'~') as tkbms,
    // 						case 
    // 							when p.model_kerja_id = 1 then p.netto * mt.tarif_perkg / count(t.id)
    // 							when p.model_kerja_id = 2 then p.tarif_tkbm_borongan 
    // 						end as jumlah_uang,
    // 						t.type_karyawan_id, p.model_kerja_id
    // 	            			from penjualans p 
    // 	        					inner join tkbms t on p.id  = t.penjualan_id 
    // 	        					inner join m_karyawans mk on mk.id  = t.karyawan_id 
    // 	        					left join m_tarifs mt on mt.id = t.tarif_id 
    // 	        					inner join m_pabriks mp on mp.id = p.pabrik_id
    // 	        				where t.deleted_at is null 
    // 	        				and t.type_karyawan_id = 2 --TKBM
    // 	        				group by t.penjualan_id, p.netto,mt.tarif_perkg, p.tanggal_penjualan,mp.nama_pabrik,t.type_karyawan_id, p.model_kerja_id,p.tarif_tkbm_borongan,p.tarif_sopir_borongan 
    // ),
    // cte_sopir_borongan as (
    // 				-- AMBIL SOPIR + BORONGAN
    // 	        	select t.penjualan_id, p.tanggal_penjualan, count(t.id) as total, mp.nama_pabrik,
    // 			        	mt.tarif_perkg,p.netto,
    // 						null as tkbms,
    // 						case 
    // 							when p.model_kerja_id = 1 then p.netto * mt.tarif_perkg 
    // 							when p.model_kerja_id = 2 then p.tarif_sopir_borongan 
    // 						end as jumlah_uang,
    // 						t.type_karyawan_id, p.model_kerja_id
    // 	            			from penjualans p 
    // 	        					inner join tkbms t on p.id  = t.penjualan_id 
    // 	        					inner join m_karyawans mk on mk.id  = t.karyawan_id 
    // 	        					left join m_tarifs mt on mt.id = t.tarif_id 
    // 	        					inner join m_pabriks mp on mp.id = p.pabrik_id
    // 	        				where t.deleted_at is null 
    // 	        				and t.type_karyawan_id = 1 --SOPIR
    // 	        				group by t.penjualan_id, p.netto,mt.tarif_perkg, p.tanggal_penjualan,mp.nama_pabrik,t.type_karyawan_id, p.model_kerja_id,p.tarif_sopir_borongan 
    // ),
    // cte_penjualan AS (
    //   SELECT p.id AS penjualan_id, p.tanggal_penjualan, p.netto, p.created_at, p.model_kerja_id
    //   FROM penjualans p
    // ),
    // cte_karyawan_ikut AS (
    //   SELECT t.*, mk.nama, mk.main_type_karyawan_id
    //   FROM tkbms t
    //   JOIN m_karyawans mk ON mk.id = t.karyawan_id
    //   WHERE t.karyawan_id = :karyawanid
    // )
    // SELECT 
    //   p.penjualan_id,k.karyawan_id,k.nama,k.type_karyawan_id,k.main_type_karyawan_id,
    //   p.tanggal_penjualan,p.netto,p.model_kerja_id,
    //   CASE 
    //     WHEN k.type_karyawan_id = 1 THEN cte_s.tarif_perkg
    //     WHEN k.type_karyawan_id = 2 THEN cte_t.tarif_perkg
    //   END AS tarif_perkg,
    //   CASE 
    //     WHEN k.type_karyawan_id = 1 THEN cte_s.tkbms
    //     WHEN k.type_karyawan_id = 2 THEN cte_t.tkbms
    //   END AS tkbms,
    //   CASE 
    //     WHEN k.type_karyawan_id = 1 THEN cte_s.jumlah_uang
    //     WHEN k.type_karyawan_id = 2 THEN cte_t.jumlah_uang
    //   END AS jumlah_uang,
    //   COALESCE(k.type_karyawan_id, 2) AS fallback_type_karyawan_id, -- biar CASE gak null
    //   CASE 
    //     WHEN k.karyawan_id IS NULL THEN true ELSE false
    //   END AS is_tkbm_alpha
    // FROM cte_penjualan p
    // LEFT JOIN cte_karyawan_ikut k ON p.penjualan_id = k.penjualan_id
    // LEFT JOIN cte_tkbm_borongan cte_t ON cte_t.penjualan_id = p.penjualan_id
    // LEFT JOIN cte_sopir_borongan cte_s ON cte_s.penjualan_id = p.penjualan_id
    // ORDER BY p.created_at DESC;", [
    //       'karyawanid' => $karyawanid,
    //     ]);




    // $karyawanid = 1;
    // $karyawan = M_karyawan::with(['main_type_karyawan'])->findOrFail($karyawanid);

    // $items = DB::select("WITH cte_tkbm_borongan as (
    // -- AMBIL TKBM + BORONGAN
    // 	        	select t.penjualan_id, p.tanggal_penjualan, count(t.id) as total, 
    // 			        	mt.tarif_perkg,p.netto,
    // 						string_agg(mk.nama,'~') as tkbms,
    // 						case 
    // 							when p.model_kerja_id = 1 then p.netto * mt.tarif_perkg / count(t.id)
    // 							when p.model_kerja_id = 2 then p.tarif_tkbm_borongan 
    // 						end as jumlah_uang,
    // 						t.type_karyawan_id, p.model_kerja_id
    // 	            			from penjualans p 
    // 	        					inner join tkbms t on p.id  = t.penjualan_id 
    // 	        					inner join m_karyawans mk on mk.id  = t.karyawan_id 
    // 	        					left join m_tarifs mt on mt.id = t.tarif_id 
    // 	        				where t.deleted_at is null 
    // 	        				and t.type_karyawan_id = 2 --TKBM
    // 	        				group by t.penjualan_id, p.netto,mt.tarif_perkg, p.tanggal_penjualan,t.type_karyawan_id, p.model_kerja_id,p.tarif_tkbm_borongan,p.tarif_sopir_borongan 
    // ),
    // cte_sopir_borongan as (
    // 				-- AMBIL SOPIR + BORONGAN
    // 	        	select t.penjualan_id, p.tanggal_penjualan, count(t.id) as total,
    // 			        	mt.tarif_perkg,p.netto, 
    // 						null as tkbms,
    // 						case 
    // 							when p.model_kerja_id = 1 then p.netto * mt.tarif_perkg 
    // 							when p.model_kerja_id = 2 then p.tarif_sopir_borongan 
    // 						end as jumlah_uang,
    // 						t.type_karyawan_id, p.model_kerja_id
    // 	            			from penjualans p 
    // 	        					inner join tkbms t on p.id  = t.penjualan_id 
    // 	        					inner join m_karyawans mk on mk.id  = t.karyawan_id 
    // 	        					left join m_tarifs mt on mt.id = t.tarif_id 
    // 	        				where t.deleted_at is null 
    // 	        				and t.type_karyawan_id = 1 --SOPIR
    // 	        				group by t.penjualan_id, p.netto,mt.tarif_perkg, p.tanggal_penjualan,t.type_karyawan_id, p.model_kerja_id,p.tarif_sopir_borongan 
    // ),
    // cte_penjualan AS (
    //   SELECT p.id AS penjualan_id, p.tanggal_penjualan, p.netto, p.created_at, p.model_kerja_id,mp.nama_pabrik
    //   FROM penjualans p
    //   inner join m_pabriks mp on mp.id = p.pabrik_id
    // ),
    // cte_karyawan_ikut AS (
    //   SELECT t.*, mk.nama, mk.main_type_karyawan_id
    //   FROM tkbms t
    //   JOIN m_karyawans mk ON mk.id = t.karyawan_id
    //   WHERE t.karyawan_id = :karyawanid
    // )
    // select * from (
    // 	SELECT 
    // 	  p.penjualan_id,k.karyawan_id,k.nama,k.type_karyawan_id,k.main_type_karyawan_id, cte_t.total,
    // 	  p.tanggal_penjualan,p.netto,p.model_kerja_id,p.nama_pabrik,
    // 	  CASE 
    // 	    WHEN k.type_karyawan_id = 1 THEN cte_s.tarif_perkg
    // 	    WHEN k.type_karyawan_id = 2 THEN cte_t.tarif_perkg
    // 	  END AS tarif_perkg,
    // 	  CASE 
    // 	    WHEN k.type_karyawan_id = 1 THEN cte_s.tkbms
    // 	    WHEN k.type_karyawan_id = 2 THEN cte_t.tkbms
    // 	  END AS tkbms,
    // 	  CASE 
    // 	    WHEN k.type_karyawan_id = 1 THEN cte_s.jumlah_uang
    // 	    WHEN k.type_karyawan_id = 2 THEN cte_t.jumlah_uang
    // 	  END AS jumlah_uang,
    // 	  COALESCE(k.type_karyawan_id, 2) AS fallback_type_karyawan_id, -- biar CASE gak null
    // 	  CASE 
    // 	    WHEN k.karyawan_id IS NULL THEN true ELSE false
    // 	  END AS is_tkbm_alpha
    // 	FROM cte_penjualan p
    // 	LEFT JOIN cte_karyawan_ikut k ON p.penjualan_id = k.penjualan_id
    // 	LEFT JOIN cte_tkbm_borongan cte_t ON cte_t.penjualan_id = p.penjualan_id
    // 	LEFT JOIN cte_sopir_borongan cte_s ON cte_s.penjualan_id = p.penjualan_id
    // 	ORDER BY p.created_at DESC
    // ) as x
    // WHERE NOT (x.model_kerja_id = 2 AND x.is_tkbm_alpha = true)", [
    //   'karyawanid' => $karyawanid,
    // ]);






    // $karyawanid = 3;
    // $karyawan = M_karyawan::with(['main_type_karyawan'])->findOrFail($karyawanid);

    // $items = DB::select("WITH cte_tkbm_borongan AS (
    //             SELECT 
    //               t.penjualan_id,p.tanggal_penjualan,COUNT(t.id) AS total, mt.tarif_perkg,p.netto,STRING_AGG(mk.nama, '~') AS tkbms,
    //               CASE 
    //                 WHEN p.model_kerja_id = 1 THEN p.netto * mt.tarif_perkg / COUNT(t.id)
    //                 WHEN p.model_kerja_id = 2 THEN p.tarif_tkbm_borongan 
    //               END AS jumlah_uang, t.type_karyawan_id,p.model_kerja_id
    //             FROM penjualans p 
    //               INNER JOIN tkbms t ON p.id = t.penjualan_id 
    //               INNER JOIN m_karyawans mk ON mk.id = t.karyawan_id 
    //               LEFT JOIN m_tarifs mt ON mt.id = t.tarif_id 
    //             WHERE t.deleted_at IS NULL 
    //               AND t.type_karyawan_id = 2 -- TKBM
    //             GROUP BY t.penjualan_id, p.tanggal_penjualan, p.netto, mt.tarif_perkg, p.model_kerja_id, p.tarif_tkbm_borongan, t.type_karyawan_id
    //           ),
    //           cte_sopir_borongan AS (
    //             SELECT 
    //               t.penjualan_id,p.tanggal_penjualan,COUNT(t.id) AS total, mt.tarif_perkg,p.netto,NULL AS tkbms,
    //               CASE 
    //                 WHEN p.model_kerja_id = 1 THEN p.netto * mt.tarif_perkg
    //                 WHEN p.model_kerja_id = 2 THEN p.tarif_sopir_borongan 
    //               END AS jumlah_uang,t.type_karyawan_id,p.model_kerja_id
    //             FROM penjualans p 
    //               INNER JOIN tkbms t ON p.id = t.penjualan_id 
    //               INNER JOIN m_karyawans mk ON mk.id = t.karyawan_id 
    //               LEFT JOIN m_tarifs mt ON mt.id = t.tarif_id 
    //             WHERE t.deleted_at IS NULL 
    //               AND t.type_karyawan_id = 1 -- SOPIR
    //             GROUP BY t.penjualan_id, p.tanggal_penjualan, p.netto, mt.tarif_perkg, p.model_kerja_id, p.tarif_sopir_borongan, t.type_karyawan_id
    //           ),
    //           cte_penjualan AS (
    //             SELECT 
    //               p.id AS penjualan_id,p.tanggal_penjualan,p.netto,p.created_at,p.model_kerja_id,mp.nama_pabrik
    //             FROM penjualans p
    //             INNER JOIN m_pabriks mp ON mp.id = p.pabrik_id
    //           ),
    //           cte_karyawan_ikut AS (
    //             SELECT DISTINCT t.penjualan_id,t.karyawan_id,mk.nama,mk.main_type_karyawan_id,t.type_karyawan_id
    //             FROM tkbms t
    //             JOIN m_karyawans mk ON mk.id = t.karyawan_id
    //             WHERE t.karyawan_id = :karyawanid --AND t.type_karyawan_id = 2
    //           )

    //           SELECT 
    //             p.penjualan_id,p.tanggal_penjualan,p.netto,p.model_kerja_id,p.nama_pabrik,
    //             k.karyawan_id,k.nama,k.type_karyawan_id,k.main_type_karyawan_id,
    //             cte_t.total,cte_t.tarif_perkg,cte_t.tkbms,cte_t.jumlah_uang,
    //             COALESCE(k.type_karyawan_id, 2) AS fallback_type_karyawan_id,
    //             CASE 
    //               WHEN k.karyawan_id IS NULL THEN TRUE
    //               ELSE FALSE
    //             END AS is_tkbm_alpha
    //           FROM cte_penjualan p
    //           LEFT JOIN cte_tkbm_borongan cte_t ON cte_t.penjualan_id = p.penjualan_id
    //           LEFT JOIN cte_sopir_borongan cte_s ON cte_s.penjualan_id = p.penjualan_id
    //           LEFT JOIN cte_karyawan_ikut k ON k.penjualan_id = p.penjualan_id
    //           WHERE NOT (p.model_kerja_id = 2 AND k.karyawan_id IS NULL)
    //           ORDER BY p.created_at DESC;", [
    //   'karyawanid' => $karyawanid,
    // ]);




    $karyawanid = 3;
    $karyawan = M_karyawan::with(['main_type_karyawan'])->findOrFail($karyawanid);
    // return $karyawan;

    // if ($karyawan->main_type_karyawan_id == 2) { // tkbm
    $items = DB::select("WITH cte_tkbm_borongan_tarif as (
            -- AMBIL TKBM + BORONGAN
                        select t.penjualan_id, p.tanggal_penjualan, count(t.id) as total, 
                            mt.tarif_perkg,p.netto,
                        case 
                          when p.model_kerja_id = 1 then p.netto * mt.tarif_perkg / count(t.id)
                          when p.model_kerja_id = 2 then p.tarif_tkbm_borongan 
                        end as jumlah_uang,
                        t.type_karyawan_id, p.model_kerja_id
                                from penjualans p 
                                inner join tkbms t on p.id  = t.penjualan_id 
                                left join m_tarifs mt on mt.id = t.tarif_id 
                              where t.deleted_at is null 
                              and t.type_karyawan_id = 2 --TKBM
                              group by t.penjualan_id, p.netto,mt.tarif_perkg, p.tanggal_penjualan,t.type_karyawan_id, p.model_kerja_id,p.tarif_tkbm_borongan,p.tarif_sopir_borongan 
            ),
            cte_sopir_borongan_tarif as (
                    -- AMBIL SOPIR + BORONGAN
                        select t.penjualan_id, p.tanggal_penjualan, count(t.id) as total,
		                            mt.tarif_perkg,p.netto, 
			                        case 
			                          when p.model_kerja_id = 1 then p.netto * mt.tarif_perkg 
			                          when p.model_kerja_id = 2 then p.tarif_sopir_borongan 
			                        end as jumlah_uang,
			                        t.type_karyawan_id, p.model_kerja_id
                                from penjualans p 
                                inner join tkbms t on p.id  = t.penjualan_id 
                                left join m_tarifs mt on mt.id = t.tarif_id 
                              where t.deleted_at is null 
                              and t.type_karyawan_id = 1 --SOPIR
                              group by t.penjualan_id, p.netto,mt.tarif_perkg, p.tanggal_penjualan,t.type_karyawan_id, p.model_kerja_id,p.tarif_sopir_borongan
            ),
            cte_penjualan AS (
              SELECT p.id AS penjualan_id, p.tanggal_penjualan, p.netto, p.created_at, p.model_kerja_id,mp.nama_pabrik
              FROM penjualans p
              inner join m_pabriks mp on mp.id = p.pabrik_id
            ),
            cte_karyawan_ikut AS (
              SELECT t.*, mk.nama, mk.main_type_karyawan_id
              FROM tkbms t
              JOIN m_karyawans mk ON mk.id = t.karyawan_id
              WHERE t.karyawan_id = :karyawanid
            )
            select * from (
              SELECT 
                p.penjualan_id,k.karyawan_id,k.nama,k.type_karyawan_id,
                case 
                  when k.type_karyawan_id = 1 then 'SOPIR'
                  when k.type_karyawan_id = 2 then 'TKBM'
                  when k.type_karyawan_id is null then 'TKBM'
                end as keterangan,
                k.main_type_karyawan_id, cte_t.total,
                p.tanggal_penjualan,p.netto,p.model_kerja_id,p.nama_pabrik, 
                CASE 
                  WHEN k.type_karyawan_id = 1 THEN cte_s.tarif_perkg
                  WHEN k.type_karyawan_id = 2 THEN cte_t.tarif_perkg
                END AS tarif_perkg,
                xx.tkbms,
                xx_sopir.sopir,
                CASE 
                  WHEN k.type_karyawan_id = 1 THEN cte_s.jumlah_uang
                  WHEN k.type_karyawan_id = 2 THEN cte_t.jumlah_uang
                  WHEN k.type_karyawan_id is null THEN 0
                END AS jumlah_uang,
                CASE 
                  WHEN k.karyawan_id IS NULL THEN true ELSE false
                END AS is_tkbm_alpha
              FROM cte_penjualan p
              LEFT JOIN cte_karyawan_ikut k ON p.penjualan_id = k.penjualan_id
              LEFT JOIN cte_tkbm_borongan_tarif cte_t ON cte_t.penjualan_id = p.penjualan_id
              LEFT JOIN cte_sopir_borongan_tarif cte_s ON cte_s.penjualan_id = p.penjualan_id
              left join (
              	select t3.penjualan_id, p3.tanggal_penjualan,string_agg(mk3.nama,'~') as tkbms
						from penjualans p3 
						inner join tkbms t3 on p3.id  = t3.penjualan_id 
						inner join m_karyawans mk3 on mk3.id  = t3.karyawan_id  
						where t3.deleted_at is null 
						and t3.type_karyawan_id = 2 --TKBM
						group by t3.penjualan_id, p3.netto, p3.tanggal_penjualan, p3.model_kerja_id,p3.tarif_tkbm_borongan,p3.tarif_sopir_borongan 
             
              ) as xx on xx.penjualan_id = p.penjualan_id
               left join (
              			select t3.penjualan_id, p3.tanggal_penjualan,mk3.nama as sopir
							from penjualans p3 
							inner join tkbms t3 on p3.id  = t3.penjualan_id 
							inner join m_karyawans mk3 on mk3.id  = t3.karyawan_id  
							where t3.deleted_at is null 
						and t3.type_karyawan_id = 1 --SOPIR 
              ) as xx_sopir on xx_sopir.penjualan_id = p.penjualan_id
              ORDER BY p.created_at DESC
            ) as x
			 WHERE NOT (x.model_kerja_id = 2 AND x.is_tkbm_alpha = true)
			 AND NOT (x.main_type_karyawan_id = 1 AND x.is_tkbm_alpha = true)", [
      'karyawanid' => $karyawanid,
    ]);
    // }



    $mapItems = collect($items)
      ->filter(function ($item) use ($karyawan) {
        $item = (array) $item;

        // if ($item['model_kerja_id'] == 2 && $item['is_tkbm_alpha'] != true) {
        //     // return $item;
        //     // return true;
        // }

        // if ($item['model_kerja_id'] == 2 && $item['is_tkbm_alpha'] == true) {
        //   return false;
        // }

        return true;
        // return true;
      })->map(function ($item) {
        $item = (array) $item;
        $item['tkbms'] = explode('~', $item['tkbms']);
        $tanggal = Carbon::parse($item['tanggal_penjualan']);
        $item['created_at_formatted'] = $tanggal->translatedFormat('l, d-F-Y');

        $item['tarif_perkg_rp'] = 'Rp ' . number_format($item['tarif_perkg'], 0, ',', '.');
        $item['jumlah_uang_rp'] = 'Rp ' . number_format($item['jumlah_uang'], 0, ',', '.');
        return $item;
      });


    // return $mapItems;


    // return $mapItems;


    $totalNetto = $mapItems->sum('netto');




    $colspanTkbm = $mapItems->max('total');

    $pabrik = M_pabrik::all();


    // return $mapItems;
    return view('pages.penggajianv2.detail', [
      'items' => $mapItems,
      'colspanTKBM' => $colspanTkbm,
      'colspanPABRIK' => count($pabrik),
      'pabriks' => $pabrik,
      'karyawan' => $karyawan,
      'totalNetto' => $totalNetto,
      'totalUang' => $mapItems->sum('jumlah_uang'),
    ]);
  }
}
// with('success', 'Transaksi berhasil disimpan!');