<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 0.5px solid #999;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: white;
        }


        .panel {

            border-radius: 4px;
            margin-bottom: 20px;
        }

        .panel-heading {
            padding: 10px;
            font-weight: bold;
        }

        .panel-body {
            padding: 10px;
        }
    </style>
</head>

<body>

    <div class="title">SLIP GAJI KARYAWAN</div>


    <div style="width: 50%; font-size: 12px;">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 30%; font-weight: bold;">Nama</td>
                <td>: {{ $karyawan->nama }}</td>
            </tr>
            <tr>
                <td style="font-weight: bold;">Pekerjaan utama</td>
                <td>: {{ $karyawan->main_type_karyawan->type_karyawan }}</td>
            </tr>
        </table>
    </div>

    <table class="table">
        <thead>
            <tr style="text-align: center">
                <th style="text-align: center" rowspan="2">NO</th>
                <th style="text-align: center" rowspan="2">TANGGAL</th>
                <th style="text-align: center" rowspan="2">PKS</th>

                <th colspan="{{ $colspanTKBM + 1 }}"
                    style="text-align: center; border-left: 0; border-right: 0; border-bottom: 0">
                    LIST PEKERJA</th>

                <th style="text-align: center" rowspan="2">TONASE (KG)</th>
                <th style="text-align: center" rowspan="2">PER KG (Rp)</th>
                <th style="text-align: center" rowspan="2">TOTAL
                    <br>
                    TKBM
                </th>
                <th style="text-align: center" rowspan="2">JUMLAH UANG DITERIMA</th>
                <th style="text-align: center" rowspan="2">JENIS <br>
                    PEKERJAAN
                </th>
            </tr>
            <tr>

                <th style="text-align: center">SOPIR</th>
                <th style="text-align: center" colspan="{{ $colspanTKBM }}">
                    TKBM </th>
            </tr>

        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr style="{{ $item['model_kerja_id'] == 2 ? 'background-color: yellow' : '' }}">
                    <td>{{ $loop->iteration }}</td>
                    <td style="{{ $item['is_tkbm_alpha'] ? 'background-color: red; color:white' : '' }}">
                        {{ $item['created_at_formatted'] }} </td>
                    <td>{{ $item['nama_pabrik'] }}</td>

                    <td>{{ $item['sopir'] }}</td>
                    @for ($i = 0; $i < $colspanTKBM; $i++)
                        <td>{{ isset($item['tkbms'][$i]) ? $item['tkbms'][$i] : '-' }}</td>
                    @endfor
                    <td>{{ $item['netto'] }}</td>
                    @if ($item['model_kerja_id'] == 2)
                        <td>BORONGAN</td>
                    @endif


                    @if ($item['model_kerja_id'] == 1)
                        <td>{{ $item['tarif_perkg_rp'] }}</td>
                    @endif


                    @if ($item['model_kerja_id'] == null)
                        <td></td>
                    @endif


                    <td>{{ $item['total'] ?? '' }}</td>
                    @if ($item['is_tkbm_alpha'])
                        <td></td>
                    @else
                        <td>{{ $item['jumlah_uang_rp'] }}</td>
                    @endif

                    <td>{{ $item['keterangan'] ?? '' }}</td>

                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th></th>
                <th></th>
                <th></th>
                <th colspan="{{ $colspanTKBM + 1 }}" style="text-align: center"></th>
                <th>{{ $totalNetto }} Kg</th>
                <th></th>
                <th></th>
                <th>{{ 'Rp ' . number_format($totalUang, 0, ',', '.') }}</th>
                <th></th>
            </tr>
        </tfoot>
    </table>


    <table style="width: 100%; font-size: 12px;">
        <tr>
            {{-- Kolom kiri: Total Gaji --}}
            <td style="width: 55%; vertical-align: top;">
                <strong>Detail : </strong>
                <table class="table">
                    <tr>
                        <td>Total Gaji</td>
                        <td>Rp {{ number_format($totalUang ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Pinjaman Saat Ini</td>
                        <td>{{ number_format($penggajian_karyawan->pinjaman_saat_ini ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Potongan Pinjaman</td>
                        <td>{{ number_format($penggajian_karyawan->potongan_pinjaman ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Sisa Pinjaman</td>
                        <td>{{ number_format($penggajian_karyawan->sisa_pinjaman ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Gaji yang Diterima</strong></td>
                        <td>{{ number_format($penggajian_karyawan->gaji_yang_diterima ?? 0, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Status Pembayaran</td>
                        <td>{{ $penggajian_karyawan->is_gaji_dibayarkan ?? false ? 'Sudah' : 'Belum' }}</td>
                    </tr>
                </table>
            </td>

            {{-- Kolom kanan: Keterangan Warna --}}
            <td style="width: 45%; vertical-align: top; padding-left: 10px;">
                <strong>Keterangan Warna:</strong>
                <br>
                {{-- <table style="font-size: 11px; margin-top: 5px;">
                    <tr>
                        <td style="background-color: yellow; width: 15px; height: 15px;"></td>
                        <td style="padding-left: 8px;">Pekerjaan sistem borongan dengan pembayaran yang diselesaikan
                            pada hari itu juga.</td>
                    </tr>
                    <tr>
                        <td style="background-color: red; width: 15px; height: 15px;"></td>
                        <td style="padding-left: 8px;">Pekerja tidak hadir saat TKBM berlangsung.</td>
                    </tr>
                </table> --}}
                <table style="font-size: 11px; margin-top: 5px;">
                    <tr>
                        <td>
                            <div
                                style="width: 15px; height: 15px; background-color: yellow; display: inline-block; border: 1px solid #000;">
                            </div>
                        </td>
                        <td style="padding-left: 8px;">
                            Pekerjaan sistem borongan dengan pembayaran yang diselesaikan pada hari itu juga.
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div
                                style="width: 15px; height: 15px; background-color: red; display: inline-block; border: 1px solid #000;">
                            </div>
                        </td>
                        <td style="padding-left: 8px;">
                            Pekerja tidak hadir.
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>



</body>

</html>
