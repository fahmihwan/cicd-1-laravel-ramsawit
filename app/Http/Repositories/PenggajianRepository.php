<?php

namespace App\Http\Repositories;

use App\Models\Penggajian;
use App\Models\Penggajian_karyawan;
use App\Models\Penggajian_penjualan;
use App\Models\Penjualan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PenggajianRepository
{
    // private $penggajianRepo;
    // public function __construct($penggajianRepo)
    // {
    //     $this->penggajianRepo = $penggajianRepo;
    // }

    public function detail_gaji($penggajianid, $karyawanid)
    {
        $items = DB::select("SELECT * from (
               SELECT
               		DISTINCT ON (p.id)
                    p.id,
                    t.penjualan_id,t_a.karyawan_id,mk.nama,t.type_karyawan_id,
                    CASE 
                        WHEN t_a.type_karyawan_id = 1 and t_a.pekerjaan_lain_id is null THEN 'SOPIR'
                        WHEN t_a.type_karyawan_id = 2 and t_a.pekerjaan_lain_id is null THEN 'TKBM'
                        WHEN t_a.type_karyawan_id = 1 and t_a.pekerjaan_lain_id is not null THEN 'SOPIR (' || mpns.jenis_pekerjaan || ')'
                        WHEN t_a.type_karyawan_id = 2 and t_a.pekerjaan_lain_id is not null THEN 'TKBM (' || mpns.jenis_pekerjaan || ')'
                    END AS keterangan,
                    mk.main_type_karyawan_id,
                    t.jumlah_tkbm AS total,p.tanggal_penjualan,p.netto,t_a.model_kerja_id,mp.nama_pabrik, mt.tarif_perkg,
                    t.tkbm_agg as tkbms,mk_sopir.nama as sopir,t_a.jumlah_uang,
                    case 
                        when t_a.karyawan_id is null then true
                        when t_a.karyawan_id is not null then false
                    end AS is_tkbm_alpha
                FROM penggajians ps
                    INNER JOIN penggajian_penjualans pp ON ps.id = pp.penggajian_id 
                    INNER JOIN penjualans p ON p.id = pp.penjualan_id 
                    LEFT JOIN m_pekerjaan_non_sawits mpns on mpns.id = p.pekerjaan_lain_id
                    LEFT JOIN m_pabriks mp ON p.pabrik_id = mp.id 
                    LEFT JOIN tkbms t ON t.penjualan_id = p.id 
                    LEFT JOIN tkbms t_a ON t_a.penjualan_id = p.id AND t_a.karyawan_id = :karyawanid 
                    LEFT JOIN m_karyawans mk ON mk.id = t_a.karyawan_id
                    left JOIN m_tarifs mt ON mt.id = t_a.tarif_id 
                    LEFT JOIN m_karyawans mk_sopir ON mk_sopir.id = p.sopir_id
                    where ps.id = :penggajianid and (p.model_kerja_id = 1 or t_a.karyawan_id = :karyawanid)
               ) as x
               order by x.tanggal_penjualan desc", [
            'karyawanid' => $karyawanid,
            'penggajianid' => $penggajianid
        ]);
        return $items;
    }

    public function getIdPenggajian_penjualanCopyResult($id)
    {
        // Langkah 1: Ambil ID penjualans yang sudah pernah dicopy ke penggajian_penjualans (existing)
        $existingPenjualanIds = Penggajian_penjualan::join('penjualans', 'penggajian_penjualans.penjualan_id', '=', 'penjualans.id')
            ->where('penggajian_penjualans.penggajian_id', $id)
            ->select('penjualans.id')
            ->pluck('penjualans.id')
            ->toArray();

        return $existingPenjualanIds;
    }


    public function getPenjualansFromRangePeriode($periode_awal, $periode_berakhir)
    {
        // Langkah 2: Ambil ID penjualans berdasarkan periode baru
        $penjualanInRangeIds = Penjualan::whereBetween('tanggal_penjualan', [
            $periode_awal,
            $periode_berakhir
        ])->select('id')->pluck('id')->toArray();

        return $penjualanInRangeIds;
    }

    public function isOverlapPeriodePenggajian($start, $end)
    {
        // Cek apakah periode sudah ada (overlap)
        $existing = Penggajian::where(function ($query) use ($start, $end) {
            $query->whereBetween('periode_awal', [$start, $end])
                ->orWhereBetween('periode_akhir', [$start, $end])
                ->orWhere(function ($query) use ($start, $end) {
                    $query->where('periode_awal', '<=', $start)
                        ->where('periode_akhir', '>=', $end);
                });
        })->exists();

        return $existing;
    }

    public function getFirstPenggajianFromRangeDate($tanggal)
    {
        $items = Penggajian::query()
            ->whereDate('periode_awal', '<=', $tanggal)
            ->whereDate('periode_akhir', '>=', $tanggal)
            ->first();
        return $items;
    }


    public function syncPenggajian($validated, $menu)
    {

        function remove_validation_is_karyawan_same($old_data_karyawawn_id, $new_data_karyawan_id)
        {
            $only_in_new = array_diff($new_data_karyawan_id, $old_data_karyawawn_id);
            $only_in_old = array_diff($old_data_karyawawn_id, $new_data_karyawan_id);
            $arrKaryawanId = array_values(array_merge($only_in_new, $only_in_old));
            return $arrKaryawanId;
        }

        function merge_array_karyawan_for_validation($validated, $arrKaryawanId)
        {
            if (!empty($validated['tkbm_id'])) { //array
                $arrKaryawanId = array_merge($arrKaryawanId, $validated['tkbm_id']);
            }
            if (!empty($validated['sopir_id'])) { //not array
                $arrKaryawanId[] = $validated['sopir_id'];
            }

            if (!empty($validated['data_tkbm_dinamis_borongan_json'])) { //array
                $ids = array_column($validated['data_tkbm_dinamis_borongan_json'], 'karyawan_id');
                $arrKaryawanId = array_merge($arrKaryawanId, $ids);
            }
            return $arrKaryawanId;
        }

        //SYNC PENGGAJIAN cek penggajian berdasarkan range tanggal penggajian, jika
        $isExistsPenggajian = $this->getFirstPenggajianFromRangeDate($validated['tanggal_penjualan']);

        $arrKaryawanId = [];
        if (!empty($isExistsPenggajian)) {
            if ($menu == "CREATE") {
                $arrKaryawanId = merge_array_karyawan_for_validation($validated, $arrKaryawanId);
            } else if ($menu == "UPDATE") {
                $old_data_karyawawn_id = $validated['arr_karyawanid_olddata_edit'];
                if ($validated['model_kerja_id'] == 1) {
                    $arrKaryawanId = merge_array_karyawan_for_validation($validated, $arrKaryawanId);
                    $arrKaryawanId = remove_validation_is_karyawan_same($old_data_karyawawn_id, $arrKaryawanId);
                } else if ($validated['model_kerja_id'] == 2) {
                    $new_data_karyawan_id = array_column($validated['data_tkbm_dinamis_borongan_json'], 'karyawan_id');
                    $arrKaryawanId = remove_validation_is_karyawan_same($old_data_karyawawn_id, $new_data_karyawan_id);
                }
            } else if ($menu == "DELETE") {
                $arrKaryawanId = $validated['arr_karyawanid_olddata_delete'];
            }


            $penggajianId = $isExistsPenggajian->id;

            $isExistsPenggajianDibayarkan = Penggajian_karyawan::with(['karyawan:id,nama'])
                ->where('penggajian_id', $penggajianId)
                ->whereIn('karyawan_id', $arrKaryawanId)->where('is_gaji_dibayarkan', true)->get();

            if (count($isExistsPenggajianDibayarkan)) {
                $listStringKaryawan = $isExistsPenggajianDibayarkan->pluck('karyawan.nama')->implode(', ');

                $periode_awal = Carbon::parse($isExistsPenggajian->periode_awal)->translatedFormat('l, d F Y');
                $periode_akhir = Carbon::parse($isExistsPenggajian->periode_akhir)->translatedFormat('l, d F Y');

                return [
                    'status' => true,
                    'message' => "karyawan $listStringKaryawan telah menerima gaji pada periode penggajian $periode_awal - $periode_akhir, lakukan uncheck terlebih dahulu",
                    'penggajianId' => $penggajianId
                ];
            }

            return [
                'status' => false,
                'message' => '',
                'penggajianId' => $penggajianId
            ];
        } else {
            return [
                'status' => false,
                'message' => '',
                'penggajianId' => null
            ];
        }
    }
}
