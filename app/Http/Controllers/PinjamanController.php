<?php

namespace App\Http\Controllers;

use App\Helpers\Utils;
use App\Models\M_karyawan;
use App\Models\Pinjaman_uang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PinjamanController extends Controller
{

    public function index(Request $request)
    {
        $tanggal = $request->input('tanggal');
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = Pinjaman_uang::with(['karyawan.main_type_karyawan:id,type_karyawan']);
        // return $query->get();


        if ($request->filled('tanggal')) {
            $query->whereDate('periode_mulai', $tanggal);
        }
        if ($search) {
            $query->where(function ($q) use ($search) {
                // $q->where('periode', 'ILIKE', "%$search%");
                // ->orWhere('harga', 'ILIKE', "%$search%")
                // ->orWhere('uang', 'ILIKE', "%$search%");
            });
        }
        $query->orderBy('created_at', 'desc');

        $data = $query->paginate($perPage)->appends($request->query());

        $list_sisa_pinjaman = collect(DB::select("SELECT
                            pu.karyawan_id,
                            mk.nama,
                            mtk.type_karyawan,
                            mk.main_type_karyawan_id,
                            (SUM(pu.nominal_peminjaman) - SUM(pu.nominal_pengembalian)) AS sisa_pinjaman
                        FROM pinjaman_uangs pu
                        INNER JOIN m_karyawans mk ON mk.id = pu.karyawan_id
                        INNER JOIN m_type_karyawans mtk ON mtk.id = mk.main_type_karyawan_id
                        WHERE pu.deleted_at IS NULL
                        GROUP BY pu.karyawan_id, mk.nama, mtk.type_karyawan,mk.main_type_karyawan_id
                        "))->map(function ($item) {
            $item->sisa_pinjaman_formatted = 'Rp ' . number_format($item->sisa_pinjaman, 0, ',', '.');
            return $item;
        });



        // return $list_sisa_pinjaman;


        return view('pages.pinjaman.index', [
            'items' =>  $data,
            'karyawans' => M_karyawan::all(),
            'list_sisa_pinjaman' => $list_sisa_pinjaman,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if (!empty($request->selain_karyawan_id)) {
            $request->merge([
                'karyawan_id' => $request->selain_karyawan_id
            ]);
        }

        $request->merge([
            'nominal_peminjaman' => $request->nominal_peminjaman ?? 0,
            'nominal_pengembalian' => $request->nominal_pengembalian ?? 0,
        ]);


        $validated = $request->validate([
            'tanggal' => 'required|date',
            'karyawan_id' => 'required|integer',
            'nominal_peminjaman' => 'nullable|integer',
            'nominal_pengembalian' => 'nullable|integer',
            'keterangan' => 'nullable',
            'pihak' => 'required',
            'transaksi' => 'required'
        ]);

        Pinjaman_uang::create($validated);
        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        if (!empty($request->selain_karyawan_id)) {
            $request->merge([
                'karyawan_id' => $request->selain_karyawan_id
            ]);
        }

        $validated = $request->validate([
            'tanggal' => 'required|date',
            'karyawan_id' => 'required|integer',
            'nominal_peminjaman' => 'nullable|integer',
            'nominal_pengembalian' => 'nullable|integer',
            'keterangan' => 'nullable',
            // 'pihak' => 'required',
            // 'transaksi' => 'required'
        ]);

        $karyawan =  Pinjaman_uang::findOrFail($id);
        $karyawan->update($validated);
        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $karyawan = Pinjaman_uang::findOrFail($id);

        $karyawan->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    }
}
