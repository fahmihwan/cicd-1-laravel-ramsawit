@extends('layouts.main')

@section('container')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Penggajian</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        {{-- <div class="row">
            @if ($errors->any())
                <div style="color:red;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div> --}}
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">

                    <div class="panel-heading ">
                        <div style=" display: flex; justify-content: space-between; align-items: center">
                            <div>
                                Stok periode
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
                                    <a href="{{ '/penggajian' }}" class="btn btn-info btn-sm">
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
                                        <th>Periode mulai penggajian</th>
                                        <th>Periode berakhir penggajian</th>
                                        <th>Karyawan</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr class="">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->periode_awal_formatted }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->periode_akhir)->translatedFormat('d F Y') }}
                                            </td>


                                            <td>


                                                <button type="button" class="btn btn-primary btn-bayar "
                                                    data-toggle="modal" data-penggajianid="{{ $item->id }}"
                                                    data-target="#modalKaryawan">
                                                    Bayar
                                                </button>

                                            </td>
                                            <td>
                                                <div style="display: flex">
                                                    <button type="button" class="btn btn-warning btn-circle btn-edit"
                                                        style="margin-right: 5px" data-id="{{ $item->id }}"
                                                        data-periode_awal="{{ $item->periode_awal }}"
                                                        data-periode_akhir="{{ $item->periode_akhir }}">
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <form method="POST" action="/penggajian/{{ $item->id }}">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-danger btn-circle btn-confirm-delete"
                                                            style="margin-right: 5px">
                                                            <i class="fa fa-trash"></i></button>
                                                    </form>

                                                </div>
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
            </div>

        </div>


        <div class="row">
            <div class="col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        Keterangan
                    </div>
                    <div class="panel-body">
                        <ul>
                            <li>Jika terjadi lupa input TKBM atau SOPIR belum diinput, sehingga menyebabkan gaji karyawan
                                tidak
                                tercatat, maka Lakukan unchecklist "<i class="fa fa-check"></i>" pada bagian <b
                                    style="color: red">Konfirmasi
                                    bahwa gaji telah dibayarkan</b> pada data MENU AMBIL GAJI supaya bisa menyisipkan
                                karyawan ke
                                dalam
                                penjualan.
                            </li>
                            <li>Data penggajian tidak dapat dihapus jika terdapat satu atau lebih karyawan yang sudah
                                dibayarkan gajinya. atau jika tetap ingin menghapus maka Lakukan unchecklist "<i
                                    class="fa fa-check"></i>" pada bagian <b style="color: red">Konfirmasi
                                    bahwa gaji telah dibayarkan</b> </li>
                        </ul>
                    </div>
                    <div class="panel-footer">
                        Readme
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" id="modalCreateEdit" tabindex="-1" role="dialog" aria-labelledby="mymodalCreateEdit"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id='mainForm' role="form" method=POST action={{ '/penggajian' }}>
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="mymodalCreateEdit">Penggajian</h4>
                        </div>


                        <div class="modal-body">
                            <input type="hidden" id="formMethod" name="_method" value="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Periode mulai penggajian</label>
                                        <input class="form-control" name="periode_awal" type="date" value=""
                                            {{-- value="{{ now()->toDateString() }}" --}} id="periode_awal">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Periode berakhir penggajian</label>
                                        <input class="form-control" name="periode_akhir" type="date" value=""
                                            id="periode_akhir">
                                    </div>
                                </div>
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
        <!-- /.row -->



        <!-- Modal -->
        <div class="modal fade " id="modalKaryawan" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true ">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Data karyawan
                        </h4>
                    </div>
                    <div class="modal-body">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Karyawan</th>
                                    <th>Pekerjaan utama</th>
                                    <th>sudah dibayar?</th>
                                    {{-- <th>Total Gaji</th> --}}
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody id="modal-karyawan-json-data">


                            </tbody>
                        </table>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    @endsection

    @section('script')
        <script>
            $(document).ready(function() {

                $('#btn-create').on('click', function() {
                    $('#mainForm')[0].reset();
                    $('#mymodalCreateEdit').text('Tambah periode penggajian ');
                    $('#mainForm')[0].reset();
                    $('#mainForm').attr('action', '/penggajian');
                    $('#formMethod').val('POST')
                });

                // Tombol Edit
                $('.btn-edit').on('click', function() {
                    const id = $(this).data('id');
                    const periodeAwal = $(this).data('periode_awal');
                    const periodeAkhir = $(this).data('periode_akhir');

                    console.log(periodeAwal);
                    console.log(periodeAkhir);

                    $('#mainForm').attr('action', '/penggajian/' + id);
                    $('#formMethod').val('PUT');
                    $('#periode_awal').val(periodeAwal);
                    $('#periode_akhir').val(periodeAkhir);

                    $('#mymodalCreateEdit').text('Edit Penggajian');
                    $('#modalCreateEdit').modal('show');
                });





                $('.btn-bayar').on('click', function() {
                    var penggajianid = $(this).data('penggajianid');
                    console.log(penggajianid);
                    $.ajax({
                        url: '/penggajian/' + penggajianid,
                        method: 'GET',
                        success: function(response) {

                            let htmlJson = ""
                            response.forEach((e, i) => {
                                htmlJson += ` <tr>
                                <td>${i+1}</td>
                                <td>${e.nama}</td>
                                <td>${e.type_karyawan}</td>
                                <td>${e.is_gaji_dibayarkan ? `<i class="fa fa-check-square fa-2x"></i>` : `<i class="fa fa-square fa-2x"></i>`}</td>
                                <td>
                                    <a href="/penggajian/${e?.penggajian_id}/${e?.karyawan_id}/detail-gaji" class="btn btn-primary">cek</a>
                                </td>
                            </tr>`
                            });
                            $("#modal-karyawan-json-data").html(htmlJson)
                        },
                        error: function(xhr) {
                            alert('Gagal ambil data: ' + xhr.status);
                        }
                    });

                });
            });
        </script>
    @endsection
