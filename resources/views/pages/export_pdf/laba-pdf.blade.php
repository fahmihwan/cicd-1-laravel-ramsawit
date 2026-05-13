<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        /* Sembunyikan tanggal */
        .ui-datepicker-calendar {
            display: none;
        }

        /* Tampilkan tombol OK saja */
        .ui-datepicker-close {
            display: inline-block;
        }


        /*  */
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
            /* margin-top: 20px; */
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
            margin-bottom: 2px;
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

    <div class="container-fluid">
        <table style="width:100%">
            <tr style="border: none">
                <td style="text-align:left; border: none">
                    <h1>Laba periode ke-{{ $periode->periode }}</h1>
                    @if (!$periode->periode_berakhir)
                        <span style="color:red">(Belum ditutup)</span>
                    @endif
                </td>
                <td style="text-align:right;border: none">
                    <h3>
                        <b>{{ \Carbon\Carbon::parse($periode->periode_mulai)->format('d M Y') }}</b>
                        @if ($periode->periode_berakhir)
                            sampai <b>{{ \Carbon\Carbon::parse($periode->periode_berakhir)->format('d M Y') }}</b>
                        @endif
                    </h3>
                </td>
            </tr>
        </table>


        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table  table-bordered ">
                                <thead>
                                    <tr>
                                        <h2>Pembelian</h2>
                                    </tr>
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

        </div>

        <div class="row">


        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered ">
                                <thead>
                                    <tr>

                                        <h2>Penjualan</h2>

                                    </tr>
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
                                        {{ number_format($totalTonasePabrik, 0, ',', '.') }} Kg
                                    </th>
                                    <th style="background-color: yellow"> Rp
                                        {{ number_format($totalUangPenjualan, 0, ',', '.') }}
                                    </th>
                                </tfoot>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-default">

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <h2>Perhitungan Laba</h2>
                                    </tr>
                                </thead>
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
                                            <b>{{ number_format($finalLaba['labaBersih'], 0, ',', '.') }}</b>
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

</body>

</html>
