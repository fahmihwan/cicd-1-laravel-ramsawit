<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Repositories\PenggajianRepository;
use App\Http\Repositories\PenjualanRepository;
use App\Http\Repositories\PeriodeRepository;
use App\Models\M_karyawan;
use App\Models\M_pekerjaanNonSawit;
use App\Models\Penggajian_penjualan;
use App\Models\Penjualan;
use App\Models\Periode;
use App\Models\Tkbm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PekerjaanNonSawitController extends Controller
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

    public function index_m_pekerjaan()
    {
        return view('pages.master_pekerjaan_lain.index', [
            'items' =>  M_pekerjaanNonSawit::latest()->get()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_m_pekerjaan(Request $request)
    {

        $validated = $request->validate([
            'jenis_pekerjaan' => 'required|max:50',
        ]);

        M_pekerjaanNonSawit::create($validated);
        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update_m_pekerjaan(Request $request, string $id)
    {
        $validated = $request->validate([
            'jenis_pekerjaan' => 'required|max:50',
        ]);

        $karyawan =  M_pekerjaanNonSawit::findOrFail($id);
        $karyawan->update($validated);
        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy_m_pekerjaan(string $id)
    {
        $karyawan = M_pekerjaanNonSawit::findOrFail($id);

        $karyawan->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }



    public function wrap_periode(Request $request)
    {

        $tanggal = $request->input('tanggal');
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = Periode::select('periodes.*')
            // ->leftJoin('penjualans', function ($join) use ($DO_TYPE) {
            //     $join->on('periodes.id', '=', 'penjualans.periode_id')
            //         ->where('penjualans.do_type_id', $DO_TYPE['id'])
            //         ->whereNull('penjualans.deleted_at');
            // })
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


        return view('pages.pekerjaan_nonsawit.wrapperiode', [
            'items' =>  $data,
        ]);
    }

    public function index(Request $request, $periode)
    {

        $tanggal = $request->input('tanggal');
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');


        $periodeExists = Periode::select('periodes.*', DB::raw('SUM(penjualans.pekerjaan_lain_id) as total_data'))
            ->leftJoin('penjualans', function ($join) {
                $join->on('periodes.id', '=', 'penjualans.periode_id')
                    ->whereNotNull('penjualans.pekerjaan_lain_id',)
                    ->whereNull('penjualans.deleted_at');
            })->where('periodes.id', $periode)
            ->groupBy('periodes.id')
            ->firstOrFail();

        if (!$periodeExists) {
            return "NOT FOUND";
        }


        $query = Penjualan::with([
            'pekerjaan_lain' => fn($q) => $q->withTrashed()->select('id', 'jenis_pekerjaan'),
            'model_kerja:id,model_kerja',
            'tarif_sopir' => fn($q) => $q->withTrashed(),
            'tarif_tkbm' => fn($q) => $q->withTrashed(),
            'periode' => fn($q) => $q->withTrashed()->select('id', 'periode', 'periode_mulai', 'periode_berakhir'),
            'pabrik' => fn($q) => $q->withTrashed()->select("id", "nama_pabrik"),
            'sopir:id,nama',
            'tkbms' => fn($q) => $q->where('type_karyawan_id', 2)
                ->select('id', 'karyawan_id', 'penjualan_id', 'type_karyawan_id', 'tarif_tkbm_borongan', 'tarif_sopir_borongan'),
            'tkbms.karyawan:id,nama'
        ])->whereNotNull('pekerjaan_lain_id')
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
        return view('pages.pekerjaan_nonsawit.index', [
            'items' =>  $data,
            'karyawans' => Utils::getKaryawanWithJobs(),
            'list_pekerjaan_nonsawit' => M_pekerjaanNonSawit::select('id', 'jenis_pekerjaan')->latest()->get(),
            'periode' => $periodeExists
        ]);
    }

    public function store(Request $request,  $periode)
    {

        if ($request->input('model_kerja_id') == 2) {
            $this->handleBoronganMergeRequest($request);
        }

        $request->merge([
            'periode_id' => $periode,
        ]);

        $validated = $request->validate($this->getStoreValidationRules());

        if ($this->periodeRepo->validateIsCorrectPeriodeMulai($validated['periode_id'], $validated['tanggal_penjualan'])) {
            return redirect()->back()->with('error', 'tanggal penjualan tidak berada pada periode saat ini')->withInput();;
        }

        try {
            DB::beginTransaction();

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
            if ($validated['model_kerja_id'] == 2) {
                $data  = $this->handlePayloadArrayBorongan($validated, $penjualan->id, $request->data_tkbm_dinamis_borongan_json);
                Tkbm::insert($data);
            }

            DB::commit();
            return redirect('/pekerjaan-nonsawit/' . $periode . '/view')->with('success', 'Transaksi berhasil disimpan!');
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect()->back()->with('error', $e->getMessage())->withInput();;
        }
    }



    private function handleBoronganMergeRequest(Request $request)
    {
        $request->merge([
            'sopir_id' => $request->input('sopir_borongan_id'),
            'tarif_sopir_borongan' => $request->input('tarif_sopir_borongan'),
            'tkbm_id' => $request->input('tkbm_borongan_id'),
            'model_kerja_id' => 2,
            'data_tkbm_dinamis_borongan_json' => json_decode($request->input('data_tkbm_dinamis_borongan_json'))
        ]);
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
                'jumlah_uang' => $d->tarif_borongan,
                'pekerjaan_lain_id' => $validated['pekerjaan_lain_id']
            ];
        }


        if ($validated['sopir_id'] != null) {
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
                'jumlah_uang' => $validated['tarif_sopir_borongan'],
                'pekerjaan_lain_id' => $validated['pekerjaan_lain_id']
            ];
        }

        return $data;
    }


    public function update(Request $request, $periode, $id)
    {

        if ($request->input('model_kerja_id') == 2) {
            $this->handleBoronganMergeRequest($request);
        }

        $request->merge([
            'periode_id' => $periode,
        ]);

        $validated = $request->validate($this->getUpdateValidationRules());;
        try {
            DB::beginTransaction();

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
            if ($validated['model_kerja_id'] == 2) {
                $data  = $this->handlePayloadArrayBorongan($validated, $penjualan->id, $request->data_tkbm_dinamis_borongan_json);
                Tkbm::insert($data);
            }

            DB::commit();
            return redirect('/pekerjaan-nonsawit/' . $periode . '/view')->with('success', 'Transaksi berhasil diubah!');
        } catch (\Throwable $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Gagal menyimpan transaksi: ' . $e->getMessage());
        }
    }


    public function destroy($id)
    {
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


    private function getStoreValidationRules(): array
    {
        return [
            'tanggal_penjualan' => 'required|date',
            'periode_id' => 'required',
            'sopir_id' => 'nullable|integer',
            'tkbm_id' => 'nullable|array',
            'model_kerja_id' => 'required',
            'tarif_sopir_id' => 'nullable|numeric',
            'tarif_tkbm_id' => 'nullable|numeric',
            'tarif_sopir_borongan' => 'nullable|numeric',
            'data_tkbm_dinamis_borongan_json' => 'nullable|array',
            'pekerjaan_lain_id' => 'required'
        ];
    }

    private function getUpdateValidationRules(): array
    {
        return [
            'sopir_id' => 'nullable|integer',
            'model_kerja_id' => 'required',
            'tarif_sopir_id' => 'nullable|numeric',
            'tarif_tkbm_id' => 'nullable|numeric',
            'tarif_sopir_borongan' => 'nullable|numeric',
            'data_tkbm_dinamis_borongan_json' => 'nullable|array',
            'pekerjaan_lain_id' => 'required'
        ];
    }
}
