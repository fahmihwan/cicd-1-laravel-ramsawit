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

                    <div class="panel panel-default">
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
                                        <a href="{{ '/periode' }}" class="btn btn-info btn-sm">
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
                                                {{-- formatted_mulai --}}
                                                <td>{{ $item->periode_awal_formatted }}</td>
                                                {{-- <td>{{ $item->periode_awal }}</td> --}}
                                                {{-- <td>{{ $item->periode_akhir }}</td> --}}
                                                <td>{{ \Carbon\Carbon::parse($item->periode_berakhir)->translatedFormat('d F Y') }}
                                                </td>

                                                <td>
                                                    {{-- @foreach ($item->penggajian_karyawans as $d)
                                                        <a
                                                            href="/penggajian/{{ $item->id }}/{{ $d->id }}/detail-gaji">{{ $d->nama }}</a><br>
                                                    @endforeach --}}
                                                    {{-- <button type="button" class="btn btn-sm btn-primary btn-edit-gaji"
                                                        data-jsonkaryawan="{{ $item->penggajian_karyawans }}"
                                                        data-target="#modalKaryawan" data-bs-toggle="modal">
                                                        Bayar
                                                    </button> --}}


                                                    <button type="button" class="btn btn-primary btn-edit-gaji "
                                                        data-toggle="modal"
                                                        data-jsonkaryawan="{{ $item->penggajian_karyawans }}"
                                                        data-target="#modalKaryawan">
                                                        Bayar
                                                    </button>

                                                </td>
                                                <td>
                                                    <form method="POST" action="/penggajian/{{ $item->id }}">
                                                        @method('delete')
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-danger btn-circle btn-confirm-delete"
                                                            style="margin-right: 5px">
                                                            <i class="fa fa-trash"></i></button>
                                                    </form>
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



            <div class="modal fade" id="modalCreateEdit" tabindex="-1" role="dialog" aria-labelledby="mymodalCreateEdit"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <form id='mainForm' role="form" method=POST action={{ '/penggajian' }}>
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal"
                                    aria-hidden="true">&times;</button>
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
                                            <input class="form-control" name="periode_akhir" type="date"
                                                value="" id="periode_akhir">
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
                            <button type="button" class="close" data-dismiss="modal"
                                aria-hidden="true">&times;</button>
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

            <!-- /.row -->
        </div>
    @endsection

    @section('script')
        <script>
            $(document).ready(function() {

                $('#btn-create').on('click', function() {
                    $('#mainForm')[0].reset();
                    $('#mymodalCreateEdit').text('Tambah periode penggajian ');
                    $('#mainForm').attr('action', '/penggajian');
                    $('#formMethod').val('POST')


                    // $('#periode_awal').prop('readonly', false);
                    // $('#periode_akhir').prop('readonly', true);

                    // $('#ops_id_select').val('').trigger('change');
                });

                $('.btn-edit-gaji').on('click', function() {
                    var jsonkaryawan = $(this).data('jsonkaryawan');
                    console.log(jsonkaryawan);
                    // $("#modal-karyawan-json-data").html("")



                    // id
                    // : 
                    // "487ee51d-fde5-4917-935a-8697fc2b370a"
                    // is_gaji_dibayarkan
                    // : 
                    // null
                    // karyawan
                    // : 
                    // {id: 3, nama: 'FAHMI', main_type_karyawan_id: 2, main_type_karyawan: {…}}
                    // karyawan_id
                    // : 
                    // 3
                    // penggajian_id
                    // : 
                    // "43405ed0-297e-4512-97f2-9a36792ff5f3"
                    // total_gaji
                    // : 
                    // 171123
                    function formatRupiah(angka, withPrefix = true) {
                        let formatted = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                            minimumFractionDigits: 0
                        }).format(angka);

                        return withPrefix ? formatted : formatted.replace('Rp', '').trim();
                    }


                    let htmlJson = ""
                    jsonkaryawan.forEach((e, i) => {

                        htmlJson += ` <tr>
                                        <td>${i+1}</td>
                                        <td>${e.karyawan.nama}</td>
                                        <td>${e.karyawan.main_type_karyawan?.type_karyawan}</td>
                                        <td>
                                            ${e.is_gaji_dibayarkan == true ? `<i class="fa fa-fw" aria-hidden="true"
                                                                                                                                                                                                                                                                                                                                                                                                                style="font-size: 25px; color: rgb(35, 187, 35)"
                                                                                                                                                                                                                                                                                                                                                                                                                title="Copy to use check-square">&#xf14a</i>` : 'belum'}
                                            
                                            </td>
                                        
                                        <td>
                                            <a href="/penggajian/${e?.penggajian_id}/${e?.karyawan_id}/detail-gaji" class="btn btn-primary">cek</a>
                                        </td>
                                    </tr>`
                    });


                    $("#modal-karyawan-json-data").html(htmlJson)


                    // var nama = $(this).data('nama');
                    // var total = $(this).data('total');
                    // var isdibayar = $(this).data('isdibayar');

                    // $('#input_id_gaji').val(id);
                    // $('#modal_nama').text(nama);
                    // $('#modal_total').text(Number(total).toLocaleString('id-ID'));
                    // $('#modal_cek').prop('checked', isdibayar == 1);
                });


                $('.btn-edit').on('click', function() {
                    let id = $(this).data('id');
                    $('#mainForm')[0].reset();
                    $('#mymodalCreateEdit').text('Tutup Periode');
                    $('#mainForm').attr('action', '/penggajian/' + id);
                    $('#formMethod').val('PUT')


                    // $('#periodeke').prop('readonly', true)
                    // $('#periode_akhir').prop('readonly', false);

                    // const ops = $(this).data('ops')
                    // const idops = $(this).data('idops')

                    // if ($('#ops_id_select option[value="' + idops + '"]').length === 0) {
                    //     $('#ops_id_select').append(
                    //         $('<option>', {
                    //             value: idops,
                    //             text: ops
                    //         })
                    //     );
                    // }


                    // $('#ops_id_select').val(idops).trigger('change');

                    // $('#periodeke').val($(this).data('periode'))
                    // $('#periode_awal').val($(this).data('mulai'))
                    // $('#periode_akhir').val($(this).data('berakhir'))

                });


            });
        </script>
    @endsection
