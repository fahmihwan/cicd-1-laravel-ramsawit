<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PembelianRepository;
use App\Http\Repositories\PenggajianRepository;
use App\Http\Repositories\PenjualanRepository;
use App\Models\M_karyawan;
use App\Models\M_pabrik;
use App\Models\Penggajian_karyawan;
use App\Models\Periode;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PdfExportController extends Controller
{

    protected $pembalianRepo;
    protected $penjualanRepo;
    protected $penggajianRepo;
    public function __construct(
        PenggajianRepository $penggajianRepo,
        PembelianRepository $pembelianRepo,
        PenjualanRepository $penjualanRepo
    ) {
        $this->penggajianRepo = $penggajianRepo;
        $this->pembalianRepo = $pembelianRepo;
        $this->penjualanRepo = $penjualanRepo;
    }

    public function gaji_karyawan($penggajianid, $karyawanid)
    {

        $karyawan = M_karyawan::with(['main_type_karyawan'])->findOrFail($karyawanid);

        $items = $this->penggajianRepo->detail_gaji($penggajianid, $karyawanid);


        $mapItems = collect($items)
            ->filter(function ($row) use ($karyawan) {
                if ($karyawan->main_type_karyawan_id == 1) {
                    return !$row->is_tkbm_alpha;
                }
                return true;
            })
            ->map(function ($item) {
                $item = (array) $item;
                $item['tkbms'] = explode('~', $item['tkbms']);
                $tanggal = Carbon::parse($item['tanggal_penjualan']);
                $item['created_at_formatted'] = $tanggal->translatedFormat('l, d-F-Y');

                $item['tarif_perkg_rp'] =  number_format($item['tarif_perkg'], 0, ',', '.');
                $item['jumlah_uang_rp'] = 'Rp ' . number_format($item['jumlah_uang'], 0, ',', '.');
                return $item;
            });


        $totalNetto = $mapItems->where('model_kerja_id', 1)->sum('netto');

        $colspanTkbm = $mapItems->max('total');

        $jumlah_uang = $mapItems->where('model_kerja_id', 1)->sum('jumlah_uang');

        $pabrik = M_pabrik::all();

        $penggajian_karyawan =  Penggajian_karyawan::where([
            ['penggajian_id', '=', $penggajianid],
            ['karyawan_id', '=', $karyawanid],
        ])->first();



        $pinjaman_saat_ini = DB::select("SELECT
                            pu.karyawan_id,
                            mk.nama,
                            mtk.type_karyawan,
                            (SUM(pu.nominal_peminjaman) - SUM(pu.nominal_pengembalian)) AS sisa_pinjaman
                        FROM pinjaman_uangs pu
                        INNER JOIN m_karyawans mk ON mk.id = pu.karyawan_id
                        INNER JOIN m_type_karyawans mtk ON mtk.id = mk.main_type_karyawan_id
                        WHERE pu.deleted_at IS null
                        and pu.karyawan_id = ?
                        GROUP BY pu.karyawan_id, mk.nama, mtk.type_karyawan", [$karyawanid]);
        if (count($pinjaman_saat_ini) == 1) {
            $pinjaman_saat_ini = $pinjaman_saat_ini[0]->sisa_pinjaman;
        } else {
            $pinjaman_saat_ini = 0;
        }

        $pdf = Pdf::loadView('pages.export_pdf.penggajian-pdf', [
            'items' => $mapItems,
            'colspanTKBM' => $colspanTkbm,
            'colspanPABRIK' => count($pabrik),
            'pabriks' => $pabrik,
            'karyawan' => $karyawan,
            'totalNetto' => $totalNetto,
            'totalUang' => $jumlah_uang,
            'penggajian_karyawan' => $penggajian_karyawan,
            'pinjaman_saat_ini' => $pinjaman_saat_ini
        ])->setPaper('a4', 'portrait');
        // landscape
        // portrait
        return $pdf->download('gaji-karyawan.pdf');


        // return $mapItems;
        return view('pages.export_pdf.penggajian-pdf', [
            'items' => $mapItems,
            'colspanTKBM' => $colspanTkbm,
            'colspanPABRIK' => count($pabrik),
            'pabriks' => $pabrik,
            'karyawan' => $karyawan,
            'totalNetto' => $totalNetto,
            'totalUang' => $jumlah_uang,
        ]);
    }


    public function detail_laba($id)
    {

        $periode = Periode::find($id);

        if (!$periode) {
            return redirect('/periode')->with('error', 'Data tidak ditemukan');
        }

        $pembelian = $this->pembalianRepo->pembelianLaba($periode->id);
        $penjualan = $this->penjualanRepo->penjualanLaba($periode->id);

        $penjualanGroupByOps = $this->penjualanRepo->penjualanGroupByOpsLaba($periode->id);

        $finalCairDo = $penjualanGroupByOps->flatMap(fn($g) => $g['values'])->sum('uang');
        $finalTotalOps = $penjualanGroupByOps->flatMap(fn($g) => $g['values'])->sum('total_ops');

        $finalModal = $pembelian->sum('uang');
        $labaBersih = $finalCairDo - $finalModal - $finalTotalOps;


        $pdf = Pdf::loadView('pages.export_pdf.laba-pdf', [
            'pembelian' => $pembelian,
            'penjualan' => $penjualan,
            'periode' => $periode,
            'penjualanGroupByOps' => $penjualanGroupByOps,
            'finalLaba' => [
                'cairDo' => $finalCairDo,
                'totalOps' => $finalTotalOps,
                'modal' => $finalModal,
                'labaBersih' => $labaBersih
            ]
        ])->setPaper('a4', 'portrait');

        return $pdf->download('laba.pdf');

        // return view('pages.export_pdf.laba-pdf', [
        // 'pembelian' => $pembelian,
        // 'penjualan' => $penjualan,
        // 'periode' => $periode,
        // 'penjualanGroupByOps' => $penjualanGroupByOps,
        // 'finalLaba' => [
        //     'cairDo' => $finalCairDo,
        //     'totalOps' => $finalTotalOps,
        //     'modal' => $finalModal,
        //     'labaBersih' => $labaBersih
        // ]
        // ]);
    }
}
