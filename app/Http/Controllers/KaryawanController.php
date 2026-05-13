<?php

namespace App\Http\Controllers;

use App\Models\M_jobs;
use App\Models\M_karyawan;
use App\Models\M_type_karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KaryawanController extends Controller
{

    public function index()
    {

        return view('pages.master_karyawan.index', [
            'items' =>  M_karyawan::with(['jobs.type_karyawan', 'main_type_karyawan:id,type_karyawan'])->whereIn('main_type_karyawan_id', [1, 2])->latest()->get()
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'nama' => 'required|max:50',
            'main_type_karyawan' => 'required',
            'type_karyawan' => 'required|array',
            'type_karyawan.*' => 'in:1,2,3',
        ]);


        try {
            DB::beginTransaction();

            $karyawan = M_karyawan::create([
                'nama' => $validated['nama'],
                'main_type_karyawan_id' => $validated['main_type_karyawan']
            ]);

            if ($validated['main_type_karyawan'] != 3) {
                $arr = [];
                for ($i = 0; $i < count($validated['type_karyawan']); $i++) {
                    $arr[] =  [
                        'karyawan_id' => $karyawan->id,
                        'type_karyawan_id' => $validated['type_karyawan'][$i]
                    ];
                }
                M_jobs::insert($arr);
            }

            DB::commit();

            return redirect()->back()->with('success', 'Data berhasil disimpan!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama' => 'required|max:50',
            'main_type_karyawan' => 'required',
            'type_karyawan' => 'required|array',
            'type_karyawan.*' => 'in:1,2,3',
        ]);

        try {
            DB::beginTransaction();

            $karyawan =  M_karyawan::findOrFail($id);
            $karyawan->update([
                'nama' => $validated['nama'],
                'main_type_karyawan_id' => $validated['main_type_karyawan']
            ]);


            if ($validated['main_type_karyawan'] != 3) {
                M_jobs::where('karyawan_id', $id)->delete();
                $arr = [];
                for ($i = 0; $i < count($validated['type_karyawan']); $i++) {
                    $arr[] =  [
                        'karyawan_id' => $karyawan->id,
                        'type_karyawan_id' => $validated['type_karyawan'][$i]
                    ];
                }

                M_jobs::insert($arr);
            }
            DB::commit();
            return redirect()->back()->with('success', 'Data berhasil disimpan!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->withErrors($e->getMessage());
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $karyawan = M_karyawan::findOrFail($id);

        $karyawan->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
