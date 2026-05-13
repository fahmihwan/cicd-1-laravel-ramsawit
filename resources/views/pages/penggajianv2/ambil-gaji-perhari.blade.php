@extends('layouts.main')

@section('style')
    <style>
        /* Sembunyikan tanggal */
        .ui-datepicker-calendar {
            display: none;
        }

        /* Tampilkan tombol OK saja */
        .ui-datepicker-close {
            display: inline-block;
        }
    </style>
@endsection

@section('container')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Detail Slip Gaji</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <!-- Tampilkan error validasi -->
            @if ($errors->any())
                <div style="color:red;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
        <div class="row">

            <div class="col-lg-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Detail Karyawan
                    </div>
                    <div class="panel-body">
                        <table>
                            <tr>
                                <td>Nama</td>
                                <td style="padding-left: 30px"> : {{ $karyawan->nama }}</td>
                            </tr>
                            <tr>
                                <td>Posisi</td>
                                <td style="padding-left: 30px"> : {{ $karyawan->main_type_karyawan->type_karyawan }}</td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs" style="margin-bottom: 12px">
                    <li role="presentation" class="{{ request()->is('penggajian/*/*/detail-gaji') ? 'active' : '' }}"><a
                            href="/penggajian/{{ request()->route('penggajianid') }}/{{ request()->route('karyawanid') }}/detail-gaji">Detail
                            Slip
                            Gaji Karyawan</a>
                    </li>
                    <li role="presentation"
                        class="{{ request()->is('penggajian/*/*/ambil-gaji-perhari') ? 'active' : '' }}"><a
                            href="/penggajian/{{ request()->route('penggajianid') }}/{{ request()->route('karyawanid') }}/ambil-gaji-perhari">Ambil
                            Gaji</a></li>
                </ul>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="display: flex; justify-content: space-between">
                        <div>
                            Laporan tonase karyawan
                        </div>


                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr style="text-align: center">
                                        <th style="text-align: center" rowspan="2">NO</th>
                                        <th style="text-align: center" rowspan="2">TANGGAL</th>
                                        <th style="text-align: center" rowspan="2">PKS</th>

                                        <th colspan="{{ $colspanTKBM + 1 }}"
                                            style="text-align: center; border-left: 0; border-right: 0; border-bottom: 0">
                                            LIST PEKERJA</th>

                                        <th style="text-align: center" rowspan="2">TONASE</th>
                                        <th style="text-align: center" rowspan="2">PER KG</th>
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
                                            <td>
                                                {{ $item['created_at_formatted'] }} </td>
                                            <td>{{ $item['nama_pabrik'] }}</td>
                                            <td>{{ $item['sopir'] }}</td>
                                            @for ($i = 0; $i < $colspanTKBM; $i++)
                                                <td>{{ isset($item['tkbms'][$i]) ? $item['tkbms'][$i] : '-' }}</td>
                                            @endfor
                                            <td>{{ $item['netto'] }} kg</td>
                                            <td>{{ $item['tarif_perkg_rp'] }}</td>
                                            <td>{{ $item['total'] ?? '' }}</td>
                                            <td>{{ $item['jumlah_uang_rp'] }}</td>
                                            <td>{{ $item['keterangan'] }}</td>
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

                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>


        <div class="row">

            <div class="col-lg-4">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Keteraangan
                    </div>
                    <div class="panel-body">
                        <form
                            action="/penggajian/{{ request()->route('penggajianid') }}/{{ request()->route('karyawanid') }}/ambil-gaji-perhari"
                            method="POST">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Total Gaji</label>
                                        <input class="form-control" type="number" name="total_gaji" id="total_gaji"
                                            value="{{ $totalUang ?? 0 }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pinjaman saat ini</label>
                                        <input class="form-control" type="number" name="pinjaman_saat_ini"
                                            id="pinjaman_saat_ini" value="{{ $pinjaman_saat_ini ?? 0 }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Potongan Pinjaman</label>
                                        <input class="form-control" type="number" name="potongan_pinjaman"
                                            value="{{ $penggajian_karyawan->potongan_pinjaman ?? 0 }}"
                                            id="potongan_pinjaman">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sisa Pinjaman</label>
                                        <input class="form-control" type="number" name="sisa_pinjaman"
                                            value="{{ $penggajian_karyawan->sisa_pinjaman ?? 0 }}" id="sisa_pinjaman"
                                            readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Gaji yang diterima</label>
                                <input class="form-control" type="number" name="gaji_yang_diterima"
                                    value="{{ $penggajian_karyawan->gaji_yang_diterima ?? 0 }}" id="gaji_yang_diterima"
                                    readonly>
                            </div>

                            <input type="hidden" name="is_gaji_dibayarkan" value="0">

                            <label style="display:flex;align-items: center; margin-bottom: 20px">
                                <input type="checkbox" name="is_gaji_dibayarkan" value="1"
                                    style="width: 25px; height: 25px; margin-right: 10px"
                                    {{ old('is_gaji_dibayarkan', $penggajian_karyawan->is_gaji_dibayarkan ?? false) ? 'checked' : '' }}>
                                <span style="color: red">Konfirmasi bahwa gaji telah dibayarkan</span>
                            </label>

                            <button class="btn  btn-primary" type="submit">
                                Bayar Gaji Karyawan
                            </button>

                        </form>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        Keterangan
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li>Lakukan checklist "<i class="fa fa-check"></i>" pada bagian <b
                                    style="color: red">Konfirmasi bahwa gaji telah dibayarkan</b> sebagai penanda bahwa
                                gaji telah diserahkan kepada karyawan.</li>

                            <li>Apabila field <b>Potongan Pinjaman</b> terisi dan Anda melakukan checklist "<i
                                    class="fa fa-check"></i>" pada <b style="color: red">Konfirmasi bahwa gaji telah
                                    dibayarkan</b>, maka potongan pinjaman tersebut akan otomatis tercatat pada menu
                                pinjaman, dengan keterangan 'potongan gaji karyawan'.</li>

                            <li>Jika Anda menghapus checklist pada <b style="color: red">Konfirmasi bahwa gaji telah
                                    dibayarkan</b>, maka apabila terdapat data <b>Potongan Pinjaman</b> yang sudah tercatat
                                pada menu pinjaman, data tersebut akan otomatis terhapus.</li>
                        </ul>
                    </div>
                    <div class="panel-footer">
                        Readme
                    </div>
                </div>
                <!-- /.col-lg-4 -->
            </div>

        </div>

    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            function hitung() {
                let totalGaji = parseFloat($('#total_gaji').val()) || 0;
                let pinjamanSaatIni = parseFloat($('#pinjaman_saat_ini').val()) || 0;
                let potonganPinjaman = parseFloat($('#potongan_pinjaman').val()) || 0;

                let sisaPinjaman = pinjamanSaatIni - potonganPinjaman;
                let gajiYangDiterima = totalGaji - potonganPinjaman;

                $('#sisa_pinjaman').val(sisaPinjaman >= 0 ? sisaPinjaman : 0);
                $('#gaji_yang_diterima').val(gajiYangDiterima >= 0 ? gajiYangDiterima : 0);
            }

            $('#total_gaji, #pinjaman_saat_ini, #potongan_pinjaman').on('input', function() {
                hitung();
            });

            // Optional: Jalankan saat load pertama kali (misalnya untuk edit data)
            hitung();
        });
    </script>
    </script>
@endsection
