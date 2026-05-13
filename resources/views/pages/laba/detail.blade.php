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
            <div class="col-md-12">
                <div style="display: flex; justify-content: space-between;  align-items: center">
                    <div style="display: flex; align-items: center">
                        <h1 class="page-header">Laba periode ke-{{ $periode->periode }}
                        </h1>
                        <h3>
                            @if (!$periode->periode_berakhir)
                                <span style="color:red; margin-left: 20px">(Belum ditutup)</span>
                            @endif
                        </h3>

                    </div>
                    <h3 style="display: flex; align-items: center">
                        <b>{{ \Carbon\Carbon::parse($periode->periode_mulai)->format('d M Y') }}</b>
                        @if ($periode->periode_berakhir)
                            sampai
                            <b>{{ \Carbon\Carbon::parse($periode->periode_berakhir)->format('d M Y') }}</b>
                            <a class="btn btn-primary btn-sm" style="margin-left: 20px"
                                href="{{ url('/export-laba/' . request()->route('id')) }}" target="_blank">
                                <i class="fa fa-download fa-lg" aria-hidden="true"></i>&nbsp;Download</a>
                            </a>
                        @endif
                    </h3>

                </div>
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
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="display: flex; justify-content: space-between">
                        <div>
                            Pembelian
                        </div>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table  table-bordered ">
                                <thead>
                                    <tr style="text-align: center">
                                        <th style="text-align: center">Jenis TBS</th>
                                        <th style="text-align: center">Tonase</th>
                                        <th style="text-align: center">Harga</th>
                                        <th style="text-align: center">Uang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($pembelian as $item)
                                        <tr>
                                            <td>{{ $item->type_tbs }}</td>
                                            <td>{{ number_format($item->netto, 0, ',', '.') }} Kg</td>
                                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item->uang, 0, ',', '.') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th>
                                        Total
                                    </th>
                                    <th style="background-color: yellow">
                                        {{ number_format($pembelian->sum('netto'), 0, ',', '.') }} Kg
                                    </th>
                                    <th>

                                    </th>
                                    <th style="background-color: yellow">
                                        Rp {{ number_format($pembelian->sum('uang'), 0, ',', '.') }}
                                    </th>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            {{-- <div class="col-md-6"> --}}

            {{-- <div class="panel panel-default">
                    <div class="panel-heading" style="display: flex; justify-content: space-between">
                        <div>
                            Penjualan
                        </div>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered ">
                                <thead>
                                    <tr style="text-align: center">
                                        <th style="text-align: center">Tonase Pabrik</th>
                                        <th style="text-align: center">Harga</th>
                                        <th style="text-align: center">Uang</th>
                                        <th style="text-align: center">Jenis DO</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penjualan as $item)
                                        <tr>


                                            <td>{{ number_format($item->netto, 0, ',', '.') }} Kg</td>
                                            <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                            <td>Rp {{ number_format($item->uang, 0, ',', '.') }}</td>
                                            <td>{{ $item->delivery_order_type }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th style="background-color: yellow">
                                        {{ number_format($penjualan->sum('netto'), 0, ',', '.') }} Kg
                                    </th>
                                    <th>

                                    </th>
                                    <th style="background-color: yellow">
                                        Rp {{ number_format($penjualan->sum('uang'), 0, ',', '.') }}
                                    </th>
                                    <th>

                                    </th>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div> --}}
            <!-- /.panel -->

            {{-- </div> --}}
            <!-- /.col-lg-12 -->
        </div>

        <div class="row">

            <!-- /.col-lg-12 -->
        </div>






        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading" style="display: flex; justify-content: space-between">
                        <div>
                            Penjualan
                        </div>
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered ">
                                <thead>
                                    <tr class="text-center">
                                        <th>Ops</th>
                                        <th>Tonase Pabrik</th>
                                        <th>Harga</th>
                                        <th>Uang</th>
                                        <th>Jenis DO</th>
                                        <th>Total OPS</th>
                                        <th>Total Tonase Pabrik</th>
                                        <th>Total Uang</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalTonasePabrik = 0;
                                        $totalUangPenjualan = 0;
                                        $totalOps = 0;
                                    @endphp
                                    @foreach ($penjualanGroupByOps as $value)
                                        @php
                                            $rowspan = count($value['values']);
                                            $totalNetto = collect($value['values'])->sum('netto');
                                            $totalUang = collect($value['values'])->sum('uang');

                                        @endphp
                                        @foreach ($value['values'] as $item)
                                            <tr>
                                                @if ($loop->first)
                                                    <td rowspan="{{ $rowspan }}" class=""
                                                        style="text-align: center; vertical-align: middle;">
                                                        {{ $value['ops'] }}
                                                    </td>
                                                @endif


                                                <td>{{ number_format($item->netto, 0, ',', '.') }} Kg</td>
                                                {{-- <td>{{ number_format($item->ops, 0, ',', '.') }}</td> --}}
                                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($item->uang, 0, ',', '.') }}</td>
                                                <td>{{ $item->delivery_order_type }}</td>
                                                <td>{{ number_format($item->total_ops ?? $item->netto * $item->ops, 0, ',', '.') }}
                                                </td>
                                                @php
                                                    $totalOps += $item->netto * $item->ops;
                                                @endphp
                                                @if ($loop->first)
                                                    @php
                                                        $totalTonasePabrik += $totalNetto;
                                                        $totalUangPenjualan += $totalUang;

                                                    @endphp
                                                    <td rowspan="{{ $rowspan }}"
                                                        style="text-align:center; vertical-align:middle; ">
                                                        Rp {{ number_format($totalNetto, 0, ',', '.') }} Kg
                                                    </td>
                                                    <td rowspan="{{ $rowspan }}"
                                                        style="text-align:center; vertical-align:middle; ">
                                                        Rp {{ number_format($totalUang, 0, ',', '.') }}
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <th colspan="5">Total</th>

                                    <th style="background-color: yellow">
                                        Rp {{ number_format($totalOps, 0, ',', '.') }}</th>
                                    </th>
                                    <th style="">
                                        {{ number_format($totalTonasePabrik, 0, ',', '.') }} Kg</th>
                                    <th style="background-color: yellow"> Rp
                                        {{ number_format($totalUangPenjualan, 0, ',', '.') }} </th>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>




        {{-- <div class="row">
            @php
                $iteration = 1;
            @endphp
            @foreach ($penjualanGroupByOps as $value)
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading" style="display: flex; justify-content: space-between">
                            <div>
                                Penjualan {{ $iteration++ }} : <b>(OPS :{{ $value['ops'] }})</b>
                            </div>
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered ">
                                    <thead>
                                        <tr style="text-align: center">
                                            <th style="text-align: center">Tonase Pabrik</th>
                                            <th style="text-align: center">Ops</th>
                                            <th style="text-align: center">Harga</th>
                                            <th style="text-align: center">Uang</th>
                                            <th style="text-align: center">Jenis DO</th>
                                            <th style="text-align: center">Total OPS</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($value['values'] as $item)
                                            <tr>
                                                <td>{{ number_format($item->netto, 0, ',', '.') }} Kg</td>
                                                <td>{{ number_format($item->ops, 0, ',', '.') }} Kg</td>
                                                <td>Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                                <td>Rp {{ number_format($item->uang, 0, ',', '.') }}</td>
                                                <td>{{ $item->delivery_order_type }}</td>
                                                <td>{{ number_format($item->netto * $item->ops, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <th style="background-color: yellow">
                                            {{ number_format($value['values']->sum('netto'), 0, ',', '.') }} Kg
                                        </th>
                                        <th></th>
                                        <th></th>
                                        <th style="background-color: yellow">
                                            Rp {{ number_format($value['values']->sum('uang'), 0, ',', '.') }}
                                        </th>
                                        <th></th>
                                        <th>Rp {{ number_format($value['values']->sum('total_ops'), 0, ',', '.') }}</th>
                                    </tfoot>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
            @endforeach
            <!-- /.col-lg-12 -->
        </div> --}}

        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">
                    <div class="panel-heading" style="display: flex; justify-content: space-between">
                        <div>
                            Perhitungan Laba
                        </div>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered ">
                                <tbody>
                                    <tr>
                                        <th>Cair Do</th>
                                        <td>Rp
                                            {{ number_format($finalLaba['cairDo'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Modal</th>
                                        <td>Rp
                                            {{ number_format($finalLaba['modal'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>OPS</th>
                                        <td>Rp
                                            {{ number_format($finalLaba['totalOps'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Laba Bersih</th>
                                        <td style="background-color: yellow">Rp
                                            {{ number_format($finalLaba['labaBersih'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
        </div>

    </div>
@endsection

@section('script')
@endsection
