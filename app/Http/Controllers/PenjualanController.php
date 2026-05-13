<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Repositories\PenggajianRepository;
use App\Http\Repositories\PenjualanRepository;
use App\Http\Repositories\PeriodeRepository;
use App\Models\M_jobs;
use App\Models\M_karyawan;
use App\Models\M_pabrik;
use App\Models\M_tarif;
use App\Models\Pembelian_tbs;
use App\Models\Penggajian_karyawan;
use App\Models\Penggajian_penjualan;
use App\Models\Penjualan;
use App\Models\Periode;
use App\Models\Tkbm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;



class PenjualanController extends Controller
{
    protected $penjualanRepo;
    protected $periodeRepo;
    protected $penggajianRepo;

    public function __construct(
        PenjualanRepository $penjualanRepo,
        PeriodeRepository $periodeRepo,
        PenggajianRepository $penggajianRepo
    ) {
        $this->penjualanRepo = $penjualanRepo;
        $this->periodeRepo = $periodeRepo;
        $this->penggajianRepo = $penggajianRepo;
    }



    public function wrap_periode(Request $request, $menu)
    {

        $tanggal = $request->input('tanggal');
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $DO_TYPE = Utils::mappingDO_type($menu);
        if ($DO_TYPE == null) {
            return "NOT FOUND";
        }

        $query = Periode::select('periodes.*', DB::raw('SUM(penjualans.netto) as total_netto'))
            ->leftJoin('penjualans', function ($join) use ($DO_TYPE) {
                $join->on('periodes.id', '=', 'penjualans.periode_id')
                    ->where('penjualans.do_type_id', $DO_TYPE['id'])
                    ->whereNull('penjualans.deleted_at');
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
        $data = $query->paginate($perPage)->appends($request->query());


        return view('pages.penjualan_TBS.wrapperiode', [
            'items' =>  $data,
            'menu' => $menu
        ]);
    }

    public function index(Request $request, string $menu, $periode)
    {

        $tanggal = $request->input('tanggal');
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $DO_TYPE = Utils::mappingDO_type($menu);
        if ($DO_TYPE == null) {
            return "NOT FOUND";
        }



        $periodeExists = Periode::select('periodes.*', DB::raw('SUM(penjualans.netto) as total_netto'))
            ->leftJoin('penjualans', function ($join) use ($DO_TYPE) {
                $join->on('periodes.id', '=', 'penjualans.periode_id')
                    ->where('penjualans.do_type_id', $DO_TYPE['id'])
                    ->whereNull('penjualans.deleted_at');
            })->where('periodes.id', $periode)
            ->groupBy('periodes.id')
            ->firstOrFail();


        if (!$periodeExists) {
            return "NOT FOUND";
        }


        $query = Penjualan::with([
            'model_kerja:id,model_kerja',
            'tarif_sopir' => fn($q) => $q->withTrashed(),
            'tarif_tkbm' => fn($q) => $q->withTrashed(),
            'periode' => fn($q) => $q->withTrashed()->select('id', 'periode', 'periode_mulai', 'periode_berakhir'),
            'pabrik' => fn($q) => $q->withTrashed()->select("id", "nama_pabrik"),
            'sopir:id,nama',
            'tkbms' => fn($q) => $q->where('type_karyawan_id', 2)
                ->select('id', 'karyawan_id', 'penjualan_id', 'type_karyawan_id', 'tarif_tkbm_borongan', 'tarif_sopir_borongan'),
            'tkbms.karyawan:id,nama'
        ])->where('do_type_id', $DO_TYPE['id'])
            ->where('periode_id', $periode);



        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $tanggal);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('netto', 'ILIKE', "%$search%")
                    ->orWhere('harga', 'ILIKE', "%$search%")
                    ->orWhere('uang', 'ILIKE', "%$search%")
                    ->orWhere('timbangan_first', 'ILIKE', "%$search%")
                    ->orWhere('timbangan_second', 'ILIKE', "%$search%")
                    ->orWhere('bruto', 'ILIKE', "%$search%")
                    ->orWhere('sortasi', 'ILIKE', "%$search%")
                    ->orWhereHas('tkbms.karyawan', function ($q) use ($search) {
                        $q->where('nama', 'ILIKE', "%$search%");
                    });;
            });
        }

        $query->orderBy('created_at', 'desc');


        $data = $query->paginate($perPage)->appends($request->query());


        return view('pages.penjualan_TBS.index', [
            'items' =>  $data,
            'title' => $DO_TYPE['text'],
            'menu' => $menu,
            'karyawans' => Utils::getKaryawanWithJobs(),
            'data_list_tarif' => Utils::getListTarif(),
            'data_pabrik' => M_pabrik::all(),
            'periode' => $periodeExists
        ]);
    }



    public function store(Request $request, $menu, $periode)
    {
        if ($request->input('model_kerja_id') == 2) {
            $this->handleBoronganMergeRequest($request);
        } else if ($request->input('model_kerja_id') == 1) { //TONASE
            $this->handleTonaseMergeRequest($request);
        }

        $request->merge([
            'uang' => str_replace('.', '', $request->input('uang')),
            'periode_id' => $periode
        ]);

        $validated = $request->validate($this->getStoreValidationRules());
        $this->periodeRepo->validateIsCorrectPeriodeMulai($validated['periode_id'], $validated['tanggal_penjualan']);

        if ($this->periodeRepo->validateIsCorrectPeriodeMulai($validated['periode_id'], $validated['tanggal_penjualan'])) {
            return redirect()->back()->with('error', 'tanggal penjualan tidak berada pada periode saat ini')->withInput();;
        }

        try {
            DB::beginTransaction();
            $DO_TYPE =  Utils::mappingDO_type($menu);
            if ($DO_TYPE == null) {
                return "NOT FOUND";
            };

            $validated['do_type_id'] = $DO_TYPE['id'];

            $penjualan =  Penjualan::create($validated);

            $isFail = $this->penggajianRepo->syncPenggajian($validated, "CREATE");
            if ($isFail['status'] == true) {
                throw new \Exception($isFail['message']);
            } else if ($isFail['status'] == false && $isFail['penggajianId'] != null) {
                Penggajian_penjualan::create([
                    'penggajian_id' => $isFail['penggajianId'],
                    'penjualan_id' => $penjualan->id,
                ]);
            }

            $data = [];
            if ($validated['model_kerja_id'] == 1) { //tkbm
                $data = $this->handlePayloadArrayTonase($validated, $penjualan->id);
                Tkbm::insert($data);
            } elseif ($validated['model_kerja_id'] == 2) {
                $data  = $this->handlePayloadArrayBorongan($validated, $penjualan->id, $request->data_tkbm_dinamis_borongan_json);
                Tkbm::insert($data);
            }

            DB::commit();
            return redirect('/penjualan/tbs/' . $menu . '/' . $periode . '/view')->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();;
        }
    }



    public function update(Request $request, $menu, $periode, $id)
    {

        if ($request->input('model_kerja_id') == 2) {
            $this->handleBoronganMergeRequest($request);
        } else if ($request->input('model_kerja_id') == 1) { //TONASE
            $this->handleTonaseMergeRequest($request);
        }

        $request->merge([
            'uang' => str_replace('.', '', $request->input('uang'))
        ]);

        $validated = $request->validate($this->getUpdateValidationRules());;


        try {
            DB::beginTransaction();

            $DO_TYPE =  Utils::mappingDO_type($menu);
            if ($DO_TYPE == null) {
                return "NOT FOUND";
            };
            $validated['do_type_id'] = $DO_TYPE['id'];


            $penjualan = Penjualan::with('tkbms:id,karyawan_id,penjualan_id')->findOrFail($id);
            $validated['tanggal_penjualan'] = $penjualan->tanggal_penjualan;
            $validated['arr_karyawanid_olddata_edit'] = $penjualan->tkbms->pluck('karyawan_id')->toArray();

            $isFail = $this->penggajianRepo->syncPenggajian($validated, "UPDATE");

            if ($isFail['status'] == true) {
                throw new \Exception($isFail['message']);
            }

            $penjualan->update($validated);

            Tkbm::where('penjualan_id', $id)->forceDelete();

            $data = [];
            if ($validated['model_kerja_id'] == 1) { //tkbm
                $data = $this->handlePayloadArrayTonase($validated, $penjualan->id);

                Tkbm::insert($data);
            } elseif ($validated['model_kerja_id'] == 2) {
                $data  = $this->handlePayloadArrayBorongan($validated, $penjualan->id, $request->data_tkbm_dinamis_borongan_json);
                Tkbm::insert($data);
            }

            DB::commit();
            return redirect('/penjualan/tbs/' . $menu . '/' . $periode . '/view')->with('success', 'Transaksi berhasil diubah!');
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }


    public function destroy($menu, $id)
    {

        $DO_TYPE =  Utils::mappingDO_type($menu);

        if ($DO_TYPE == null) {
            return "NOT FOUND";
        }

        try {
            DB::beginTransaction();
            $penjualan = Penjualan::with('tkbms:id,karyawan_id,penjualan_id')->findOrFail($id);

            $validated['tanggal_penjualan'] = $penjualan->tanggal_penjualan;
            $validated['arr_karyawanid_olddata_delete'] = $penjualan->tkbms->pluck('karyawan_id')->toArray();

            $isFail = $this->penggajianRepo->syncPenggajian($validated, "DELETE");
            if ($isFail['status'] == true) {
                throw new \Exception($isFail['message']);
            }

            $penjualan->delete();
            Tkbm::where('penjualan_id', $penjualan->id)->forceDelete();
            DB::commit();

            return redirect()->back()->with('success', 'Transaksi berhasil dihapus!');
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }






    // UTILSS ====================================================================================

    private function handleBoronganMergeRequest(Request $request)
    {
        $request->merge([
            'tarif_sopir_id' => null,
            'tarif_tkbm_id' => null,
            'ops' => $request->input('ops'),
            'sopir_id' => $request->input('sopir_borongan_id'),
            'tkbm_id' => $request->input('tkbm_borongan_id'),
            'tarif_sopir_borongan' => $request->input('tarif_sopir_borongan'),
            'tarif_tkbm_borongan' => $request->input('tarif_tkbm_borongan'),
            'model_kerja_id' => 2,
            'data_tkbm_dinamis_borongan_json' => json_decode($request->input('data_tkbm_dinamis_borongan_json'))
        ]);
    }

    private function handleTonaseMergeRequest(Request $request)
    {
        $request->merge([
            'tarif_sopir_id' => $request->input('tarif_sopir_id'),
            'tarif_tkbm_id' => $request->input('tarif_tkbm_id'),
            'ops' => $request->input('ops'),
            'sopir_id' => $request->input('sopir_id'),
            'tkbm_id' => $request->input('tkbm_id'),
            'tarif_sopir_borongan' => null,
            'tarif_tkbm_borongan' => null,
            'model_kerja_id' => 1,
            'data_tkbm_dinamis_borongan_json' => []
        ]);
    }

    private function getUpdateValidationRules(): array
    {
        return [
            'pabrik_id' => 'required|integer',
            'sopir_id' => 'required|integer',
            'tkbm_id' => 'nullable|array',
            'ops' => 'required',
            'timbangan_first' => 'required|numeric',
            'timbangan_second' => 'required|numeric',
            'model_kerja_id' => 'required',
            'tarif_sopir_id' => 'nullable|numeric',
            'tarif_tkbm_id' => 'nullable|numeric',
            'tarif_sopir_borongan' => 'nullable|numeric',
            'data_tkbm_dinamis_borongan_json' => 'nullable|array',
            'sortasi' => 'required|numeric',
            'bruto' => 'required|numeric',
            'netto' => 'required|numeric',
            'harga' => 'required|numeric',
            'uang' => 'required|numeric'
        ];
    }


    private function getStoreValidationRules(): array
    {
        return [
            'tanggal_penjualan' => 'required|date',
            'periode_id' => 'required',
            'ops' => 'required',
            'pabrik_id' => 'required|integer',
            'sopir_id' => 'required|integer',
            'tkbm_id' => 'nullable|array',
            'timbangan_first' => 'required|numeric',
            'timbangan_second' => 'required|numeric',
            'model_kerja_id' => 'required',
            'tarif_sopir_id' => 'nullable|numeric',
            'tarif_tkbm_id' => 'nullable|numeric',
            'tarif_sopir_borongan' => 'nullable|numeric',
            'data_tkbm_dinamis_borongan_json' => 'nullable|array',
            'sortasi' => 'required|numeric',
            'bruto' => 'required|integer',
            'netto' => 'required|integer',
            'harga' => 'required|integer',
            'uang' => 'required|integer'
        ];
    }


    private function handlePayloadArrayTonase($validated, $penjualanId)
    {
        $data = [];
        $get_tkbm_agg =  M_karyawan::withTrashed()->whereIn('id', $validated['tkbm_id'])->select('nama')->pluck('nama')->toArray();
        $tkbm_agg = implode('~', $get_tkbm_agg);
        $tarif_tkbm = M_tarif::where('id', $validated['tarif_tkbm_id'])->first();
        $jumlah_uang_tkbm =  $validated['netto'] * $tarif_tkbm->tarif_perkg / count($validated['tkbm_id']);

        $tarif_sopir = M_tarif::where('id', $validated['tarif_sopir_id'])->first();
        $jumlah_uang_sopir =  $validated['netto'] * $tarif_sopir->tarif_perkg;
        foreach ($validated['tkbm_id'] as $d) {
            $data[] = [
                'id' => (string) Str::uuid(),
                'karyawan_id' => $d,
                'penjualan_id' => $penjualanId,
                'type_karyawan_id' => 2, //TKBM
                'model_kerja_id' => $validated['model_kerja_id'],
                'tarif_id' => $validated['tarif_tkbm_id'] ? $validated['tarif_tkbm_id'] : null,
                'tarif_tkbm_borongan' =>  null,
                'tarif_sopir_borongan' => null,
                'is_gaji_dibayarkan' => false,
                'tkbm_agg' => $tkbm_agg,
                'jumlah_tkbm' => count($validated['tkbm_id']),
                'jumlah_uang' => ceil($jumlah_uang_tkbm)
            ];
        }

        $data[] = [
            'id' => (string) Str::uuid(),
            'karyawan_id' => $validated['sopir_id'],
            'penjualan_id' => $penjualanId,
            'type_karyawan_id' => 1, //SOPIR
            'model_kerja_id' => $validated['model_kerja_id'],
            'tarif_id' => $validated['tarif_sopir_id'],
            'tarif_tkbm_borongan' => null,
            'tarif_sopir_borongan' => null,
            'is_gaji_dibayarkan' => false,
            'tkbm_agg' => $tkbm_agg,
            'jumlah_tkbm' => count($validated['tkbm_id']),
            'jumlah_uang' => ceil($jumlah_uang_sopir)
        ];
        return $data;
    }
    private function handlePayloadArrayBorongan($validated, $penjualanId, $data_tkbm_dinamis_borongan_json)
    {
        $data = [];
        $karyawanIds = collect($data_tkbm_dinamis_borongan_json)
            ->pluck('karyawan_id')
            ->all();

        $get_tkbm_agg =  M_karyawan::withTrashed()->whereIn('id', $karyawanIds)->select('nama')->pluck('nama')->toArray();
        $tkbm_agg = implode('~', $get_tkbm_agg);

        foreach ($validated['data_tkbm_dinamis_borongan_json'] as $d) {
            $data[] = [
                'id' => (string) Str::uuid(),
                'karyawan_id' => $d->karyawan_id,
                'penjualan_id' => $penjualanId,
                'type_karyawan_id' => 2, //TKBM
                'model_kerja_id' => $validated['model_kerja_id'],
                'tarif_id' => null,
                'tarif_tkbm_borongan' => $d->tarif_borongan,
                'tarif_sopir_borongan' => null,
                'is_gaji_dibayarkan' => false,
                'tkbm_agg' => $tkbm_agg,
                'jumlah_tkbm' => count($validated['data_tkbm_dinamis_borongan_json']),
                'jumlah_uang' => $d->tarif_borongan
            ];
        }

        $data[] = [
            'id' => (string) Str::uuid(),
            'karyawan_id' => $validated['sopir_id'],
            'penjualan_id' => $penjualanId,
            'type_karyawan_id' => 1, //SOPIR
            'model_kerja_id' => $validated['model_kerja_id'],
            'tarif_id' => null,
            'tarif_tkbm_borongan' => null,
            'tarif_sopir_borongan' => $validated['tarif_sopir_borongan'],
            'is_gaji_dibayarkan' => false,
            'tkbm_agg' => $tkbm_agg,
            'jumlah_tkbm' => count($validated['data_tkbm_dinamis_borongan_json']),
            'jumlah_uang' => $validated['tarif_sopir_borongan']
        ];
        return $data;
    }
}
