<?php

namespace Database\Seeders;

use App\Models\M_delivery_order_type;
use App\Models\M_jobs;
use App\Models\M_karyawan;
use App\Models\M_modelkerja;
use App\Models\M_pabrik;
use App\Models\M_tarif;
use App\Models\M_type_karyawan;
use App\Models\M_type_tbs;
use App\Models\Pembelian_tbs;
use App\Models\Penjualan;
use App\Models\Periode;
use App\Models\Tkbm;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::create([
            'nama' => 'Alifian cahyo',
            'username' => 'alif46',
            'password' => bcrypt('qweqwe123')
        ]);

        M_modelkerja::create([
            'model_kerja' => "TONASE"
        ]);

        M_modelkerja::create([
            'model_kerja' => "BORONGAN"
        ]);

        M_pabrik::create([
            'nama_pabrik' => 'AGRO'
        ]);
        M_pabrik::create([
            'nama_pabrik' => 'PAJ'
        ]);

        M_type_tbs::create(['type_tbs' => 'TBS RUMAH']);
        M_type_tbs::create(['type_tbs' => 'TBS LAHAN']);
        M_type_tbs::create(['type_tbs' => 'TBS RAM']);


        M_delivery_order_type::create(['delivery_order_type' => 'PLASMA']);
        M_delivery_order_type::create(['delivery_order_type' => 'LU']);
        M_delivery_order_type::create(['delivery_order_type' => 'Lainnya']);

        M_type_karyawan::create([
            'type_karyawan' => "SOPIR"
        ]);

        M_type_karyawan::create([
            'type_karyawan' => "TKBM"
        ]);

        M_type_karyawan::create([
            'type_karyawan' => "LAINNYA"
        ]);

        M_type_karyawan::create([
            'type_karyawan' => "BORONGAN"
        ]);



        // M_karyawan::create([
        //     'nama' => 'Ragil',
        //     'main_type_karyawan_id' => 1
        // ]);
        // M_karyawan::create([
        //     'nama' => 'Dimas',
        //     'main_type_karyawan_id' => 1
        // ]);
        // M_karyawan::create([
        //     'nama' => 'FAHMI',
        //     'main_type_karyawan_id' => 2
        // ]);

        // M_karyawan::factory(5)->create();



        // for ($i = 1; $i <= 8; $i++) {

        //     $karyawan_id = $i;
        //     $type_karyawan_id = 2;
        //     if ($karyawan_id < 3) {
        //         $type_karyawan_id = 1;
        //     }

        //     M_jobs::create([
        //         'karyawan_id'  => $karyawan_id,
        //         'type_karyawan_id' => $type_karyawan_id
        //     ]);
        // }

        // M_jobs::create([
        //     'karyawan_id'  => 1,
        //     'type_karyawan_id' => 2
        // ]);



        // for ($j = 7; $j >= 1; $j--) {
        //     $mkId = M_karyawan::create([
        //         'nama' => 'BORONGAN ' . $j,
        //         'main_type_karyawan_id' => 4
        //     ])->id;
        //     M_jobs::create([
        //         'karyawan_id'  => $mkId,
        //         'type_karyawan_id' => 4
        //     ]);
        // }


        // M_tarif::create(['tarif_perkg' => 20, 'type_karyawan_id' => 1]);
        // M_tarif::create(['tarif_perkg' => 30, 'type_karyawan_id' => 2]);





        // $periode = Periode::create([
        //     'id' => Str::uuid(),
        //     'periode' =>  1,
        //     'periode_mulai' => '2025-08-12',
        //     'periode_berakhir' => null,
        // ])->id;


        // $periode = null;
        // for ($i = 0; $i < 1; $i++) {
        //     $periode = Periode::create([
        //         'id' => Str::uuid(),
        //         'periode' => $i + 1,
        //         'periode_mulai' => '2025-06-01',
        //         'periode_berakhir' => '2025-06-04',
        //         'stok' => 0,
        //         'ops_id' => 2
        //     ])->id;
        // }



        // Pembelian_tbs::create([
        //     'id' => Str::uuid(),
        //     'nama_customer' => 'Pak Budi',
        //     'periode_id' => $periode,
        //     'tanggal_pembelian' => now()->toDateString(),
        //     'tbs_type_id' => 1,
        //     'netto' => 3000,
        //     'harga' => 2900,
        //     'uang' =>  8700000,
        //     'timbangan_first' => 0,
        //     'timbangan_second' => 0,
        //     'bruto' => 0,
        //     'sortasi' => 0,
        // ]);

        // Pembelian_tbs::create([
        //     'id' => Str::uuid(),
        //     'nama_customer' => 'Pak Angga',
        //     'periode_id' => $periode,
        //     'tanggal_pembelian' => now()->toDateString(),
        //     'tbs_type_id' => 2,
        //     'netto' => 3000,
        //     'harga' => 2900,
        //     'uang' =>  8700000,
        //     'timbangan_first' => 0,
        //     'timbangan_second' => 0,
        //     'bruto' => 0,
        //     'sortasi' => 0,
        // ]);


        // Pembelian_tbs::create([
        //     'id' => Str::uuid(),
        //     'nama_customer' => 'Pak Jo',
        //     'periode_id' => $periode,
        //     'tanggal_pembelian' => now()->toDateString(),
        //     'tbs_type_id' => 3,
        //     'netto' => 2895,
        //     'harga' => 3000,
        //     'uang' =>  8685000,
        //     'timbangan_first' => 6000,
        //     'timbangan_second' => 3000,
        //     'bruto' => 3000,
        //     'sortasi' => 3.5,
        // ]);



        // Pembelian_tbs::create([
        //     'id' => Str::uuid(),
        //     'periode_id' => $periode,
        //     'tanggal_pembelian' => now()->toDateString(),
        //     'nama_customer' => 'Pak angga',
        //     'tbs_type_id' => 3, // pastikan ID ini ada di tabel m_type_tbs
        //     'netto' => 2000,
        //     'harga' => 3050,
        //     'uang' =>  6098414,
        //     'timbangan_first' => 3072,
        //     'timbangan_second' => 1000,
        //     'bruto' => 2072,
        //     'sortasi' => 3.50,
        // ]);



        // $penjualanUUid = Str::uuid();
        // Penjualan::create([
        //     'id' => $penjualanUUid,
        //     'periode_id' => $periode,
        //     'pabrik_id' => 1,          // pastikan data pabrik dengan ID ini ada
        //     'sopir_id' => 2,           // ID sopir dari m_karyawans
        //     'do_type_id' => 1,         // dari m_delivery_order_types
        //     'tarif_tkbm_id' => 2,      // dari m_tarifs
        //     'tarif_sopir_id' => 1,     // dari m_tarifs
        //     'tanggal_penjualan' => now()->addDay()->toDateString(),
        //     'timbangan_first' => 3590,
        //     'timbangan_second' => 1000,
        //     'bruto' => 2590,
        //     'sortasi' => 3.50,
        //     'netto' => 2500,
        //     'harga' => 3000,
        //     'uang' => 7498050,
        // ]);


        // for ($i = 0; $i < 3; $i++) {
        //     Tkbm::create([
        //         'id' => Str::uuid(),
        //         'karyawan_id' => rand(3, 5),       // 
        //         'penjualan_id' => $penjualanUUid, // pastikan UUID ini valid dari tabel penjualans
        //     ]);
        // }



        // // Pembelian_tbs::factory(100)->create();

        // $penjualans = Penjualan::factory(50)->create();

        // $total = 0; // jumlah data yang akan dibuat
        // $penjualans = []; // Array untuk menyimpan entri Penjualan
        // // Membuat entri Penjualan
        // for ($i = 0; $i < $total; $i++) {
        //     $penjualan = Penjualan::factory()
        //         ->createdWithinLastWeek($i, $total)
        //         ->create([
        //             'periode_id' => $periode
        //         ]);
        //     $penjualans[] = $penjualan;
        // }

        // $allKaryawanIds = M_karyawan::where('main_type_karyawan_id', 2)->pluck('id')->toArray();
        // foreach ($penjualans as $penjualan) {
        //     $jumlahTkbm = rand(2, 5);
        //     // Ambil karyawan_id random unik sebanyak $jumlahTkbm
        //     // Jika jumlah karyawan kurang dari jumlah Tkbm, batasi jumlahnya
        //     $karyawanIdsForPenjualan = collect($allKaryawanIds)
        //         ->shuffle()
        //         ->take(min($jumlahTkbm, count($allKaryawanIds)))
        //         ->toArray();
        //     foreach ($karyawanIdsForPenjualan as $karyawanId) {
        //         Tkbm::factory()->create([
        //             'penjualan_id' => $penjualan->id,
        //             'karyawan_id' => $karyawanId,
        //             'model_kerja_id' => 1,
        //             'type_karyawan_id' => 2,
        //             'tarif_id' => 2,
        //         ]);
        //     }
        // }
    }
}
