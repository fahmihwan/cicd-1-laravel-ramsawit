<?php

namespace App\Http\Controllers;

use App\Models\M_pabrik;
use Illuminate\Http\Request;

class PabrikController extends Controller
{

    public function index()
    {
        return view('pages.master_pabrik.index', [
            'items' =>  M_pabrik::latest()->get()
        ]);
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'nama_pabrik' => 'required|max:50',
        ]);

        M_pabrik::create($validated);
        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_pabrik' => 'required|max:50',
        ]);

        $karyawan =  M_pabrik::findOrFail($id);
        $karyawan->update($validated);
        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $karyawan = M_pabrik::findOrFail($id);

        $karyawan->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
