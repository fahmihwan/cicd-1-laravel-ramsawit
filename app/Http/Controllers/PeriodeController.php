<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Repositories\PeriodeRepository;
use App\Models\Penjualan;
use App\Models\Periode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeriodeController extends Controller
{

    protected $periodeRepo;
    public function __construct(PeriodeRepository $periodeRepo)
    {
        $this->periodeRepo = $periodeRepo;
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


        return view('pages.periode.index', [
            'items' =>  $data,
            'get_first_periode' => Periode::orderBy('periode', 'desc')->first()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $validated = $request->validate([
            'periode' => 'required|integer',
            'periode_mulai' => 'required|date',
        ]);

        $isOverlap =  $this->periodeRepo->validateIsOverlapPeriode($validated['periode_mulai']);

        if ($isOverlap) {
            return redirect()->back()->with('error', 'tanggal periode sudah digunakan');
        }


        $isExist = Periode::where('periode', $validated['periode'])->exists();

        if ($isExist) {
            return redirect('/periode')->with('error', 'periode sudah tersedia!');
        }


        $validated['periode_berakhir'] = null;
        $validated['stok'] = 0;

        Periode::create($validated);
        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'periode_mulai' => ['required', 'date'],
            'periode_berakhir' => ['nullable', 'date', 'after_or_equal:periode_mulai'],
            'ops_id' => ['nullable', 'integer'],
        ]);

        $periode =  Periode::findOrFail($id);

        if (!empty($validated['periode_berakhir'])) {
            $conflict = Periode::where('id', '!=', $id)
                ->where('periode', '>', $periode->periode)
                ->whereDate('periode_mulai', '<=', $validated['periode_berakhir'])->exists();

            if ($conflict) {
                return redirect()->back()->with('error', 'Periode bertabrakan dengan periode lain (termasuk jika tanggal akhir menyentuh tanggal mulai)');
            }

            $penjualan = Penjualan::where('periode_id', $id)
                ->where(function ($q) use ($periode, $validated) {
                    $q->whereDate('tanggal_penjualan', '<', $periode->periode_mulai)
                        ->orWhereDate('tanggal_penjualan', '>', $validated['periode_berakhir']);
                });


            if ($penjualan->count() > 0) {

                $lastPenjualan = $penjualan->orderBy('tanggal_penjualan', 'desc')->first();

                $end =  Carbon::parse($lastPenjualan->tanggal_penjualan)->translatedFormat('d F Y');
                $reqEnd = Carbon::parse($validated['periode_berakhir'])->translatedFormat('d F Y');

                return redirect()->back()->with('error', "tidak dapat di edit dengan periode berakhir $reqEnd, karena masih ada data dengan tanggal penjualan $end");
            }
        }



        $periode->update($validated);
        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $periode = Periode::findOrFail($id);

        $totalPembelian = $periode->pembelians()->count();
        $totalPenjualan = $periode->penjualans()->count();

        if ($totalPembelian > 0 || $totalPenjualan > 0) {
            return redirect()->back()->with('error', 'Periode tidak bisa dihapus karena sudah ada transaksi');
        }
        $periode->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
