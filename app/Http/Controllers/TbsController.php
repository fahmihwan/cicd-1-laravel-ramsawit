<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Repositories\PeriodeRepository;
use App\Models\M_type_tbs;
use App\Models\Pembelian_tbs;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;

class TbsController extends Controller
{


    protected $periodeRepo;

    public function __construct(PeriodeRepository $periodeRepo)
    {

        $this->periodeRepo = $periodeRepo;
    }

    public function wrap_periode(Request $request, $menu)
    {

        $tanggal = $request->input('tanggal');
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $TBS_TYPE =  Utils::mappingTBS_type($menu);
        if ($TBS_TYPE == null) {
            return "NOT FOUND";
        }

        $query = Periode::select('periodes.*', DB::raw('SUM(pembelian_tbs.netto) as total_netto'))
            ->leftJoin('pembelian_tbs', function ($join) use ($TBS_TYPE) {
                $join->on('periodes.id', '=', 'pembelian_tbs.periode_id')
                    ->where('pembelian_tbs.tbs_type_id', $TBS_TYPE['id'])
                    ->whereNull('pembelian_tbs.deleted_at');
            })
            ->groupBy('periodes.id');

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
        // $query->where('pembelian_tbs.tbs_type_id', $TBS_TYPE['id']);
        $data = $query->paginate($perPage)->appends($request->query());


        return view('pages.pembelian_TBS.wrapperiode', [
            'items' =>  $data,
            'menu' => $menu
        ]);
    }


    public function index(Request $request, string $menu, $periode)
    {
        $tanggal = $request->input('tanggal');
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $TBS_TYPE =  Utils::mappingTBS_type($menu);
        if ($TBS_TYPE == null) {
            return "NOT FOUND";
        }

        $periodeExists = Periode::select('periodes.*', DB::raw('SUM(pembelian_tbs.netto) as total_netto'))
            ->leftJoin('pembelian_tbs', function ($join) use ($TBS_TYPE) {
                $join->on('periodes.id', '=', 'pembelian_tbs.periode_id')
                    ->where('pembelian_tbs.tbs_type_id', $TBS_TYPE['id'])
                    ->whereNull('pembelian_tbs.deleted_at');
            })->where('periodes.id', $periode)
            ->groupBy('periodes.id')
            ->firstOrFail();

        if (!$periodeExists) {
            return "NOT FOUND";
        }

        $query = Pembelian_tbs::with([
            'periode' => function ($query) {
                $query->select('id', 'periode', 'periode_mulai', 'periode_berakhir');
            }
        ])->where('tbs_type_id', $TBS_TYPE['id'])
            ->where('periode_id', $periode);

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal_pembelian', $tanggal);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama_customer', 'ILIKE', "%$search%")
                    ->orWhere('tbs_type_id', 'ILIKE', "%$search%")
                    ->orWhere('netto', 'ILIKE', "%$search%")
                    ->orWhere('harga', 'ILIKE', "%$search%")
                    ->orWhere('uang', 'ILIKE', "%$search%")
                    ->orWhere('timbangan_first', 'ILIKE', "%$search%")
                    ->orWhere('timbangan_second', 'ILIKE', "%$search%")
                    ->orWhere('bruto', 'ILIKE', "%$search%")
                    ->orWhere('sortasi', 'ILIKE', "%$search%");
            });
        }
        $query->orderBy('created_at', 'desc');

        $data = $query->paginate($perPage)->appends($request->query());

        return view('pages.pembelian_TBS.index', [
            'items' =>  $data,
            'title' => $TBS_TYPE['text'],
            'menu' => $menu,
            'periode' => $periodeExists
        ]);
    }

    public function store(Request $request, $menu, $periode)
    {

        $request->merge([
            'periode_id' => $periode,
            'uang' => str_replace('.', '', $request->input('uang'))
        ]);


        $rules = [
            'tanggal_pembelian' => 'required|date',
            'periode_id' => 'required',
            'nama_customer' => 'required|max:50',
            'netto' => 'required|integer',
            'harga' => 'required|integer',
            'uang' => 'required|integer'
        ];

        $TBS_TYPE =  Utils::mappingTBS_type($menu);
        $validated = null;
        if ($TBS_TYPE == null) {
            return "NOT FOUND";
        }

        if ($menu == 'RUMAH') {
            $validated = $request->validate($rules);
            $validated['tbs_type_id'] = $TBS_TYPE['id'];
        } else if ($menu == 'LAHAN') {
            $validated = $request->validate($rules);
            $validated['tbs_type_id'] = $TBS_TYPE['id'];
        } else if ($menu == 'RAM') {
            $rules['timbangan_first'] = 'required|integer';
            $rules['timbangan_second'] = 'required|integer';
            $rules['sortasi'] = 'required|numeric';
            $rules['bruto'] = 'required|integer';
            $validated = $request->validate($rules);
            $validated['tbs_type_id'] = $TBS_TYPE['id'];
        } else {
            return "NOT FOUND";
        }

        if ($this->periodeRepo->validateIsCorrectPeriodeMulai($validated['periode_id'], $validated['tanggal_pembelian'])) {
            return redirect()->back()->with('error', 'tanggal penjualan tidak berada pada periode saat ini')->withInput();;
        }


        try {
            DB::beginTransaction();
            Pembelian_tbs::create($validated);

            $periode = Periode::findOrFail($validated['periode_id']);
            $stok = $periode->stok + $validated['netto'];
            $periode->update([
                'stok' => $stok,
            ]);

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $menu, $periode, $id)
    {
        $request->merge([
            'uang' => str_replace('.', '', $request->input('uang'))
        ]);

        $rules = [
            'nama_customer' => 'required|max:50',
            'netto' => 'required|integer',
            'harga' => 'required|integer',
            'uang' => 'required|integer'
        ];

        $TBS_TYPE =  Utils::mappingTBS_type($menu);
        $validated = null;
        if ($TBS_TYPE == null) {
            return "NOT FOUND";
        }

        if ($menu == 'RUMAH') {
            $validated = $request->validate($rules);
        } else if ($menu == 'LAHAN') {
            $validated = $request->validate($rules);
        } else if ($menu == 'RAM') {
            $rules['timbangan_first'] = 'required|integer';
            $rules['timbangan_second'] = 'required|integer';
            $rules['sortasi'] = 'required|numeric';
            $rules['bruto'] = 'required|integer';
            $validated = $request->validate($rules);
        } else {
            return "NOT FOUND";
        }


        try {
            DB::beginTransaction();
            $pembelianTBs =  Pembelian_tbs::findOrFail($id);
            $periode = Periode::findOrFail($pembelianTBs->periode_id);

            if ($periode->periode_berakhir != null) {
                redirect()->back()->with('error', 'Periode telah ditutup');
            }

            $stok = $periode->stok - $pembelianTBs->netto + $validated['netto'];

            $periode->update([
                'stok' => $stok,
            ]);
            $pembelianTBs->update($validated);

            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil diubah!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan: ' . $e->getMessage());
        }

        return redirect('pembelian/tbs/' . $menu . '/view');
    }

    public function destroy($menu, $id)
    {
        $TBS_TYPE =  Utils::mappingTBS_type($menu);

        if ($TBS_TYPE == null) {
            return "NOT FOUND";
        }

        $karyawan = Pembelian_tbs::findOrFail($id);

        $karyawan->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
