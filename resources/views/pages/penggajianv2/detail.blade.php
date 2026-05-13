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
                <h1 class="page-header">Detail Slip Gaji </h1>
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
                    <li role="presentation" class="active"><a
                            href="/penggajian/{{ request()->route('penggajianid') }}/{{ request()->route('karyawanid') }}/detail-gaji">Detail
                            Slip
                            Gaji Karyawan</a>
                    </li>
                    <li role="presentation"><a
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
                            Laporan Penggajian karyawan
                        </div>

                        <a href="{{ url('/export-gaji-karyawan/' . request()->route('penggajianid') . '/' . request()->route('karyawanid') . '/detail-gaji') }}"
                            target="_blank">
                            <i class="fa fa-download fa-lg" aria-hidden="true"></i>&nbsp;Download</a>
                        </a>

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
                                            <td
                                                style="{{ $item['is_tkbm_alpha'] ? 'background-color: red; color:white' : '' }}">
                                                {{ $item['created_at_formatted'] }} </td>
                                            <td>{{ $item['nama_pabrik'] }}</td>

                                            <td>{{ $item['sopir'] }}</td>
                                            @for ($i = 0; $i < $colspanTKBM; $i++)
                                                <td>{{ isset($item['tkbms'][$i]) ? $item['tkbms'][$i] : '-' }}</td>
                                            @endfor
                                            <td>{{ $item['netto'] }} kg</td>
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
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>


    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {


            // $("#datepicker").datepicker({

            //     changeMonth: true,
            //     changeYear: true,
            //     showButtonPanel: true,
            //     dateFormat: 'yy-mm',
            //     onClose: function(dateText, inst) {
            //         var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
            //         var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
            //         // Format bulan (tambah 1 dan tambahkan nol depan jika perlu)
            //         month = ('0' + (parseInt(month) + 1)).slice(-2);
            //         $(this).val(year + '-' + month);
            //     },
            //     beforeShow: function(input, inst) {
            //         $(input).datepicker("widget").addClass('hide-calendar');
            //     }
            // });
        });
    </script>
    </script>
@endsection
