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
                                        <th style="text-align: center">No</th>
                                        <th style="text-align: center">Tanggal</th>
                                        <th style="text-align: center">Tonase</th>
                                        <th style="text-align: center">Per Kg</th>
                                        <th style="text-align: center" colspan="{{ $colspanTKBM }}">TKBM </th>
                                        <th style="text-align: center">Total</th>
                                        <th style="text-align: center">Jumlah Uang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td style="{{ $item['alpha'] ? 'background-color: red; color:white' : '' }}">
                                                {{ $item['created_at_formatted'] }}</td>
                                            <td>{{ $item['netto'] }} kg</td>
                                            <td>{{ $item['tarif_perkg_rp'] }}</td>
                                            @for ($i = 0; $i < $colspanTKBM; $i++)
                                                <td>{{ isset($item['tkbms'][$i]) ? $item['tkbms'][$i] : '-' }}</td>
                                            @endfor
                                            <td>{{ $item['total'] }}</td>
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
                                        <th colspan="{{ $colspanTKBM + 1 }}" style="text-align: center">Total Uang</th>
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
