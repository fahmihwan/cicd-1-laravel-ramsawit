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
                <h1 class="page-header">Pinjaman</h1>
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
                    {{-- <div class="panel-heading ">
                        <div style=" display: flex; justify-content: space-between; align-items: center">
                            <div>
                                Stok periode
                            </div>

                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#modalCreateEdit">
                                <i class="fa fa-plus"></i> Tambah Data
                            </button>
                        </div>
                    </div> --}}


                    {{-- <div class="panel-heading ">
                            <div style=" display: flex; justify-content: space-between; align-items: center">
                                <div>
                                    Master TBS
                                </div>

                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" id="btn-create"
                                    data-target="#modalCreateEdit">
                                    <i class="fa fa-plus"></i> Tambah Data
                                </button>
                            </div>
                        </div> --}}

                    <div class="panel-heading ">
                        <div style=" display: flex; justify-content: space-between; align-items: center">
                            <div>
                                Pinjaman
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
                                    <a href="{{ '/pinjaman' }}" class="btn btn-info btn-sm">
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
                                        <th>Tanggal</th>
                                        <th>Karyawan</th>
                                        <th>Pekerjaan utama</th>
                                        <th>Nominal Peminjaman</th>
                                        <th>Nominal Pengembalian</th>
                                        <th>Keterangan</th>
                                        <th>Created At</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr class="">
                                            <td>{{ $loop->iteration }}</td>
                                            {{-- <span class="label label-info">Info</span> --}}
                                            <td>{{ $item->tanggal_formatted }}</td>
                                            <td>{{ $item->karyawan->nama }}</td>
                                            <td>{{ $item->karyawan->main_type_karyawan->type_karyawan }}</td>
                                            {{-- <td>{{ $item->nominal_peminjaman }}</td>
                                                <td>{{ $item->nominal_pengembalian }}</td> --}}
                                            <td>{{ $item->nominal_peminjaman_formatted }}</td>
                                            <td>{{ $item->nominal_pengembalian_formatted }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                            <td class="center">{{ $item->formatted_created_at }}</td>
                                            <td style="display: flex; border-bottom: 1px">
                                                @if (@empty($item->penggajian_karyawan_id))
                                                    <button type="button" class="btn btn-warning btn-circle btn-edit"
                                                        style="margin-right: 10px" data-toggle="modal"
                                                        data-target="#modalCreateEdit" data-id="{{ $item->id }}"
                                                        data-tanggal="{{ $item->tanggal }}"
                                                        data-nama="{{ $item->karyawan->nama }}"
                                                        data-karyawanid="{{ $item->karyawan_id }}"
                                                        data-maintypekaryawanid="{{ $item->karyawan->main_type_karyawan_id }}"
                                                        data-nominalpinjam="{{ $item->nominal_peminjaman }}"
                                                        data-nominalkembali="{{ $item->nominal_pengembalian }}"
                                                        data-keterangan="{{ $item->keterangan }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>


                                                    @if ($item->periode_berakhir != null)
                                                        <button data-bs-toggle="modal" type="button"
                                                            class="btn  btn-circle btn-edit"
                                                            style="background-color: gray; color:white"
                                                            onclick="alert('Periode sudah ditutup');">
                                                            <i class="fa fa-lock"></i>
                                                        </button>
                                                    @else
                                                        <form method="POST" action="/pinjaman/{{ $item->id }}">
                                                            @method('delete')
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-danger btn-circle btn-confirm-delete "
                                                                style="margin-right: 5px">
                                                                <i class="fa fa-trash"></i></button>
                                                        </form>
                                                    @endif
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

                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading" style="display: flex; justify-content: space-between">
                        <span>Sisa Pinjaman Karyawan</span>

                        <b class="label label-danger" style="display: flex; align-items: center">
                            Total : Rp
                            {{ number_format($list_sisa_pinjaman->whereIn('main_type_karyawan_id', [1, 2])->sum('sisa_pinjaman'), 0, ',', '.') }}
                        </b>
                    </div>

                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover"
                                id="dataTables-sisapinjamankaryawan">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th>Pekerjaan utama</th>
                                        <th>sisa pinjaman</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_sisa_pinjaman as $item)
                                        @if ($item->main_type_karyawan_id != 3)
                                            <tr>
                                                <td>{{ $item->nama }}</td>
                                                <td>{{ $item->type_karyawan }}</td>
                                                <td>{{ $item->sisa_pinjaman_formatted }}</td>
                                            </tr>
                                        @endif
                                    @endforeach



                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="panel panel-default">

                    <div class="panel-heading" style="display: flex; justify-content: space-between">
                        <span>Sisa Pinjaman Selain karyawan (petani)</span>
                        <b class="label label-danger" style="display: flex; align-items: center">
                            Total : Rp
                            {{ number_format($list_sisa_pinjaman->whereIn('main_type_karyawan_id', [3])->sum('sisa_pinjaman'), 0, ',', '.') }}
                        </b>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover"
                                id="dataTables-sisapinjamanpetani">
                                <thead>
                                    <tr>
                                        <th>Nama</th>

                                        <th>sisa pinjaman</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_sisa_pinjaman as $item)
                                        @if ($item->main_type_karyawan_id == 3)
                                            <tr>
                                                <td>{{ $item->nama }}</td>

                                                <td>{{ $item->sisa_pinjaman_formatted }}</td>
                                            </tr>
                                        @endif
                                    @endforeach


                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-lg-6"></div>

            <div class="col-lg-6">
                <div class="panel panel-default">
                    <div class="panel-heading ">
                        <div style=" display: flex; justify-content: space-between; align-items: center">
                            <div>
                                Master Data (selain karyawan atau petani)
                            </div>

                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#modalCreatePetani" id="create-petani">
                                <i class="fa fa-user-plus"></i> Tambah Data
                            </button>
                        </div>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        {{-- <th>Pekerjaan Utama</th> --}}
                                        {{-- <th>List Pekerjaan</th> --}}
                                        <th>Created at</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i = 1;
                                    @endphp
                                    @foreach ($karyawans as $data)
                                        @if ($data->main_type_karyawan_id == 3)
                                            <tr class="">
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $data->nama }}</td>
                                                {{-- <td>{{ $data->main_type_karyawan->type_karyawan }}</td> --}}

                                                <td class="center">{{ $data->formatted_created_at }}</td>
                                                <td class="center" style="display: flex">
                                                    <button type="button"
                                                        class="btn btn-warning btn-circle btn-edit-petani"
                                                        data-toggle="modal" data-target="#modalCreatePetani"
                                                        style="margin-right: 5px" data-id="{{ $data->id }}"
                                                        data-nama="{{ $data->nama }}" {{-- data-typekaryawan="{{ $data->jobs }}" --}}
                                                        data-maintypekaryawan="{{ $data->main_type_karyawan_id }}">

                                                        <i class="fa fa-edit"></i>
                                                    </button>

                                                    <form id="form-delete-{{ $data->id }}" method="POST"
                                                        action="/master/karyawan/{{ $data->id }}">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-danger btn-circle  btn-confirm-delete">
                                                            <i class="fa fa-trash"></i></button>
                                                    </form>


                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>





        <div class="modal fade" id="modalCreateEdit" tabindex="-1" role="dialog" aria-labelledby="mymodalCreateEdit"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id='mainForm' role="form" method=POST action={{ '/pinjaman' }}>
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="mymodalCreateEdit">Periode</h4>
                        </div>


                        <div class="modal-body">
                            <input type="hidden" id="formMethod" name="_method" value="POST">
                            @csrf

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Tanggal</label>
                                        <input class="form-control" name="tanggal" type="date"
                                            value="{{ now()->toDateString() }}" id="tanggal">
                                    </div>
                                </div>
                            </div>
                            <div class="row" id="option-radio">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <table>
                                            <tr>
                                                <th style="width: 150px; height: 30px;">Jenis Pihak</th>
                                                <td style="width: 120px">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="pihak" id="pihak"
                                                            value="karyawan">Karyawan
                                                    </label>
                                                </td>
                                                <td style=""><label class="radio-inline">
                                                        <input type="radio" name="pihak" id="pihak"
                                                            value="lainnya">Selain karyawan (petani)
                                                    </label>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th style="width: 150px; height: 30px;">Jenis Transaksi</th>
                                                <td style="width: 120px">
                                                    <label class="radio-inline">
                                                        <input type="radio" name="transaksi" id="transaksi"
                                                            value="peminjaman">Peminjaman
                                                    </label>
                                                </td>
                                                <td style=""><label class="radio-inline">
                                                        <input type="radio" name="transaksi" id="transaksi"
                                                            value="pengembalian">Pengembalian
                                                    </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="karyawan_id">Pilih Karyawan </label><br>
                                        <select name="karyawan_id" id="karyawan_id" class="form-control" disabled
                                            style="width: 100%; !important;">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($karyawans as $k)
                                                @if ($k->main_type_karyawan_id == 1 || $k->main_type_karyawan_id == 2)
                                                    <option value="{{ $k->id }}"
                                                        {{ old('karyawan_id') == $k->id ? 'selected' : '' }}>
                                                        {{ $k->nama }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <input type="hidden" value="" id="karyawan_hidden_id" name="karyawan_id">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="selain_karyawan_id">Selain karyawan (petani) </label><br>
                                        <select name="selain_karyawan_id" id="selain_karyawan_id" disabled
                                            class="form-control" style="width: 100%; !important;">
                                            <option value="">-- Pilih --</option>
                                            @foreach ($karyawans as $k)
                                                @if ($k->main_type_karyawan_id == 3)
                                                    <option value="{{ $k->id }}"
                                                        {{ old('selain_karyawan_id') == $k->id ? 'selected' : '' }}>
                                                        {{ $k->nama }}</option>
                                                @endif
                                            @endforeach
                                        </select>

                                        <input type="hidden" value="" id="selain_karyawan_hidden_id"
                                            name="selain_karyawan_id">
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>Nominal Peminjaman</label>
                                        <input class="form-control" name="nominal_peminjaman" type="number" disabled
                                            value="{{ old('nominal_peminjaman', 0) }}" id="nominal_peminjaman">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <label>Nominal Pengembalian</label>
                                        <input class="form-control" name="nominal_pengembalian" type="number" disabled
                                            value="{{ old('nominal_pengembalian', 0) }}" id="nominal_pengembalian">
                                    </div>
                                </div>

                            </div>

                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea class="form-control" name="keterangan" id="keterangan" rows="3">{{ old('keterangan') }}</textarea>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <div class="">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </div>


                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>




        <div class="modal fade" id="modalCreatePetani" tabindex="-1" role="dialog"
            aria-labelledby="myModalCreatePetani" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="mainFormPetani" role="form" method="POST" action="/master/karyawan">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalCreatePetani">Tambah selain karyawan (petani)</h4>
                        </div>
                        <div class="modal-body">

                            <input type="hidden" id="formMethodPetani" name="_method" value="POST">
                            @csrf

                            <input type="hidden" class="form-control" name="main_type_karyawan"
                                id="main_type_karyawan_id" value="3">
                            <input type="hidden" class="form-control" name="type_karyawan[]" value="3"
                                id='type_karyawan_id'>


                            <div class="form-group">
                                <label>Nama</label>
                                <input class="form-control" name="nama" id="nama_petani"
                                    value="{{ old('nama') }}">
                            </div>


                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


    @endsection

    @section('script')
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
        <script>
            $(document).ready(function() {

                $('#dataTables-sisapinjamanpetani').DataTable({
                    responsive: true
                });

                $('#dataTables-sisapinjamankaryawan').DataTable({
                    responsive: true
                });

                $('#karyawan_id').select2({
                    placeholder: "Pilih User",
                    allowClear: true,
                    dropdownParent: $('#modalCreateEdit')
                });
                $('#selain_karyawan_id').select2({
                    placeholder: "Pilih User",
                    allowClear: true,
                    dropdownParent: $('#modalCreateEdit')
                });




                // Event ketika radio button diklik
                $('input[name="pihak"]').on('change', function() {
                    const selected = $('input[name="pihak"]:checked').val();

                    if (selected === 'karyawan') {
                        $('#karyawan_id').prop('disabled', false);
                        $('#selain_karyawan_id').prop('disabled', true);

                    } else if (selected === 'lainnya') {
                        $('#karyawan_id').prop('disabled', true);
                        $('#selain_karyawan_id').prop('disabled', false);
                        $("#selain_karyawan_text_id").prop('disabled', false)
                    }
                });

                $('input[name="transaksi"]').on('change', function() {
                    const selected = $('input[name="transaksi"]:checked').val();
                    if (selected === 'peminjaman') {
                        $('#nominal_peminjaman').prop('disabled', false);
                        $('#nominal_pengembalian').prop('disabled', true);
                    } else if (selected === 'pengembalian') {
                        $('#nominal_peminjaman').prop('disabled', true);
                        $('#nominal_pengembalian').prop('disabled', false);
                    }
                });


                $('#btn-create').on('click', function() {
                    $('#mainForm')[0].reset();
                    $('#mymodalCreateEdit').text('Buat peminjaman');
                    $('#mainForm').attr('action', '/pinjaman');
                    $('#formMethod').val('POST')
                    $('#option-radio').show()

                    $('#karyawan_hidden_id').prop('disabled', true);
                    $('#selain_karyawan_hidden_id').prop('disabled', true);

                    $('#selain_karyawan_id').val(null).trigger('change');
                    $('#karyawan_id').val(null).trigger('change');

                    $('#periode_mulai').prop('readonly', false);
                    $('#periode_berakhir').prop('readonly', true);
                });


                $('.btn-edit').on('click', function() {
                    let id = $(this).data('id');
                    $('#mainForm')[0].reset();
                    $('#mymodalCreateEdit').text('Ubah peminjaman');
                    $('#mainForm').attr('action', '/pinjaman/' + id);
                    $('#formMethod').val('PUT')

                    $('#tanggal').val($(this).data('tanggal'))
                    $('#nominal_peminjaman').val($(this).data('nominalpinjam'))
                    $('#nominal_pengembalian').val($(this).data('nominalkembali'))
                    $('#keterangan').val($(this).data('keterangan'))

                    $('#nominal_peminjaman').prop('disabled', false);
                    $('#nominal_pengembalian').prop('disabled', false);

                    $('#option-radio').hide()
                    $('#karyawan_hidden_id').prop('disabled', false);
                    $('#selain_karyawan_hidden_id').prop('disabled', false);

                    const karyawan_id = $(this).data('karyawanid')
                    const karyawan_nama = $(this).data('nama')
                    const maintypekaryawanid = $(this).data('maintypekaryawanid')


                    if (maintypekaryawanid == 3) {
                        if ($('#selain_karyawan_id option[value="' + karyawan_id + '"]').length === 0) {
                            $('#selain_karyawan_id').append(
                                $('<option>', {
                                    value: karyawan_id,
                                    text: karyawan_nama
                                })
                            );
                        }
                        $('#selain_karyawan_id').val(karyawan_id).trigger('change');
                        $('#selain_karyawan_hidden_id').val(karyawan_id);

                    } else {
                        if ($('#karyawan_id option[value="' + karyawan_id + '"]').length === 0) {
                            $('#karyawan_id').append(
                                $('<option>', {
                                    value: karyawan_id,
                                    text: karyawan_nama
                                })
                            );
                        }
                        $('#karyawan_id').val(karyawan_id).trigger('change');
                        $('#karyawan_hidden_id').val(karyawan_id);
                    }


                });


                $('#create-petani').on('click', function() {
                    $('#mainFormPetani')[0].reset(); // Kosongkan form
                    $('#mymodalCreateEdit').text('Tambah master data selain karyawan (petani) ');
                    $('#mainFormPetani').attr('action', '/master/karyawan');
                    $('#formMethodPetani').val('POST')

                })

                $('.btn-edit-petani').on('click', function() {
                    $('#mainFormPetani')[0].reset(); // Kosongkan form
                    const id = $(this).data('id')
                    $('#mymodalCreateEdit').text('Edit master data selain karyawan (petani) ');
                    $('#mainFormPetani').attr('action', '/master/karyawan/' + id);
                    $('#formMethodPetani').val('PUT')

                    // 

                    $('#nama_petani').val($(this).data('nama'))
                    $('#main_type_karyawan_id').val(3)
                    $('#type_karyawan_id').val([3])

                })
            });
        </script>
    @endsection
