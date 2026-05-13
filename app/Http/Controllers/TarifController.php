<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Models\M_tarif;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarifController extends Controller
{

    public function index()
    {

        // $rows = DB::select("SELECT mt.id, mt.tarif_perkg, mt.type_karyawan, mt.created_at, is_active_tarif
        //                     FROM m_tarifs mt
        //                     left JOIN (
        //                         SELECT mt2.type_karyawan, MAX(mt2.id) AS max_id, true as is_active_tarif
        //                         FROM m_tarifs mt2 where mt2.deleted_at is null 
        //                         GROUP BY type_karyawan
        //                     ) sub ON mt.type_karyawan = sub.type_karyawan AND mt.id = sub.max_id
        //                     WHERE mt.deleted_at IS NULL
        //                     order by mt.created_at desc");

        // $rows = DB::select("SELECT mt.id, mt.tarif_perkg, mtk.type_karyawan, mt.type_karyawan_id, x.is_active_tarif, mt.created_at  
		// 					from m_tarifs mt 
		// 					left join (
	    //                         SELECT mt2.type_karyawan_id, mtk2.type_karyawan, MAX(mt2.id) AS max_id, true as is_active_tarif
	    //                             FROM m_tarifs mt2 
	    //                             inner join m_type_karyawans mtk2 ON mtk2.id = mt2.type_karyawan_id
	    //                         where mt2.deleted_at is null 
	    //                         GROUP BY mtk2.type_karyawan, mt2.type_karyawan_id
		// 					) as x on mt.id  = x.max_id
		// 					inner join m_type_karyawans mtk ON mtk.id = mt.type_karyawan_id
 		// 					where mt.deleted_at is null
 		// 					order by mt.created_at desc");

        $data = M_tarif::with('type_karyawan:id,type_karyawan')->get();

        return view('pages.master_tarif.index', [
            'items' =>  $data
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'tarif_perkg' => 'required|integer',
            'type_karyawan_id' => 'required|integer',
        ]);

        M_tarif::create($validated);
        return redirect('/master/tarif');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'tarif_perkg' => 'required|integer',
            'type_karyawan_id' => 'required|integer',
        ]);

        $karyawan =  M_tarif::findOrFail($id);
        $karyawan->update($validated);
        return redirect('/master/tarif');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $karyawan = M_tarif::findOrFail($id);

        $karyawan->delete();
        return redirect('/master/tarif')->with('success', 'Tarif berhasil dihapus!');
    }
}
