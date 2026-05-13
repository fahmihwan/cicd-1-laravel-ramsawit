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
                                        <option value="{{ $val }}"
                                            {{ $selectedBulan == $val ? 'selected' : '' }}>
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
                                        <th style="text-align: center">Pekerjaan</th>
                                        <th style="text-align: center">Tonase</th>
                                        <th style="text-align: center">Per Kg</th>
                                        <th style="text-align: center">PKS</th>
                                        <th style="text-align: center">TKBM </th>
                                        <th style="text-align: center">Total</th>
                                        <th style="text-align: center">Jumlah Uang</th>
                                        <th style="text-align: center">Gaji Diambil</th>
                                        <th style="text-align: center">Sudah Dibayarkan</th>
                                        <th style="text-align: center">#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td> {{ $item['created_at_formatted'] }}</td>
                                            <td>{{ $item['type_karyawan'] }}</td>
                                            <td>{{ $item['netto'] }} kg</td>
                                            <td>{{ $item['tarif_perkg_rp'] }}</td>
                                            <td>{{ $item['nama_pabrik'] }}</td>
                                            <td>
                                                @for ($i = 0; $i < count($item['tkbms']); $i++)
                                                    - {{ isset($item['tkbms'][$i]) ? $item['tkbms'][$i] : '-' }} </br>
                                                @endfor
                                            </td>
                                            <td>{{ $item['total'] }}</td>
                                            <td>{{ $item['jumlah_uang_rp'] }}</td>
                                            <td style="text-align: center">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" value=""
                                                            style="width: 25px;height: 25px;accent-color: #2196F3;cursor: pointer;">
                                                    </label>
                                                </div>
                                            </td>
                                            <td style="text-align: center">
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" value=""
                                                            style="width: 25px;height: 25px;accent-color: #2196F3;cursor: pointer;">
                                                    </label>
                                                </div>
                                            </td>
                                            <td>
                                                <form method="POST" action="/penggajian/{{ $item['id'] }}">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-circle "
                                                        style="margin-right: 5px">
                                                        <i class="fa fa-trash"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th>{{ $totalNetto }} Kg</th>
                                        <th colspan="" style="text-align: center"></th>
                                        <th></th>
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
                        Keterangan
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
                                            value="{{ $penggajian_karyawan->total_gaji }}" readonly>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Pinjaman saat ini</label>
                                        <input class="form-control" type="number" name="pinjaman_saat_ini"
                                            id="pinjaman_saat_ini" value="{{ $penggajian_karyawan->pinjaman_saat_ini }}"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Potongan Pinjaman</label>
                                        <input class="form-control" type="number" name="potongan_pinjaman"
                                            value="{{ $penggajian_karyawan->potongan_pinjaman }}" id="potongan_pinjaman">
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Sisa Pinjaman</label>
                                        <input class="form-control" type="number" name="sisa_pinjaman"
                                            value="{{ $penggajian_karyawan->sisa_pinjaman }}" id="sisa_pinjaman"
                                            readonly>
                                    </div>

                                </div>
                            </div>


                            <div class="form-group">
                                <label>Gaji yang diterima</label>
                                <input class="form-control" type="number" name="gaji_yang_diterima"
                                    value="{{ $penggajian_karyawan->gaji_yang_diterima }}" id="gaji_yang_diterima"
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
