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
                    <li role="presentation"><a href="#">Messages</a></li>
                </ul>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="display: flex; justify-content: space-between">
                        <div>
                            DataTables Advanced Tables
                        </div>

                        {{--  --}}
                        @php
                            $selectedBulan = request('bulan') ?? now()->format('m');
                            $selectedTahun = request('tahun') ?? now()->format('Y');
                        @endphp

                        <form method="GET" action="/slipgaji/karyawan/{{ $karyawan->id }}" style="display: flex">
                            <div class="" style="margin-right: 10px">
                                <select name="bulan" class="form-control">
                                    <option value="">Pilih Bulan</option>
                                    @for ($i = 1; $i <= 12; $i++)
                                        @php $val = str_pad($i, 2, '0', STR_PAD_LEFT); @endphp
                                        <option value="{{ $val }}" {{ $selectedBulan == $val ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="">
                                <select name="tahun" class="form-control">
                                    <option value="">Pilih Tahun</option>
                                    @for ($y = now()->year; $y >= 2024; $y--)
                                        <option value="{{ $y }}" {{ $selectedTahun == $y ? 'selected' : '' }}>
                                            {{ $y }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div class="form-filter-datatables">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fa fa-search"></i> Search
                                </button>
                            </div>

                            <div class="form-filter-datatables">
                                <a href="/slipgaji/karyawan/{{ $karyawan->id }}?bulan={{ now()->format('m') }}&tahun={{ now()->format('Y') }}"
                                    class="btn btn-info btn-sm">
                                    <i class="fa fa-refresh"></i> clear
                                </a>
                            </div>


                        </form>


                        {{--  --}}
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr style="text-align: center">
                                        <th style="text-align: center" rowspan="2">No</th>
                                        <th style="text-align: center" rowspan="2">Tanggal</th>
                                        <th style="text-align: center" rowspan="2">Tonase</th>
                                        <th style="text-align: center" rowspan="2">Per Kg</th>
                                        @if ($karyawan->main_type_karyawan_id == 2)
                                            <th style="text-align: center" colspan="{{ $colspanTKBM }}" rowspan="2">
                                                TKBM </th>
                                            <th style="text-align: center" rowspan="2">Total</th>
                                        @endif

                                        @if ($karyawan->main_type_karyawan_id == 1)
                                            <th style="text-align: center" colspan={{ count($pabriks) }}>PKS</th>
                                            @if ($colspanTKBM > 0)
                                                <th style="text-align: center" rowspan="2">TKBM</th>
                                            @endif
                                        @endif


                                        <th style="text-align: center" rowspan="2">Jumlah Uang</th>
                                    </tr>
                                    <tr>
                                        @if ($karyawan->main_type_karyawan_id == 1)
                                            @foreach ($pabriks as $p)
                                                <th style="text-align: center">{{ $p->nama_pabrik }}</th>
                                            @endforeach
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td
                                                style="{{ $item['is_tkbm_alpha'] ? 'background-color: red; color:white' : '' }}">
                                                {{ $item['created_at_formatted'] }}</td>
                                            <td>{{ $item['netto'] }} kg</td>
                                            <td>{{ $item['tarif_perkg_rp'] }}</td>
                                            @if ($karyawan->main_type_karyawan_id == 2)
                                                @for ($i = 0; $i < $colspanTKBM; $i++)
                                                    <td>{{ isset($item['tkbms'][$i]) ? $item['tkbms'][$i] : '-' }}</td>
                                                @endfor
                                                <td>{{ $item['total'] }}</td>
                                            @endif
                                            @if ($karyawan->main_type_karyawan_id == 1)
                                                @foreach ($pabriks as $p)
                                                    @if ($item['nama_pabrik'] == $p['nama_pabrik'])
                                                        <td style="text-align: center">
                                                            <i class="fa fa-fw" aria-hidden="true" style="font-size: 25px"
                                                                title="Copy to use check-square">&#xf14a</i>
                                                        </td>
                                                    @else
                                                        <td style="text-align: center">
                                                            <i class="fa fa-fw" aria-hidden="true" style="font-size: 25px"
                                                                title="Copy to use square-o">&#xf096</i>
                                                        </td>
                                                    @endif
                                                @endforeach
                                            @endif


                                            @if ($karyawan->main_type_karyawan_id == 1)
                                                @if ($colspanTKBM > 0)
                                                    <td>
                                                        @for ($i = 0; $i < count($item['tkbms']); $i++)
                                                            <span>- {{ $item['tkbms'][$i] }}</span><br>
                                                        @endfor
                                                    </td>
                                                @endif
                                            @endif

                                            <td>{{ $item['jumlah_uang_rp'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th>{{ $totalNetto }} Kg</th>
                                        <th></th>
                                        @if ($karyawan->main_type_karyawan_id == 2)
                                            <th colspan="{{ $colspanTKBM + 1 }}" style="text-align: center">Total Uang</th>
                                        @endif
                                        @if ($karyawan->main_type_karyawan_id == 1)
                                            <th style="text-align: center" colspan={{ count($pabriks) }}></th>
                                        @endif
                                        @if ($karyawan->main_type_karyawan_id == 1)
                                            @if ($colspanTKBM > 0)
                                                <th></th>
                                            @endif
                                        @endif
                                        <th>{{ $totalUang }}</th>

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
