@extends('layouts.main')

@section('container')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Pembelian TBS {{ $menu }} tiap periode</h1>
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


                    <div class="panel panel-default">

                        <div class="panel-heading ">
                            <div style=" display: flex; justify-content: space-between; align-items: center">
                                <div>
                                    list pembelian tiap periode
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
                                        <a href="{{ '/pembelian/tbs/' . request('menu') . '/periode' }}"
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
                                            <th>Periode</th>
                                            <th>Periode Mulai</th>
                                            <th>Periode Berakhir</th>
                                            <th>Total Netto</th>
                                            <th>Created At</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($items as $item)
                                            <tr class="">
                                                <td>{{ $loop->iteration }}</td>
                                                {{-- <span class="label label-info">Info</span> --}}
                                                <td>{{ $item->label_periode }}</td>
                                                <td>{{ $item->formatted_mulai }}</td>
                                                <td>
                                                    @if ($item->formatted_berakhir == null)
                                                        <span class="label label-success" style="font-size: 12px">Periode
                                                            masih berjalan</span>
                                                    @else
                                                        {{ $item->formatted_berakhir }}
                                                    @endif
                                                </td>
                                                <td>{{ number_format($item->total_netto, 0, ',', '.') }}</td>

                                                <td class="center">{{ $item->formatted_created_at }}</td>
                                                <td style="display: flex; border-bottom: 1px">

                                                    {{-- <button type="button" class="btn btn-sm  btn-warning btn-edit"
                                                        style="margin-right: 10px" data-id="{{ $item->id }}"
                                                        data-periodeakhir={{ $item->periode_berakhir }}
                                                        data-periodemulai={{ $item->periode_mulai }} data-bs-toggle="modal"
                                                        data-toggle="modal" data-target="#modalCreateEdit"
                                                        type="button">TUTUP PERIODE</button> --}}

                                                    <a href="/pembelian/tbs/{{ $menu }}/{{ $item->id }}/view"
                                                        class="btn btn-primary  btn-edit">
                                                        <i class="fa  fa-cart-arrow-down"></i>
                                                    </a>
                                                    {{-- @if ($item->periode_berakhir != null)
                                                        <button data-bs-toggle="modal" type="button"
                                                            class="btn  btn-circle btn-edit"
                                                            style="background-color: gray; color:white"
                                                            onclick="alert('Periode sudah ditutup');">
                                                            <i class="fa fa-lock"></i>
                                                        </button>
                                                    @endif --}}

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
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>





            <!-- /.row -->
        </div>
    @endsection

    @section('script')
        <script>
            $(document).ready(function() {

                $('#btn-create').on('click', function() {
                    $('#mainForm')[0].reset();
                    $('#mymodalCreateEdit').text('Buat periode baru ');
                    $('#mainForm').attr('action', '/periode');
                    $('#formMethod').val('POST')


                    $('#periode_mulai').prop('readonly', false);
                    $('#periode_berakhir').prop('readonly', true);

                });


                $('.btn-edit').on('click', function() {
                    let id = $(this).data('id');
                    $('#mainForm')[0].reset();
                    $('#mymodalCreateEdit').text('Tutup Periode');
                    $('#mainForm').attr('action', '/periode/' + id);
                    $('#formMethod').val('PUT')

                    $('#periode_mulai').prop('readonly', true);
                    $('#periode_berakhir').prop('readonly', false);


                    $('#periode_mulai').val($(this).data('periodemulai'))
                    $('#periode_berakhir').val($(this).data('periodeakhir'))




                    // const tkbms = $(this).data('tkbms');
                    // const karyawanIds = tkbms.map(t => t.karyawan_id);


                    // $('#pabrik_id').val($(this).data('pabrik')).trigger('change');;
                    // $('#sopir_id').val($(this).data('nama')).trigger('change');;
                    // $('#tkbm_id').val(karyawanIds).trigger('change');;


                    // $('#netto').val($(this).data('netto'));
                    // $('#harga').val($(this).data('harga'));
                    // $('#uang').val($(this).data('uang'));

                    // $('#timbangan_first').val($(this).data('timbangan1'));
                    // $('#timbangan_second').val($(this).data('timbangan2'));
                    // $('#bruto').val($(this).data('bruto'));
                    // $('#sortasi').val($(this).data('sortasi'));
                });


            });
        </script>
    @endsection
