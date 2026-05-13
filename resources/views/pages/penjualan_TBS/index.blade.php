@extends('layouts.main')


@section('style')
    <!-- Select2 CSS -->
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" /> --}}
    <!-- Select2 CSS (versi lama, cocok untuk jQuery 2.1.3) -->
    {{-- <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" /> --}}

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .select2-container--default .select2-results>.select2-results__options {
            max-height: 200px;
            overflow-y: auto;
        }

        .select2-container .select2-selection--single {
            height: 38px !important;
            /* padding: 10px 12px; */
            font-size: 14px;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
        }

        .select2-container--default .select2-selection--multiple {
            min-height: 100px;
            /* Bisa kamu ubah sesuai kebutuhan */
            max-height: 200px;
            /* Tambahkan jika mau batasi */
            overflow-y: auto;
            padding: 5px;
        }
    </style>
@endsection


@section('container')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Penjualan {{ $title }}</h1>
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
                        Periode pembelian
                    </div>
                    <div class="panel-body">
                        <table>
                            <tr>
                                <td>Periode </td>
                                <td style="padding-left:20px">: {{ $periode->periode }}</td>
                            </tr>
                            <tr>
                                <td>Periode berakhir</td>
                                <td style="padding-left:20px">:
                                    @if ($periode->periode_berakhir == null)
                                        <span class="label label-success" style="font-size: 12px">Periode
                                            masih berjalan</span>
                                    @else
                                        {{ $periode->formatted_berakhir }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Total netto </td>
                                <td style="padding-left:20px">: {{ number_format($periode->total_netto, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading ">
                        <div style=" display: flex; justify-content: space-between; align-items: center">
                            <div>
                                Penjualan
                            </div>
                            <div>
                                <a href="/penjualan/tbs/{{ $menu }}/periode" type="button"
                                    class="btn btn-outline btn-default">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                                @if ($periode->periode_berakhir == null)
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        id="btn-create" data-target="#modalCreateEdit">
                                        <i class="fa fa-plus"></i> Tambah Data
                                    </button>
                                @endif


                            </div>

                        </div>
                    </div>

                    <div>
                        <form method="GET" id="perPageForm" class="container-filter-datatables">
                            <div class="container-left-datatables">
                                <span style="margin-left: 5px; margin-right: 5px">Show</span>
                                <select class="form-control" name="per_page" style="width: 100px"
                                    onchange="document.getElementById('perPageForm').submit()">
                                    @foreach ([10, 25, 50, 100] as $size)
                                        <option value="{{ $size }}"
                                            {{ request('per_page') == $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>
                                <span style="margin-left: 5px; margin-right: 5px">entries</span>
                            </div>




                            <div class="container-right-datatables">
                                <div class="form-filter-datatables">
                                    <span style="display: block; margin-right: 5px; width: 140px">Filter Tanggal</span>
                                    <input type="date" name="tanggal" value="{{ request('tanggal') }}"
                                        class="form-control" style="width: 100%">
                                </div>

                                <div class="form-filter-datatables">
                                    <span style="display: block; margin-right: 5px ">Search </span>
                                    <input class="form-control" name="search" value="{{ request('search') }}">
                                </div>

                                <div class="form-filter-datatables">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-search"></i> Search
                                    </button>
                                </div>

                                <div class="form-filter-datatables">
                                    <a href="{{ '/penjualan/tbs/' . $menu . '/' . request('periode') . '/view' }}"
                                        class="btn btn-info btn-sm">
                                        <i class="fa fa-refresh"></i> clear
                                    </a>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Tgl Penjualan / Periode</th>
                                        <th>Pabrik</th>
                                        <th>Sopir</th>
                                        <th>TKBM</th>
                                        <th>Timbangan 1</th>
                                        <th>Timbangan 2</th>
                                        <th>Bruto</th>
                                        <th>Sortasi</th>
                                        <th>Netto</th>
                                        <th>Harga</th>
                                        <th>Uang</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr class="">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->formatted_tgl_penjualan }} /
                                                <span class="label label-danger">{{ $item->periode->periode }}</span> <br>
                                                <span class="text-info"
                                                    style="font-weight: bold">{{ isset($item->model_kerja->model_kerja) ? $item->model_kerja->model_kerja : '' }}</span>
                                            </td>
                                            <td>{{ $item->pabrik->nama_pabrik ?? '-' }}</td>
                                            <td>
                                                @if ($item->model_kerja_id == 1)
                                                    <span
                                                        class="label label-primary">{{ $item->tarif_sopir->tarif_perkg ?? '' }}</span>
                                                    {{ $item->sopir->nama ?? '-' }}
                                                @endif
                                                @if ($item->model_kerja_id == 2)
                                                    <span
                                                        class="label label-warning">{{ 'Rp ' . number_format($item->tarif_sopir_borongan, 0, ',', '.') ?? '' }}</span>
                                                    {{ $item->sopir->nama ?? '-' }}
                                                @endif
                                            </td>
                                            <td>
                                                <div style="display: flex">
                                                    <div style="margin-right: 5px">
                                                        @if ($item->model_kerja_id == 1)
                                                            <span class="label label-primary">
                                                                {{ $item->tarif_tkbm->tarif_perkg ?? '' }}</span>
                                                        @endif
                                                        @if ($item->model_kerja_id == 2)
                                                            @foreach ($item->tkbms as $d)
                                                                @if ($d->type_karyawan_id == 2)
                                                                    <span class="label label-warning">
                                                                        {{ 'Rp ' . number_format($d->tarif_tkbm_borongan, 0, ',', '.') ?? '-' }}
                                                                    </span> <br>
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div>
                                                        @foreach ($item->tkbms as $d)
                                                            @if ($d->type_karyawan_id == 2)
                                                                <p style="margin: 0; padding: 0;">-
                                                                    {{ $d->karyawan->nama ?? '-' }}
                                                                </p>
                                                            @endif
                                                        @endforeach
                                                    </div>

                                                </div>

                                            </td>
                                            <td>{{ $item->timbangan_first_formatted }}</td>
                                            <td>{{ $item->timbangan_second_formatted }}</td>
                                            <td>{{ $item->bruto_formatted }}</td>
                                            <td>{{ $item->sortasi_formatted }}</td>
                                            <td>
                                                <span class="label label-info">{{ $item->ops ?? '' }}</span>
                                                {{ $item->netto_formatted }}
                                            </td>
                                            <td>{{ $item->harga_formatted }}</td>
                                            <td>{{ $item->uang_formatted }}</td>
                                            {{-- <td class="center">{{ $item->formatted_created_at }}</td> --}}
                                            <td style="display: flex; border-bottom: 1px">

                                                @if ($item->periode->periode_berakhir != null)
                                                    <button data-bs-toggle="modal" type="button"
                                                        class="btn  btn-circle btn-edit"
                                                        style="background-color: gray; color:white margin-right: 5px"
                                                        onclick="alert('Periode sudah ditutup');">
                                                        <i class="fa fa-lock"></i>
                                                    </button>
                                                @else
                                                    <button data-id="{{ $item->id }}"
                                                        data-tarifsopirid="{{ $item->tarif_sopir_id }}"
                                                        data-tariftkbmid="{{ $item->tarif_tkbm_id }}"
                                                        data-tarifsopirborongan="{{ $item->tarif_sopir_borongan ?? '' }}"
                                                        data-tariftkbmborongan="{{ $item->tarif_tkbm_borongan ?? '' }}"
                                                        data-jsontkbm="{{ $item->tkbms }}"
                                                        data-tarifsopirtext="{{ $item->tarif_sopir->tarif_perkg ?? '' }}"
                                                        data-tariftkbmtext="{{ $item->tarif_tkbm->tarif_perkg ?? '' }}"
                                                        data-modelkerja="{{ $item->model_kerja_id }}"
                                                        data-tanggalpenjualan={{ $item->tanggal_penjualan }}
                                                        data-periode={{ $item->periode->periode }}
                                                        data-nama="{{ $item->sopir->id ?? '' }}"
                                                        data-pabrik="{{ $item->pabrik_id ?? '' }}"
                                                        data-tkbms='@json($item->tkbms)'
                                                        data-timbangan1="{{ $item->timbangan_first }}"
                                                        data-timbangan2="{{ $item->timbangan_second }}"
                                                        data-bruto="{{ $item->bruto }}"
                                                        data-sortasi="{{ $item->sortasi }}"
                                                        data-ops="{{ $item->ops }}" data-netto="{{ $item->netto }}"
                                                        data-harga="{{ $item->harga }}" data-uang="{{ $item->uang }}"
                                                        data-bs-toggle="modal" type="button"
                                                        class="btn btn-warning btn-circle btn-edit" data-toggle="modal"
                                                        data-target="#modalCreateEdit" style="margin-right: 5px"
                                                        data-id="{{ $item->id }}"><i class="fa fa-edit"></i>
                                                    </button>


                                                    <form method="POST"
                                                        action="/penjualan/tbs/{{ $menu }}/delete/{{ $item->id }}">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-danger btn-circle btn-confirm-delete">
                                                            <i class="fa fa-trash"></i></button>
                                                    </form>
                                                @endif


                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <p> Showing {{ $items->firstItem() }} to {{ $items->lastItem() }} of
                                    {{ $items->total() }} eentries</p>

                                {{ $items->links() }}
                            </div>

                        </div>

                        <!-- /.table-responsive -->
                    </div>
                    <!-- /.panel-body -->
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>


        <input type="hidden" value="{{ $menu }}" id="menu">
        <input type="hidden" value="{{ $periode->id }}" id="periode">


        <!-- Modal CREATE-->
        <div class="modal fade" id="modalCreateEdit" tabindex="-1" role="dialog" aria-labelledby="mymodalCreateEdit"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <form id='mainForm' role="form" method=POST
                        action={{ '/penjualan/tbs/' . $menu . '/' . $periode->id . '/view' }}>
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="mymodalCreateEdit">Pembelian {{ $title }}</h4>
                        </div>


                        <div class="modal-body">
                            <input type="hidden" id="formMethod" name="_method" value="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group "> {{-- has-success, has-warning, has-error --}}
                                        <label>Tgl Penjualan</label>
                                        <input type="date" class="form-control" name="tanggal_penjualan"
                                            value="{{ now()->toDateString() }}" id="tanggal_penjualan" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group" id="form-periode-select">
                                        <label>Periode</label>
                                        <input class="form-control" name="periode_id" value="{{ $periode->periode }}"
                                            id="periode_id_text" readonly>
                                    </div>

                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pabrik_id">Pilih Pabrik </label><br>
                                        <select name="pabrik_id" id="pabrik_id" class="form-control"
                                            style="width: 100%;">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($data_pabrik as $pabrik)
                                                <option value="{{ $pabrik->id }}"
                                                    {{ old('pabrik_id') == $pabrik->id ? 'selected' : '' }}>
                                                    {{ $pabrik->nama_pabrik }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>OPS</label>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="number" class="form-control" name="ops"
                                                value="{{ old('ops') }}" id="ops" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>Harga Pabrik</label>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="number" class="form-control" name="harga"
                                                value="{{ old('harga') }}" id="harga" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>Timbangan 1 (kg)</label>
                                        <div class="form-group ">
                                            <input type="number" class="form-control" name="timbangan_first"
                                                value="{{ old('timbangan_first') }}" required id="timbangan_first">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>Timbangan 2 (kg)</label>
                                        <div class="form-group ">
                                            <input type="number" class="form-control" name="timbangan_second"
                                                value="{{ old('timbangan_second', 0) }}" id="timbangan_second" required>
                                        </div>
                                    </div>
                                </div>

                            </div>


                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>Sortasi (%)</label>
                                        <div class="form-group input-group">
                                            <input class="form-control" name="sortasi" value="{{ old('sortasi') }}"
                                                id="sortasi">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>Bruto</label>
                                        <div class="form-group input-group">
                                            <input class="form-control" type="number" name="bruto"
                                                value="{{ old('bruto') }}" readonly id="bruto">
                                            <span class="input-group-addon">Kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>Netto</label>
                                        <div class="form-group input-group">
                                            <input type="number" class="form-control" name="netto" readonly
                                                value="{{ old('netto') }}" id="netto">
                                            <span class="input-group-addon">Kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group ">
                                        <label>Uang</label>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="form-control" readonly name="uang"
                                                value="{{ old('uang') }}" id="uang">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-2">
                                    <div class="form-group"
                                        style="border:1px solid rgb(70, 137, 230); padding: 5px; border-radius: 5px;">
                                        <label>Model Kerja</label>
                                        <div>
                                            <label class="radio-inline">
                                                <input type="radio" name="model_kerja_id" id="tonase"
                                                    value="1" checked style="">Tonase
                                            </label> <br>
                                            <label class="radio-inline">
                                                <input type="radio" name="model_kerja_id" id="borongan"
                                                    value="2">Borongan
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-10">

                                    {{-- #TONASE --}}
                                    <div id="tarif-tonase">
                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                TONASE
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sopir_id">Pilih Sopir</label><br>
                                                            <select name="sopir_id" id="sopir_id" class="form-control"
                                                                style="width: 100%;">
                                                                <option value="">-- Pilih Sopir --</option>
                                                                @foreach ($karyawans as $karyawan)
                                                                    @if ($karyawan->type_karyawan == 'SOPIR')
                                                                        <option value="{{ $karyawan->id }}"
                                                                            {{ old('sopir_id') == $karyawan->id ? 'selected' : '' }}>
                                                                            {{ $karyawan->nama }}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="form-group" id="form-periode-select">
                                                            <label>Tarif Sopir</label>
                                                            <select name="tarif_sopir_id" id="tarif_sopir_id_select"
                                                                class="form-control" style="width: 100%; ">
                                                                <option value="">-- Pilih --</option>
                                                                @foreach ($data_list_tarif as $p)
                                                                    @if ($p->type_karyawan == 'SOPIR')
                                                                        <option value="{{ $p->id }}"
                                                                            {{ old('tarif_sopir_id') == $p->id ? 'selected' : '' }}>
                                                                            {{ $p->tarif_perkg }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="form-group" id="form-periode-select">
                                                            <label>Tarif TKBM</label>
                                                            <select name="tarif_tkbm_id" id="tarif_tkbm_id_select"
                                                                class="form-control" style="width: 100%; ">
                                                                <option value="">-- Pilih --</option>
                                                                @foreach ($data_list_tarif as $p)
                                                                    @if ($p->type_karyawan == 'TKBM')
                                                                        <option value="{{ $p->id }}"
                                                                            {{ old('tarif_tkbm_id') == $p->id ? 'selected' : '' }}>
                                                                            {{ $p->tarif_perkg }}</option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                </div>

                                                @php
                                                    // Pastikan nilai selalu array
                                                    $selectedTkbm = old('tkbm_id');

                                                    // Jika tidak ada old(), dan misalnya ini form edit, fallback ke $model->tkbm_ids
                                                    if (!is_array($selectedTkbm)) {
                                                        $selectedTkbm =
                                                            isset($model) && is_array($model->tkbm_ids ?? null)
                                                                ? $model->tkbm_ids
                                                                : [];
                                                    }
                                                @endphp

                                                <div class="form-group">
                                                    <select name="tkbm_id[]" multiple="multiple" id="tkbm_id"
                                                        class="form-control" style="width: 100%;">
                                                        @foreach ($karyawans as $karyawan)
                                                            @if ($karyawan->type_karyawan == 'TKBM')
                                                                <option value="{{ $karyawan->id }}"
                                                                    {{ in_array($karyawan->id, $selectedTkbm) ? 'selected' : '' }}>
                                                                    {{ $karyawan->nama }}
                                                                </option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>

                                            </div>
                                        </div>


                                    </div>



                                    {{-- #BORONGAN --}}
                                    <div id="tarif-borongan">

                                        <div class="panel panel-primary">
                                            <div class="panel-heading">
                                                BORONGAN
                                            </div>
                                            <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="sopir_id">Pilih Sopir</label><br>
                                                            <select name="sopir_borongan_id" id="sopir_borongan_id"
                                                                class="form-control" style="width: 100%;">
                                                                <option value="">-- Pilih Sopir --</option>
                                                                @foreach ($karyawans as $karyawan)
                                                                    @if ($karyawan->type_karyawan_id == 1 || $karyawan->type_karyawan_id == 4)
                                                                        <option value="{{ $karyawan->id }}">
                                                                            {{ $karyawan->nama }}
                                                                        </option>
                                                                    @endif
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group ">
                                                            <label>Tarif Sopir</label>
                                                            <div class="form-group ">
                                                                <input type="number" class="form-control"
                                                                    name="tarif_sopir_borongan" value="0"
                                                                    id="tarif_sopir_borongan">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="data_tkbm_dinamis_borongan_json"
                                                    id="data_tkbm_dinamis_borongan_json"
                                                    style="width: 100%; height: 50px;">

                                                <div class="row" style="margin-bottom: 10px">
                                                    <div class="col-md-12">
                                                        <div style="border:1px solid #46b8da" class="bg-success">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr class="info">
                                                                        <td style="width: 50%">TKBM</td>
                                                                        <td style="width: 50%">TARIF BORONGAN</td>
                                                                        <td style="width: 10%">#</td>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="tbody-tkbm-dinamis-borongan">


                                                                </tbody>
                                                                <tfoot>
                                                                    <tr class="success">
                                                                        <td colspan="3">
                                                                            <div
                                                                                style="display: flex; align-items: center; justify-content: center">
                                                                                <button
                                                                                    class="btn btn-primary btn-add-row-dinamis-borongan">
                                                                                    <i class="fa fa-plus"></i>
                                                                                </button>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">

                                <div style="display: flex; justify-content: space-between; color: red">

                                    <div>

                                    </div>

                                    <div class="">
                                        <button type="button" class="btn btn-default"
                                            data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Save changes</button>
                                    </div>

                                </div>
                            </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

    </div>
@endsection

@section('script')
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script> --}}
    {{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
        $(document).ready(function() {

            const listKaryawans = @json($karyawans);

            let dataBorongan = []

            var value = $('input[name="model_kerja_id"]').val();
            if (value === '1') {
                $('#tarif-tonase').show();
                $('#tarif-borongan').hide();
            } else if (value === '2') {
                $('#tarif-tonase').hide();
                $('#tarif-borongan').show();
            }

            $('input[name="model_kerja_id"]').on('change', function() {
                var value = $(this).val();
                if (value === '1') {
                    $('#tarif-tonase').show();
                    $('#tarif-borongan').hide();
                    // clearModelKerjaGroup()
                } else if (value === '2') {
                    $('#tarif-tonase').hide();
                    $('#tarif-borongan').show();
                    // clearModelKerjaGroup()
                }
            });



            let menu = $('#menu').val()
            let periodeId = $('#periode').val()

            $(document).ready(function() {
                $('#pabrik_id').select2({
                    placeholder: "Pilih User",
                    allowClear: true,
                    dropdownParent: $('#modalCreateEdit')
                });


                $('#sopir_id').select2({
                    placeholder: "Pilih User",
                    allowClear: true,
                    dropdownParent: $('#modalCreateEdit')
                });

                $('#tkbm_id').select2({
                    placeholder: "Pilih User",
                    allowClear: true,
                    dropdownParent: $('#modalCreateEdit')
                });


                $('#sopir_borongan_id').select2({
                    placeholder: "Pilih User",
                    allowClear: true,
                    dropdownParent: $('#modalCreateEdit')
                });

                $('#tkbm_borongan_id').select2({
                    placeholder: "Pilih User",
                    allowClear: true,
                    dropdownParent: $('#modalCreateEdit')
                });

                $('.tkbm_borongan_class').select2({
                    placeholder: "Pilih User",
                    allowClear: true,
                    dropdownParent: $('#modalCreateEdit')
                });

            });





            function formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', {
                    maximumFractionDigits: 0,
                    minimumFractionDigits: 0,
                }).format(angka);
            }

            function hitungRUMAH_LAHAN() {
                var netto = parseInt($('#netto').val()) || 0;
                var harga = parseInt($('#harga').val()) || 0;
                var total = netto * harga;
                $('#uang').val(formatRupiah(total));
            }



            function hitungRam() {
                let timbang1 = parseInt($('#timbangan_first').val()) || 0;
                let timbang2 = parseInt($('#timbangan_second').val()) || 0;

                let bruto = timbang1 - timbang2;

                let sortasiPersen = parseFloat($('#sortasi').val()) || 0;
                let sortasiNilai = bruto * (sortasiPersen / 100);
                let netto = bruto - sortasiNilai;

                let harga = parseInt($('#harga').val()) || 0;
                let uang = netto * harga;

                $('#bruto').val(Math.ceil(bruto));
                $('#netto').val(Math.ceil(netto));
                $('#uang').val(formatRupiah(uang));

            }

            $(' #timbangan_first, #timbangan_second, #sortasi, #harga').on('input', hitungRam);
            $('#netto, #harga').on('input', hitungRUMAH_LAHAN);


            function clearModelKerjaGroup() {
                $('#tarif_sopir_id_select').val('').trigger('change');
                $('#tarif_tkbm_id_select').val('').trigger('change');
                $('#sopir_id').val('').trigger('change');
                $('#tkbm_id').val('').trigger('change');
                $('#sopir_borongan_id').val('').trigger('change');
                $('#tkbm_borongan_id').val('').trigger('change');
                $('#tarif_sopir_borongan').val('')
                $('#tarif_tkbm_borongan').val('')

                $('#tarif-tonase').show();
                $('#tarif-borongan').hide();

                $('input[name="model_kerja_id"][value="1"]').prop('checked', true);
                refreshAllSelects()
                dataBorongan = []
                renderTkbmBorongan(dataBorongan);


            }



            $('#btn-create').on('click', function() {
                $('#mainForm')[0].reset(); // Kosongkan form
                clearModelKerjaGroup()
                $('#mymodalCreateEdit').text('Tambah TBS ' + menu);
                $('#mainForm').attr('action', '/penjualan/tbs/' + menu + '/' + periodeId + '/view');
                $('#tanggal_penjualan').prop('disabled', false);

                // $('#form-periode-select').show()
                // $('#form-periode-text').hide()

                $('#formMethod').val('POST')
            });


            $('.btn-edit').on('click', function() {
                let id = $(this).data('id');
                $('#mainForm')[0].reset(); // Kosongkan form
                $('#mymodalCreateEdit').text('Edit TBS ' + menu);
                $('#mainForm').attr('action', '/penjualan/tbs/' + menu + '/' + periodeId + '/view/' + id);
                $('#formMethod').val('PUT')

                // $('#form-periode-select').hide()
                // $('#form-periode-text').show()




                const tarifSopirId = $(this).data('tarifsopirid');
                const tarifTkbmId = $(this).data('tariftkbmid');
                const tarifSopirText = $(this).data('tarifsopirtext');
                const tarifTkbmText = $(this).data('tariftkbmtext');
                const tarifTkbmBorongan = $(this).data('tariftkbmborongan')
                const tarifSopirBorongan = $(this).data('tarifsopirborongan')
                const jsontkbm = $(this).data('jsontkbm')



                let jsonTkbmMap = jsontkbm.filter((x) => x.type_karyawan_id == 2 || x.type_karyawan_id == 4)
                    .map((d) => {
                        return {
                            'karyawan_id': d.karyawan_id,
                            'tarif_borongan': d.tarif_tkbm_borongan
                        }
                    })

                dataBorongan = jsonTkbmMap
                renderTkbmBorongan(dataBorongan);
                const modelkerja = $(this).data('modelkerja')
                $(`input[name="model_kerja_id"][value="${modelkerja}"]`).prop('checked', true);

                console.log(modelkerja);
                if (modelkerja === 1) {
                    $('#tarif-tonase').show();
                    $('#tarif-borongan').hide();
                } else if (modelkerja === 2) {
                    $('#tarif-tonase').hide();
                    $('#tarif-borongan').show();
                }

                if (modelkerja === 1) {
                    if ($('#tarif_sopir_id_select option[value="' + tarifSopirId + '"]').length === 0) {
                        $('#tarif_sopir_id_select').append(
                            $('<option>', {
                                value: tarifSopirId,
                                text: tarifSopirText
                            })
                        );
                    }
                    $('#tarif_sopir_id_select').val(tarifSopirId).trigger('change');

                    if ($('#tarif_tkbm_id_select option[value="' + tarifTkbmId + '"]').length === 0) {
                        $('#tarif_tkbm_id_select').append(
                            $('<option>', {
                                value: tarifTkbmId,
                                text: tarifTkbmText
                            })
                        );
                    }
                    $('#tarif_tkbm_id_select').val(tarifTkbmId).trigger('change');

                    $('#sopir_id').val($(this).data('nama')).trigger('change');;

                    const tkbms = $(this).data('tkbms');
                    const karyawanIds = tkbms.map(t => t.karyawan_id);
                    $('#tkbm_id').val(karyawanIds).trigger('change');;
                } else if (modelkerja == 2) {

                    $('#sopir_borongan_id').val($(this).data('nama')).trigger('change');;

                    const tkbms = $(this).data('tkbms');
                    const karyawanIds = tkbms.map(t => t.karyawan_id);
                    $('#tkbm_borongan_id').val(karyawanIds).trigger('change');;

                    $('#tarif_sopir_borongan').val(tarifSopirBorongan)
                    $('#tarif_tkbm_borongan').val(tarifTkbmBorongan)
                }

                $('#tanggal_penjualan').val($(this).data('tanggalpenjualan'));
                $('#periode_id_text').val($(this).data('periode'));
                $('#tanggal_penjualan').prop('disabled', true);
                $('#periode_id_text').prop('disabled', true);
                $('#pabrik_id').val($(this).data('pabrik')).trigger('change');;

                $('#netto').val($(this).data('netto'));
                $('#harga').val($(this).data('harga'));
                $('#uang').val(formatRupiah($(this).data('uang')));
                $('#ops').val(formatRupiah($(this).data('ops')));

                $('#timbangan_first').val($(this).data('timbangan1'));
                $('#timbangan_second').val($(this).data('timbangan2'));
                $('#bruto').val($(this).data('bruto'));
                $('#sortasi').val($(this).data('sortasi'));
            });









            // LOGIC UNTUK TKBM BORONGAN DINAMIS TABEL
            function getSelectedKaryawanIds() {
                const selected = [];
                $('.tkbm_borongan_class').each(function() {
                    const val = $(this).val();
                    if (val) selected.push(parseInt(val));
                });
                return selected;
            }

            function generateOptions(selectedId = null, includeEmpty = true) {
                const selectedIds = getSelectedKaryawanIds().filter(id => id !== selectedId);
                const options = [];

                if (includeEmpty) {
                    options.push(`<option value="">-- Pilih Sopir --</option>`);
                }

                listKaryawans
                    .filter(k => k.type_karyawan_id === 2 || k.type_karyawan_id === 4)
                    .filter(k => !selectedIds.includes(k.id))
                    .forEach(k => {
                        const selected = k.id === selectedId ? 'selected' : '';
                        options.push(`<option value="${k.id}" ${selected}>${k.nama}</option>`);
                    });

                return options.join('');
            }

            function refreshAllSelects() {
                $('.tkbm_borongan_class').each(function() {
                    const currentVal = parseInt($(this).val());
                    const $parent = $(this).parent();

                    $(this).select2('destroy').remove();

                    const selectHtml = `
                <select  class="form-control tkbm_borongan_class" style="width: 100%;">
                    ${generateOptions(currentVal)}
                </select>
            `;

                    $parent.append(selectHtml);
                });

                $('.tkbm_borongan_class').select2({
                    placeholder: "Pilih User",
                    allowClear: true,
                    dropdownParent: $('#modalCreateEdit')
                });

                collectDataBorongan();
            }

            function collectDataBorongan() {
                let dataBorongan = []

                $('#tbody-tkbm-dinamis-borongan tr').each(function() {
                    const karyawanId = $(this).find('.tkbm_borongan_class').val();
                    const tarif = $(this).find('input[type="number"]').val();

                    if (karyawanId) {
                        dataBorongan.push({
                            karyawan_id: parseInt(karyawanId),
                            tarif_borongan: parseInt(tarif) || 0
                        });
                    }
                });

                $('#data_tkbm_dinamis_borongan_json').val(JSON.stringify(dataBorongan));
            }

            function renderTkbmBorongan(dataArray = []) {
                const $tbody = $('#tbody-tkbm-dinamis-borongan');
                $tbody.html('');

                dataArray.forEach(row => {
                    const options = generateOptions(row.karyawan_id, true);

                    const $row = $(`
                <tr class="success">
                    <td>
                        <select  class="form-control tkbm_borongan_class" style="width: 100%;">
                            ${options}
                        </select>
                    </td>
                    <td>
                        <input type="number"  value="${row.tarif_borongan}" class="form-control tkbm_borongan_tarif_class" />
                    </td>
                    <td>
                        <button class="btn btn-danger btn-delete-row-dinamis-borongan"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            `);

                    $tbody.append($row);
                });

                $('.tkbm_borongan_class').select2({
                    placeholder: "Pilih User",
                    allowClear: true,
                    dropdownParent: $('#modalCreateEdit')
                });

                refreshAllSelects();
                collectDataBorongan();
            }

            // Trigger awal (saat edit)
            $(document).ready(function() {
                if (dataBorongan.length > 0) {
                    renderTkbmBorongan(dataBorongan);
                } else {
                    $('.btn-add-row-dinamis-borongan').trigger('click'); // default 1 baris
                }
            });

            // Tambah baris
            $('.btn-add-row-dinamis-borongan').on('click', function(e) {
                e.preventDefault();



                // Ambil tarif dari baris pertama (jika ada)
                let defaultTarif = 0;
                const $firstTarifInput = $('#tbody-tkbm-dinamis-borongan tr:first').find(
                    'input[type="number"]');
                if ($firstTarifInput.length > 0) {
                    const val = parseInt($firstTarifInput.val());
                    if (!isNaN(val)) {
                        defaultTarif = val;
                    }
                }

                const $row = $(`
                                <tr class="success">
                                <td>
                                    <select  class="form-control tkbm_borongan_class" style="width: 100%;">
                                        ${generateOptions(null, true)}
                                    </select>
                                </td>
                                <td>
                                    <input type="number"  value="${defaultTarif}" class="form-control tkbm_borongan_tarif_class" />
                                </td>
                                <td>
                                    <button class="btn btn-danger btn-delete-row-dinamis-borongan"><i class="fa fa-trash"></i></button>
                                </td>
                            </tr>
                            `);

                $('#tbody-tkbm-dinamis-borongan').append($row);

                $row.find('.tkbm_borongan_class').select2({
                    placeholder: "Pilih User",
                    allowClear: true,
                    dropdownParent: $('#modalCreateEdit')
                });

                refreshAllSelects();
            });

            // Hapus baris
            $(document).on('click', '.btn-delete-row-dinamis-borongan', function(e) {
                e.preventDefault();

                const $tbody = $('#tbody-tkbm-dinamis-borongan');
                if ($tbody.find('tr').length > 1) {
                    $(this).closest('tr').remove();
                    refreshAllSelects();
                } else {
                    alert('Minimal 1 baris harus ada.');
                }
            });

            // Update saat input tarif diubah
            $(document).on('input', '.tkbm_borongan_tarif_class', function() {
                collectDataBorongan();
            });

            // Update saat dropdown berubah
            $(document).on('change', '.tkbm_borongan_class', function() {
                refreshAllSelects();
            });

        });
    </script>
@endsection
