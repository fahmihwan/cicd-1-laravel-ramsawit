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
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading ">
                        <div style=" display: flex; justify-content: space-between; align-items: center">
                            <div>
                                Penjualan
                            </div>

                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" id="btn-create"
                                data-target="#modalCreateEdit">
                                <i class="fa fa-plus"></i> Tambah Data
                            </button>
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
                                    <a href="{{ '/penjualan/tbs/' . $menu . '/view' }}" class="btn btn-info btn-sm">
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
                                        {{-- <th>Created at</th> --}}
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr class="">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->formatted_tgl_penjualan }} /
                                                <span class="label label-success">{{ $item->periode->periode }}</span> <br>
                                                <span class="text-info"
                                                    style="font-weight: bold">{{ isset($item->model_kerja->model_kerja) ? $item->model_kerja->model_kerja : '' }}</span>
                                                {{-- <span class=""
                                                    style="color: red">{{ $item->periode->periode }}</span>  --}}
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
                                                        class="label label-warning">{{ $item->tarif_sopir_borongan ?? '' }}</span>
                                                    {{ $item->sopir->nama ?? '-' }}
                                                @endif
                                            </td>
                                            <td>
                                                <div style="display: flex">
                                                    <div style="margin-right: 5px">
                                                        @if ($item->model_kerja_id == 1)
                                                            <span
                                                                class="label label-primary">{{ $item->tarif_tkbm->tarif_perkg ?? '' }}</span>
                                                        @endif
                                                        @if ($item->model_kerja_id == 2)
                                                            <span
                                                                class="label label-warning">{{ $item->tarif_tkbm_borongan ?? '' }}</span>
                                                            {{-- {{ $item->sopir->nama ?? '-' }} --}}
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
                                            <td>{{ $item->netto_formatted }}</td>
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
                                                        data-netto="{{ $item->netto }}" data-harga="{{ $item->harga }}"
                                                        data-uang="{{ $item->uang }}" data-bs-toggle="modal"
                                                        type="button" class="btn btn-warning btn-circle btn-edit"
                                                        data-toggle="modal" data-target="#modalCreateEdit"
                                                        style="margin-right: 5px" data-id="{{ $item->id }}"><i
                                                            class="fa fa-edit"></i>
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
                                    {{ $items->total() }} entries</p>

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


        <!-- Modal CREATE-->
        <div class="modal fade" id="modalCreateEdit" tabindex="-1" role="dialog" aria-labelledby="mymodalCreateEdit"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id='mainForm' role="form" method=POST action={{ '/penjualan/tbs/' . $menu . '/view' }}>
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="mymodalCreateEdit">Pembelian {{ $title }}</h4>
                        </div>


                        <div class="modal-body">
                            <input type="hidden" id="formMethod" name="_method" value="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group "> {{-- has-success, has-warning, has-error --}}
                                        <label>Tgl Penjualan</label>
                                        <input type="date" class="form-control" name="tanggal_penjualan"
                                            value="{{ now()->toDateString() }}" id="tanggal_penjualan" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="form-periode-select">
                                        <label>Periode</label>
                                        <select name="periode_id" id="periode_id_select" class="form-control"
                                            style="width: 100%; ">
                                            <option value="">-- Pilih Periode --</option>
                                            @foreach ($periodes as $p)
                                                <option value="{{ $p->id }}"
                                                    {{ old('periode_id') == $p->id ? 'selected' : '' }}>
                                                    {{ $p->periode }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group" id="form-periode-text">
                                        <label>Peroide</label>
                                        <input class="form-control" name="" value="" id="periode_id_text">
                                    </div>

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pabrik_id">Pilih Pabrik </label><br>
                                        <select name="pabrik_id" id="pabrik_id" class="form-control"
                                            style="width: 100%; height: 200px !important;">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($data_pabrik as $pabrik)
                                                <option value="{{ $pabrik->id }}"
                                                    {{ old('pabrik_id') == $pabrik->id ? 'selected' : '' }}>
                                                    {{ $pabrik->nama_pabrik }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>Harga Pabrik</label>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="number" class="form-control" name="harga"
                                                value="{{ old('harga', 0) }}" id="harga">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group"
                                style="border:1px solid rgb(70, 137, 230); padding: 5px; border-radius: 5px;">
                                <label>Model Kerja</label>
                                <div>
                                    <label class="radio-inline">
                                        <input type="radio" name="model_kerja_id" id="tonase" value="1"
                                            checked style="">Tonase
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="model_kerja_id" id="borongan"
                                            value="2">Borongan
                                    </label>
                                </div>
                            </div>

                            <div class="row">



                            </div>
                            {{-- TARIF TONASE --}}
                            <div id="tarif-tonase">
                                <div class="row">
                                    <div>
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



                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sopir_id">Pilih Sopir</label><br>
                                            <select name="sopir_id" id="sopir_id" class="form-control"
                                                style="width: 100%; height: 200px !important;">
                                                {{-- <option value="">-- Pilih Sopir --</option> --}}
                                                {{-- @foreach ($karyawans as $karyawan)
                                                    @if ($karyawan->type_karyawan == 'SOPIR')
                                                        <option value="{{ $karyawan->id }}">{{ $karyawan->nama }}
                                                        </option>
                                                    @endif
                                                @endforeach --}}
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
                                </div>

                                @php
                                    // Pastikan nilai selalu array
                                    $selectedTkbm = old('tkbm_id');

                                    // Jika tidak ada old(), dan misalnya ini form edit, fallback ke $model->tkbm_ids
                                    if (!is_array($selectedTkbm)) {
                                        $selectedTkbm =
                                            isset($model) && is_array($model->tkbm_ids ?? null) ? $model->tkbm_ids : [];
                                    }
                                @endphp

                                <div class="form-group">
                                    <select name="tkbm_id[]" multiple="multiple" id="tkbm_id" class="form-control"
                                        style="width: 100%; height: 200px !important;">
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







                            {{-- TARIF BORONGAN --}}
                            <div id="tarif-borongan">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group ">
                                            <label>Tarif Sopir</label>
                                            <div class="form-group ">
                                                <input type="number" class="form-control" name="tarif_sopir_borongan"
                                                    {{-- value="{{ old('tarif_sopir_borongan') }}"  --}} value="0" id="tarif_sopir_borongan">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group ">
                                            <label>Tarif TKBM</label>
                                            <div class="form-group ">
                                                <input type="number" class="form-control" name="tarif_tkbm_borongan"
                                                    {{-- value="{{ old('tarif_tkbm_borongan') }}" --}} value="0" id="tarif_tkbm_borongan">
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="sopir_id">Pilih Sopir</label><br>
                                            <select name="sopir_borongan_id" id="sopir_borongan_id" class="form-control"
                                                style="width: 100%; height: 200px !important;">
                                                <option value="">-- Pilih Sopir --</option>
                                                @foreach ($karyawans as $karyawan)
                                                    @if ($karyawan->type_karyawan_id == 1 || $karyawan->type_karyawan_id == 4)
                                                        <option value="{{ $karyawan->id }}">{{ $karyawan->nama }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div>

                                    </div>

                                </div>



                                <div class="form-group">
                                    <label for="tkbm_id">Pilih TKBM</label><br>
                                    <select name="tkbm_borongan_id[]" multiple="multiple" id="tkbm_borongan_id"
                                        class="form-control" style="width: 100%; height: 200px !important;">
                                        {{-- <option value="">-- Pilih TKBM --</option> --}}
                                        @foreach ($karyawans as $karyawan)
                                            @if ($karyawan->type_karyawan_id == 2 || $karyawan->type_karyawan_id == 4)
                                                <option value="{{ $karyawan->id }}">{{ $karyawan->nama }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>Timbangan 1 (kg)</label>
                                        <div class="form-group ">
                                            <input type="number" class="form-control" name="timbangan_first"
                                                value="{{ old('timbangan_first', 0) }}" id="timbangan_first">
                                            {{-- <span class="input-group-addon">Kg</span> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>Timbangan 2 (kg)</label>
                                        <div class="form-group ">
                                            <input type="number" class="form-control" name="timbangan_second"
                                                value="{{ old('timbangan_second', 0) }}" id="timbangan_second">
                                            {{-- <span class="input-group-addon">Kg</span> --}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>Sortasi (%)</label>
                                        <div class="form-group input-group">
                                            <input class="form-control" name="sortasi" value="{{ old('sortasi', 0) }}"
                                                id="sortasi">
                                            <span class="input-group-addon">%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>Bruto</label>
                                        <div class="form-group input-group">
                                            <input class="form-control" type="number" name="bruto"
                                                value="{{ old('bruto') }}" readonly id="bruto">
                                            <span class="input-group-addon">Kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group ">
                                        <label>Netto</label>
                                        <div class="form-group input-group">
                                            <input type="number" class="form-control" name="netto" readonly
                                                value="{{ old('netto') }}" id="netto">
                                            <span class="input-group-addon">Kg</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
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
                                <div class="col-md-6">

                                </div>
                                <div class="col-md-6">
                                    {{-- <div class="form-group ">
                                        <label>Uang</label>
                                        <div class="form-group input-group">
                                            <span class="input-group-addon">Rp</span>
                                            <input type="text" class="form-control" readonly name="uang"
                                                value="{{ old('uang') }}" id="uang">
                                        </div>
                                    </div> --}}
                                </div>
                            </div>






                        </div>
                        <div class="modal-footer">

                            <div style="display: flex; justify-content: space-between; color: red">

                                <div>
                                    <p style="margin: 0;  text-align: left; ">* Tarif <b>Sopir</b> saat ini
                                        Rp.{{ $data_tarif['tarif_sopir_perkg'] }}/kg
                                    </p>
                                    <p style="margin: 0; text-align: left">* Tarif <b>TKBM</b> saat ini
                                        Rp.{{ $data_tarif['tarif_tkbm_perkg'] }}/kg
                                    </p>
                                </div>

                                <div class="">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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


            const dataTarif = @json($data_tarif)

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


            if (dataTarif.tarif_sopir_id == null || dataTarif?.tarif_tkbm_id == null) {
                alert('tarif belum di set')
                window.location.href = '/master/tarif'
            }


            let menu = $('#menu').val()

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

            // if (menu == 'RAM') {
            $(' #timbangan_first, #timbangan_second, #sortasi, #harga').on('input', hitungRam);
            // } else {
            $('#netto, #harga').on('input', hitungRUMAH_LAHAN);
            // }

            function clearModelKerjaGroup() {
                $('#tarif_sopir_id_select').val('').trigger('change');
                $('#tarif_tkbm_id_select').val('').trigger('change');
                $('#sopir_id').val('').trigger('change');
                $('#tkbm_id').val('').trigger('change');
                $('#sopir_borongan_id').val('').trigger('change');
                $('#tkbm_borongan_id').val('').trigger('change');
                $('#tarif_sopir_borongan').val('')
                $('#tarif_tkbm_borongan').val('')
            }



            $('#btn-create').on('click', function() {
                $('#mainForm')[0].reset(); // Kosongkan form
                clearModelKerjaGroup()
                $('#mymodalCreateEdit').text('Tambah TBS ' + menu);
                $('#mainForm').attr('action', '/penjualan/tbs/' + menu + '/view');
                $('#tanggal_penjualan').prop('disabled', false);
                $('#form-periode-select').show()
                $('#form-periode-text').hide()

                $('#formMethod').val('POST')
            });


            $('.btn-edit').on('click', function() {
                let id = $(this).data('id');
                $('#mainForm')[0].reset(); // Kosongkan form
                $('#mymodalCreateEdit').text('Edit TBS ' + menu);
                $('#mainForm').attr('action', '/penjualan/tbs/' + menu + '/view/' + id);
                $('#formMethod').val('PUT')

                $('#form-periode-select').hide()
                $('#form-periode-text').show()




                const tarifSopirId = $(this).data('tarifsopirid');
                const tarifTkbmId = $(this).data('tariftkbmid');
                const tarifSopirText = $(this).data('tarifsopirtext');
                const tarifTkbmText = $(this).data('tariftkbmtext');
                const tarifTkbmBorongan = $(this).data('tariftkbmborongan')
                const tarifSopirBorongan = $(this).data('tarifsopirborongan')



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
                    // if ($('#tarif_sopir_id_select option[value="' + tarifSopirId + '"]').length === 0) {
                    //     $('#tarif_sopir_id_select').append(
                    //         $('<option>', {
                    //             value: tarifSopirId,
                    //             text: tarifSopirText
                    //         })
                    //     );
                    // }
                    // $('#tarif_sopir_id_select').val(tarifSopirId).trigger('change');

                    // if ($('#tarif_tkbm_id_select option[value="' + tarifTkbmId + '"]').length === 0) {
                    //     $('#tarif_tkbm_id_select').append(
                    //         $('<option>', {
                    //             value: tarifTkbmId,
                    //             text: tarifTkbmText
                    //         })
                    //     );
                    // }
                    // $('#tarif_tkbm_id_select').val(tarifTkbmId).trigger('change');

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

                $('#timbangan_first').val($(this).data('timbangan1'));
                $('#timbangan_second').val($(this).data('timbangan2'));
                $('#bruto').val($(this).data('bruto'));
                $('#sortasi').val($(this).data('sortasi'));


            });


        });
    </script>
@endsection
