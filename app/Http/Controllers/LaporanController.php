<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    public function laporan_semua_stok(Request $request)
    {

        //   SELECT
        //   x.periode_id,x.periode,  x.periode_mulai,x.periode_berakhir,  x.created_at,
        //   SUM(x.bruto_masuk)  AS total_bruto_masuk,
        //   SUM(x.netto_masuk)  AS total_netto_masuk,
        //   SUM(x.bruto_keluar) AS total_bruto_keluar,SUM(x.netto_keluar) AS total_netto_keluar
        // FROM (
        //   -- Pembelian
        //   SELECT
        //     p.id AS periode_id,p.periode,p.periode_mulai,p.periode_berakhir,p.created_at,
        //     sum(
        // 	    case when tbs_type_id = 3 then b.bruto
        // 	    	 when tbs_type_id != 3 then b.netto
        // 	   	end
        //     ) as bruto_masuk,
        //     SUM(b.netto) AS netto_masuk,
        //     0::numeric  AS bruto_keluar,0::numeric  AS netto_keluar
        //   FROM pembelian_tbs b
        //   JOIN periodes p ON p.id = b.periode_id
        //   GROUP BY p.id, p.periode, p.periode_mulai, p.periode_berakhir, p.created_at

        //   UNION ALL
        //   -- Penjualan
        //   SELECT
        //     p.id AS periode_id,p.periode,p.periode_mulai,p.periode_berakhir,p.created_at,
        //     0::numeric AS bruto_masuk,0::numeric AS netto_masuk,SUM(s.bruto) AS bruto_keluar,SUM(s.netto)AS netto_keluar
        //   FROM penjualans s
        //   JOIN periodes p ON p.id = s.periode_id
        //   GROUP BY p.id, p.periode, p.periode_mulai, p.periode_berakhir, p.created_at
        // ) AS x
        // GROUP BY
        //   x.periode_id,x.periode,x.periode_mulai,x.periode_berakhir,x.created_at;

        // Pembelian
        $pembelian = DB::table('pembelian_tbs as b')
            ->join('periodes as p', 'p.id', '=', 'b.periode_id')
            ->select([
                DB::raw('p.id AS periode_id'),
                'p.periode',
                'p.periode_mulai',
                'p.periode_berakhir',
                'p.created_at',
                DB::raw("
            SUM(CASE WHEN b.tbs_type_id = 3 THEN b.bruto ELSE b.netto END) AS bruto_masuk
        "),
                DB::raw('SUM(b.netto) AS netto_masuk'),
                DB::raw('0::numeric AS bruto_keluar'),
                DB::raw('0::numeric AS netto_keluar'),
            ])
            ->groupBy('p.id', 'p.periode', 'p.periode_mulai', 'p.periode_berakhir', 'p.created_at');

        // Penjualan
        $penjualan = DB::table('penjualans as s')
            ->join('periodes as p', 'p.id', '=', 's.periode_id')
            ->select([
                DB::raw('p.id AS periode_id'),
                'p.periode',
                'p.periode_mulai',
                'p.periode_berakhir',
                'p.created_at',
                DB::raw('0::numeric AS bruto_masuk'),
                DB::raw('0::numeric AS netto_masuk'),
                DB::raw('SUM(s.bruto) AS bruto_keluar'),
                DB::raw('SUM(s.netto) AS netto_keluar'),
            ])
            ->groupBy('p.id', 'p.periode', 'p.periode_mulai', 'p.periode_berakhir', 'p.created_at');

        // UNION ALL dua bagian di atas
        $union = $pembelian->unionAll($penjualan);

        // Bungkus union sebagai subquery lalu agregasi akhir
        $subquery = DB::query()
            ->fromSub($union, 'x')
            ->select([
                'x.periode_id',
                'x.periode',
                'x.periode_mulai',
                'x.periode_berakhir',
                'x.created_at',
                DB::raw('SUM(x.bruto_masuk)  AS total_bruto_masuk'),
                DB::raw('SUM(x.netto_masuk)  AS total_netto_masuk'),
                DB::raw('SUM(x.bruto_keluar) AS total_bruto_keluar'),
                DB::raw('SUM(x.netto_keluar) AS total_netto_keluar'),
            ])
            ->groupBy('x.periode_id', 'x.periode', 'x.periode_mulai', 'x.periode_berakhir', 'x.created_at');


        // Eksekusi dan ambil data



        $tanggal = $request->input('tanggal');
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = $subquery;

        if ($request->filled('tanggal')) {
            $query->whereDate('periode_mulai', $tanggal);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                // $q->where('periode', 'ILIKE', "%$search%");
                // ->orWhere('harga', 'ILIKE', "%$search%")
                // ->orWhere('uang', 'ILIKE', "%$search%");
            });
        }
        $query->orderBy('x.periode', 'desc');

        $data = $query->paginate($perPage)->appends($request->query());
        // return $data;

        return view('pages.laporan.laporan-stock', [
            'items' => $data
        ]);
    }
}
