<?php

namespace App\Http\Controllers;

use App\Models\M_karyawan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Return_;

class SlipGajiController extends Controller
{
    public function index()
    {
        return view('pages.slip-gaji.index', [
            'items' =>  M_karyawan::latest()->get()
        ]);
    }

    public function detail($id, Request $request)
    {



        $karyawan = M_karyawan::findOrFail($id);


        $karyawanId = $karyawan->id;
        $bulan = $request->input('bulan'); // "06"
        $tahun = $request->input('tahun'); // "2025"


        $items =  DB::select("SELECT 
                        p.id,
                        p.created_at,
                        p.netto,
                        mt.tarif_perkg,
                        STRING_AGG(mk.nama, '~') AS tkbms,
                        count(t.id) as total,
                        CASE 
                            WHEN SUM(CASE WHEN mk.id = ? THEN 1 ELSE 0 END) = 0 THEN true
                            ELSE false
                        END AS alpha,
                        CASE 
                            WHEN SUM(CASE WHEN mk.id = ? THEN 1 ELSE 0 END) != 0 THEN (p.netto * mt.tarif_perkg)/count(t.id)
                            ELSE 0
                        END AS jumlah_uang,
                        CASE 
                            WHEN SUM(CASE WHEN mk.id = ? THEN 1 ELSE 0 END) != 0 
                            THEN CEIL((p.netto * mt.tarif_perkg) / COUNT(t.id))
                            ELSE 0
                        END AS jumlah_uang_bulat
                    FROM penjualans p
                    LEFT JOIN tkbms t ON t.penjualan_id = p.id
                    LEFT JOIN m_karyawans mk ON mk.id = t.karyawan_id
                    inner join m_tarifs mt on mt.id = p.tarif_tkbm_id 	
                        WHERE EXTRACT(MONTH FROM p.created_at) = ?
                        AND EXTRACT(YEAR FROM p.created_at) = ?
                        AND p.deleted_at is null
  		                AND t.deleted_at is null
                    GROUP BY p.id, p.created_at,mt.tarif_perkg", [$karyawanId, $karyawanId, $karyawanId, $bulan, $tahun]);




        $mapItems = collect($items)->map(function ($item) {
            $item = (array) $item;
            $item['tkbms'] = explode('~', $item['tkbms']);



            $tanggal = Carbon::parse($item['created_at']);
            $item['created_at_formatted'] = $tanggal->translatedFormat('l, d-F-Y H:i') . ' WIB';

            $item['tarif_perkg_rp'] = 'Rp ' . number_format($item['tarif_perkg'], 0, ',', '.');
            $item['jumlah_uang_rp'] = 'Rp ' . number_format($item['jumlah_uang'], 0, ',', '.');
            $item['jumlah_uang_bulat_rp'] = 'Rp ' . number_format($item['jumlah_uang_bulat'], 0, ',', '.');
            return $item;
        });



        $totalNetto = $mapItems->sum('netto');
        $totalUang   = 'Rp ' . number_format($mapItems->sum('jumlah_uang'), 0, ',', '.');



        $colspanTkbm = $mapItems->max('total');

        return view('pages.slip-gaji.detail', [
            'items' => $mapItems,
            'colspanTKBM' => $colspanTkbm,
            'karyawan' => $karyawan,
            'totalNetto' => $totalNetto,
            'totalUang' => $totalUang
        ]);
    }
}
