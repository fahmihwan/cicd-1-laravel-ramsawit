<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Http\Repositories\PenggajianRepository;
use App\Models\M_karyawan;
use App\Models\M_pabrik;
use App\Models\Penggajian;
use App\Models\Penggajian_karyawan;
use App\Models\Penggajian_penjualan;
use App\Models\Penjualan;
use App\Models\Periode;
use App\Models\Pinjaman_uang;
use App\Models\Tkbm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class PenggajianController extends Controller
{

    protected $penggajianRepo;

    public function __construct(PenggajianRepository $penggajianRepo)
    {
        $this->penggajianRepo = $penggajianRepo;
    }

    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = Penggajian::with([
            'penggajian_karyawans:id,penggajian_id,karyawan_id,total_gaji,is_gaji_dibayarkan',
            'penggajian_karyawans.karyawan:id,nama,main_type_karyawan_id',
            'penggajian_karyawans.karyawan.main_type_karyawan:id,type_karyawan',
        ]);
        $query->orderBy('created_at', 'desc');
        $penggajians = $query->paginate($perPage)->appends($request->query());

        return view('pages.penggajian.index', [
            'items' =>  $penggajians,
            'get_first_periode' => Periode::orderBy('periode', 'desc')->first()
        ]);
    }

    public function show_karyawan_penggajian_ajax($penggajian_id)
    {
        $penggajian = DB::select("SELECT distinct p.id as penggajian_id, mk.id as karyawan_id, mk.nama,mtk.type_karyawan, pk.is_gaji_dibayarkan from penggajians p 
			 inner join penggajian_penjualans pp on p.id = pp.penggajian_id 
			 inner join penjualans p2 on p2.id = pp.penjualan_id 
			 inner join tkbms t on p2.id = t.penjualan_id 
			 inner join m_karyawans mk on mk.id = t.karyawan_id
			 left join penggajian_karyawans pk on pk.karyawan_id = mk.id
			 inner join m_type_karyawans mtk on mtk.id = mk.main_type_karyawan_id
             where mtk.id not in (4,3) AND p.id = ? ", [$penggajian_id]);

        return response()->json($penggajian);
    }


    public function detail_gaji($penggajianid, $karyawanid)
    {

        $karyawan = M_karyawan::with(['main_type_karyawan'])->findOrFail($karyawanid);


        $items = $this->penggajianRepo->detail_gaji($penggajianid, $karyawanid);



        $mapItems = collect($items)
            ->map(function ($item) {
                $item = (array) $item;
                $item['tkbms'] = explode('~', $item['tkbms']);
                $tanggal = Carbon::parse($item['tanggal_penjualan']);
                $item['created_at_formatted'] = $tanggal->translatedFormat('l, d-F-Y');

                $item['tarif_perkg_rp'] = 'Rp ' . number_format($item['tarif_perkg'], 0, ',', '.');
                $item['jumlah_uang_rp'] = 'Rp ' . number_format($item['jumlah_uang'], 0, ',', '.');
                return $item;
            });



        $totalNetto = $mapItems->where('model_kerja_id', 1)->sum('netto');
        $totalUang   = $mapItems->where('model_kerja_id', 1)->sum('jumlah_uang');
        $colspanTkbm = $mapItems->max('total');





        $pabrik = M_pabrik::all();


        // return $mapItems;
        return view('pages.penggajianv2.detail', [
            'items' => $mapItems,
            'colspanTKBM' => $colspanTkbm,
            'colspanPABRIK' => count($pabrik),
            // 'pabriks' => $pabrik,
            'karyawan' => $karyawan,
            'totalNetto' => $totalNetto,
            'totalUang' => $totalUang,
        ]);
    }


    public function ambil_gaji_perhari($penggajianid, $karyawanid)
    {

        $karyawan = M_karyawan::with(['main_type_karyawan'])->findOrFail($karyawanid);


        $items = DB::select("SELECT DISTINCT ON (p.id) 
                    p.id,t.penjualan_id,t.karyawan_id,mk.nama,t.type_karyawan_id,
                    CASE 
                        WHEN t.type_karyawan_id = 1 THEN 'SOPIR'
                        WHEN t.type_karyawan_id = 2 THEN 'TKBM'
                    END AS keterangan,
                    mk.main_type_karyawan_id,
                    t.jumlah_tkbm AS total,p.tanggal_penjualan,p.netto,t.model_kerja_id,mp.nama_pabrik,mt.tarif_perkg,t.tkbm_agg as tkbms,mk_sopir.nama as sopir,t.jumlah_uang
                FROM penggajians ps
                    INNER JOIN penggajian_penjualans pp ON ps.id = pp.penggajian_id 
                    INNER JOIN penjualans p ON p.id = pp.penjualan_id 
                    INNER JOIN m_pabriks mp ON p.pabrik_id = mp.id 
                    LEFT JOIN tkbms t ON t.penjualan_id = p.id 
--                    LEFT JOIN tkbms t_a ON t_a.penjualan_id = p.id AND t_a.karyawan_id = :karyawanid 
                    LEFT JOIN m_karyawans mk ON mk.id = t.karyawan_id
                    LEFT JOIN m_tarifs mt ON mt.id = t.tarif_id 
                    INNER JOIN m_karyawans mk_sopir ON mk_sopir.id = p.sopir_id
                    where ps.id = :penggajianid and t.karyawan_id = :karyawanid and p.model_kerja_id = 1
                ORDER BY p.id, p.created_at DESC;", [
            'karyawanid' => $karyawanid,
            'penggajianid' => $penggajianid
        ]);

        $items = collect($items)
            ->map(function ($item) {
                $item = (array) $item;
                $item['tkbms'] = explode('~', $item['tkbms']);
                $tanggal = Carbon::parse($item['tanggal_penjualan']);
                $item['created_at_formatted'] = $tanggal->translatedFormat('l, d-F-Y');

                $item['tarif_perkg_rp'] = 'Rp ' . number_format($item['tarif_perkg'], 0, ',', '.');
                $item['jumlah_uang_rp'] = 'Rp ' . number_format($item['jumlah_uang'], 0, ',', '.');
                return $item;
            });



        $totalNetto = $items->where('model_kerja_id', 1)->sum('netto');
        $totalUang   = $items->where('model_kerja_id', 1)->sum('jumlah_uang');


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





        $penggajian_karyawan =  Penggajian_karyawan::where([
            ['penggajian_id', '=', $penggajianid],
            ['karyawan_id', '=', $karyawanid],
        ])->first();





        return view('pages.penggajianv2.ambil-gaji-perhari', [
            'items' => $items,
            'penggajian_karyawan' => $penggajian_karyawan,
            'colspanTKBM' =>  $items->max('total'),
            'karyawan' => $karyawan,
            'totalNetto' => $totalNetto,
            'totalUang' => $totalUang,
            'pinjaman_saat_ini' => $pinjaman_saat_ini
        ]);
    }


    public function update_ambil_gaji(Request $request, $penggajianid, $karyawanid)
    {


        try {
            DB::beginTransaction();

            $request->merge([
                'karyawan_id' => $karyawanid,
                'penanggung_jawab_id' => auth()->user()->id
            ]);


            $validated = $request->validate([
                'karyawan_id' => 'required|exists:m_karyawans,id',
                'total_gaji' => 'nullable|integer',
                'pinjaman_saat_ini' => 'nullable|integer',
                'potongan_pinjaman' => 'nullable|integer',
                'sisa_pinjaman' => 'nullable|integer',
                'gaji_yang_diterima' => 'nullable|integer',
                'penanggung_jawab_id' => 'required|exists:users,id',
                'is_gaji_dibayarkan' => 'boolean',
            ]);

            $penggajian_karyawan = Penggajian_karyawan::where([
                ['penggajian_id', '=', $penggajianid],
                ['karyawan_id', '=', $karyawanid],
            ]);

            if ($penggajian_karyawan->exists()) {
                $penggajian_karyawan->update($validated);
                $message = 'Gaji karyawan barhasil di tambahkan!';
            } else {
                $validated['id'] = (string) Str::uuid();
                $validated['penggajian_id'] = $penggajianid;
                $penggajian_karyawan->insert($validated);
                $message = 'Gaji karyawan berhasil di perbarui!';
            }

            $penggajianKaryawanId = $penggajian_karyawan->first();

            if ($validated['potongan_pinjaman'] > 0 && $validated['is_gaji_dibayarkan'] == true) {

                $dataPengembalian = [
                    'tanggal' => Carbon::now(),
                    'karyawan_id' => $karyawanid,
                    'nominal_peminjaman' => 0,
                    'nominal_pengembalian' => $validated['potongan_pinjaman'],
                    'keterangan' => 'potongan gaji karyawan',
                ];

                $pinjaman_uang = Pinjaman_uang::where('penggajian_karyawan_id', $penggajianKaryawanId->id);
                if ($pinjaman_uang->exists()) {
                    $pinjaman_uang->update($dataPengembalian);
                } else {
                    $dataPengembalian['penggajian_karyawan_id'] = $penggajianKaryawanId->id;
                    Pinjaman_uang::create($dataPengembalian);
                }
            } else {
                $pinjaman_uang = Pinjaman_uang::where('penggajian_karyawan_id', $penggajianKaryawanId->id);
                if ($pinjaman_uang->exists()) {
                    $pinjaman_uang->forceDelete();
                }
            }


            DB::commit();
            return redirect()->back()->with('success', $message);;
        } catch (\Throwable $th) {

            DB::rollBack();
            return redirect()->back()->withErrors($th->getMessage());
        }
    }


    public function store(Request $request)
    {

        $validated = $request->validate([
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
        ]);

        $start = Carbon::parse($validated['periode_awal']);
        $end = Carbon::parse($validated['periode_akhir']);
        $days = $start->diffInDays($end);


        if ($days > 32) {
            return redirect()->back()->with('error', 'Rentang tanggal tidak boleh lebih dari 32 hari.');
        }

        // Cek apakah periode sudah ada (overlap)
        $existing = $this->penggajianRepo->isOverlapPeriodePenggajian($start, $end);

        if ($existing) {
            return redirect()->back()->with('error', 'Periode yang dipilih sudah tersedia');
        }

        try {
            DB::beginTransaction();


            $penggajian_id = Penggajian::create($validated)->id;

            $get_penjualan_id = Penjualan::whereBetween('tanggal_penjualan', [$validated['periode_awal'], $validated['periode_akhir']])->select('id')->get();

            $penggajian_penjualan = $get_penjualan_id->map(function ($item) use ($penggajian_id) {
                return [
                    'id' => Str::uuid(),
                    'penggajian_id' => $penggajian_id,
                    'penjualan_id' => $item->id,
                ];
            });

            Penggajian_penjualan::insert($penggajian_penjualan->toArray());
            DB::commit();


            return redirect()->back()->with('success', 'Transaksi berhasil disimpan!');;
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->withErrors($th->getMessage());
        }
    }

    public function update_delete_range_penggajian(Request $request, $id)
    {
        $validated = $request->validate([
            'periode_awal' => 'required|date',
            'periode_akhir' => 'required|date|after_or_equal:periode_awal',
        ]);


        $start = Carbon::parse($validated['periode_awal']);
        $end = Carbon::parse($validated['periode_akhir']);
        $days = $start->diffInDays($end);


        if ($days > 32) {
            return redirect()->back()->with('error', 'Rentang tanggal tidak boleh lebih dari 32 hari.');
        }

        // Cek apakah periode sudah ada (overlap)
        $existing = $this->penggajianRepo->isOverlapPeriodePenggajian($start, $end);
        if ($existing) {
            return redirect()->back()->with('error', 'Periode yang dipilih sudah tersedia');
        }

        try {
            DB::beginTransaction();

            // Langkah 1: Ambil ID penjualans yang sudah pernah dicopy ke penggajian_penjualans (existing)
            $existingPenjualanIds = $this->penggajianRepo->getIdPenggajian_penjualanCopyResult($id);


            // Langkah 2: Ambil ID penjualans berdasarkan periode baru
            $penjualanInRangeIds = $this->penggajianRepo->getPenjualansFromRangePeriode($validated['periode_awal'], $validated['periode_akhir']);


            // Langkah 3: Bandingkan ID — cari penjualan yang tidak lagi ada dalam range baru
            $toDeleteIds = array_values(array_diff($existingPenjualanIds, $penjualanInRangeIds));

            // Langkah 4: Hapus penggajian_penjualans berdasarkan hasil perbandingan
            if (!empty($toDeleteIds)) {
                Penggajian_penjualan::where('penggajian_id', $id)
                    ->whereIn('penjualan_id', $toDeleteIds)
                    ->delete();

                // Langkah 5: cek tkbm apakaha ada penggajian dengan penjualan_id tersbut
                $karyawanIds =  Tkbm::whereIn('penjualan_id', $toDeleteIds)->select(['karyawan_id'])->get()->pluck('karyawan_id');

                $isExists = Penggajian_karyawan::whereIn('karyawan_id', $karyawanIds)->where('penggajian_id', $id)->where('is_gaji_dibayarkan', true)->exists();

                if ($isExists) {
                    throw new \Exception("Beberapa karyawan telah ditandai sebagai sudah menerima gaji pada periode yang Anda masukkan. Silakan batalkan terlebih dahulu status pembayaran tersebut (uncheck) melalui menu Ambil Gaji. ");
                }
            }


            // Langkah 6: Cari penjualan baru yang belum ada di penggajian_penjualans (perlu dicopy)
            $toInsertIds = array_values(array_diff($penjualanInRangeIds, $existingPenjualanIds));

            if (!empty($toInsertIds)) {
                $penjualansToInsert = Penjualan::whereIn('id', $toInsertIds)->get();

                $penggajian_penjualan = $penjualansToInsert->map(function ($item) use ($id) {
                    return [
                        'id' => Str::uuid(),
                        'penggajian_id' => $id,
                        'penjualan_id' => $item->id,
                    ];
                });

                Penggajian_penjualan::insert($penggajian_penjualan->toArray());
            }

            Penggajian::where('id', $id)->update($validated);



            DB::commit();
            return redirect()->back()->with('success', 'periode penggajian di ubah!');;
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $isExists =  Penggajian_karyawan::where('penggajian_id', $id)->where('is_gaji_dibayarkan', true)->exists();

            if ($isExists) {
                throw new \Exception("Beberapa karyawan telah ditandai sebagai sudah menerima gaji pada periode yang Anda masukkan. Silakan batalkan terlebih dahulu status pembayaran tersebut (uncheck) melalui menu Ambil Gaji.");
            }

            Penggajian::where('id', $id)->forceDelete();
            // Penggajian_tkbm::where('penggajian_id', $id)->forceDelete();

            DB::commit();
            return redirect()->back()->with('success', 'Transaksi berhasil dihapus!');;
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
}
