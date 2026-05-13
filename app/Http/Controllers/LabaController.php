<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PembelianRepository;
use App\Http\Repositories\PenjualanRepository;
use App\Models\Periode;
use Illuminate\Http\Request;


class LabaController extends Controller
{
    private $pembalianRepo;
    private $penjualanRepo;

    public function __construct(PembelianRepository $pembelianRepo, PenjualanRepository $penjualanRepo)
    {
        $this->pembalianRepo = $pembelianRepo;
        $this->penjualanRepo = $penjualanRepo;
    }


    public function index(Request $request)
    {

        $tanggal = $request->input('tanggal');
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = Periode::query();

        if ($request->filled('tanggal')) {
            $query->whereDate('periode_mulai', $tanggal);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('periode', 'ILIKE', "%$search%");
                // ->orWhere('harga', 'ILIKE', "%$search%")
                // ->orWhere('uang', 'ILIKE', "%$search%");
            });
        }
        $query->orderBy('periode', 'desc');

        $data = $query->paginate($perPage)->appends($request->query());

        return view('pages.laba.index', [
            'items' =>  $data,
            'get_first_periode' => Periode::orderBy('periode', 'desc')->first()
        ]);
    }

    public function detail($id)
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


        return view('pages.laba.detail', [
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
        ]);
    }
}
