<?php

namespace App\Http\Controllers;

use App\Models\M_karyawan;
use App\Models\Pembelian_tbs;
use App\Models\Penjualan;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stat = $this->stat();
        $totalPembelian_lineChart = $this->total_pembelianTBSLine();
        $totalPembelian_donutChart = $this->total_pembelianDonutChart();
        // $totalPenjualan_lineChart = $this->total_penjualan();

        return view('pages.dashboard', [
            'stat' => $stat,
            'totalPembelian_lineChart' => $totalPembelian_lineChart,
            'totalPembelian_donutChart' => $totalPembelian_donutChart
        ]);
    }



    private function total_pembelianDonutChart()
    {
        return DB::select("SELECT mt.type_tbs as label, sum(pt.netto) as value from pembelian_tbs pt
	       	inner join m_type_tbs mt on mt.id = pt.tbs_type_id 
	       	where pt.deleted_at is null
           group by mt.type_tbs ");
    }

    private function total_pembelianTBSLine()
    {
        return  DB::select("SELECT 
            x.period,
            COALESCE(SUM(x.tbs_rumah), 0) AS tbs_rumah,
            COALESCE(SUM(x.tbs_lahan), 0) AS tbs_lahan,
            COALESCE(SUM(x.tbs_ram),   0) AS tbs_ram
            FROM (
            SELECT 
                date_trunc('month', pt.tanggal_pembelian)::date AS period,
                CASE WHEN tbs_type_id = 1 THEN SUM(netto) END AS tbs_rumah,
                CASE WHEN tbs_type_id = 2 THEN SUM(netto) END AS tbs_lahan,
                CASE WHEN tbs_type_id = 3 THEN SUM(netto) END AS tbs_ram
            FROM pembelian_tbs pt
            WHERE pt.deleted_at is null
            GROUP BY 
                date_trunc('month', pt.tanggal_pembelian)::date, 
                tbs_type_id
            ) AS x
            GROUP BY x.period
            ORDER BY x.period;");
    }


    private function total_penjualan()
    {
        return DB::select("SELECT 
                    x.period,
                    COALESCE(SUM(x.plasma), 0) AS plasma,
                    COALESCE(SUM(x.lu), 0) AS lu,
                    COALESCE(SUM(x.lainnya),   0) AS lainnya
                    FROM (
                        select 
                            date_trunc('month', p.tanggal_penjualan)::date AS period,
                            CASE WHEN do_type_id = 1 THEN SUM(netto) END AS plasma,
                            CASE WHEN do_type_id = 2 THEN SUM(netto) END AS lu,
                            CASE WHEN do_type_id = 3 THEN SUM(netto) END AS lainnya
                        from penjualans p 
                        GROUP BY 
                            date_trunc('month', p.tanggal_penjualan)::date, 
                            do_type_id
                    ) AS x
                    GROUP BY x.period
                    ORDER BY x.period");
    }


    private function stat()
    {
        // select count(id) from m_karyawans mk where main_type_karyawan_id in (1,2)
        $jumlahKaryawan = M_karyawan::whereIn('main_type_karyawan_id', [1, 2])
            ->count();

        // select count(id) from pembelian_tbs pt
        $jumlahPembelianTbs = Pembelian_tbs::count();

        // select count(id) from penjualans p
        $jumlahPenjualan = Penjualan::count();

        // select periode from periodes p order by created_at desc limit 1
        $periodeTerbaru = Periode::orderByDesc('created_at')
            ->value('periode'); // ambil 1 kolom langsung

        return [
            'total_karyawan' => $jumlahKaryawan,
            'total_pembelian_tbs' => $jumlahPembelianTbs,
            'total_penjualan' => $jumlahPenjualan,
            'periode_terbaru' => $periodeTerbaru,
        ];
    }
}
