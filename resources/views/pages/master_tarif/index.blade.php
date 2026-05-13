@extends('layouts.main')

@section('container')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Master Tarif</h1>
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
                                Master Tarif
                            </div>

                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#modalCreate">
                                <i class="fa fa-plus"></i> Tambah Data
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
                                        <th>Kategori Karyawan</th>
                                        <th>Tarif per KG</th>
                                        <th>Created at</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr class="">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->type_karyawan->type_karyawan }}</td>
                                            <td>Rp {{ $item->tarif_perkg }}</td>

                                           <td class="center">{{ $item->formatted_created_at }}</td>

                                            {{-- <button type="submit"
                                                class="font-medium text-red-600  hover:underline ml-3">Hapus</button> --}}

                                            <td class="center" style="display: flex">
                                                <form method="POST" action="/master/tarif/{{ $item->id }}">
                                                    @method('delete')
                                                    @csrf
                                                    <button type="submit"
                                                        class="btn btn-danger btn-circle btn-confirm-delete"
                                                        style="margin-right: 5px">
                                                        <i class="fa fa-trash"></i></button>
                                                </form>


                                                {{-- <button type="button" class="btn btn-warning btn-circle btn-edit"
                                                    data-toggle="modal" data-target="#modalEdit"
                                                    data-id="{{ $item->id }}" data-tarifperkg="{{ $item->tarif_perkg }}"
                                                    data-typekaryawan="{{ $item->type_karyawan }}">
                                                    <i class="fa fa-edit"></i>
                                                </button> --}}
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
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

        {{-- <div class="row">
            <div class="col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        Peringatan!
                    </div>
                    <div class="panel-body">

                        <ul>
                            <li>Tarif digunakan untuk penggajian karyawan.</li>
                            <li>Pastikan <b>Tarif Aktif</b> untuk SOPIR dan TKBM tersedia "<i class="fa fa-check"></i>"
                                sebelum melakukan proses
                                <i>penjualan</i>.
                            </li>
                            <li>Jika Anda menambahkan data baru, maka data tersebut akan menjadi <b>Tarif Aktif</b>. sesudi
                                Kategori Karyawan</li>
                            <li>Jika Anda menghapus data <b>Tarif Aktif</b>, maka untuk transaksi penjualan
                                selanjutnya akan mengambil data <b>Tarif Aktif</b> dengan tanggal <i>created_at</i> dari
                                data sebelumnya.
                            </li>
                        </ul>
                    </div>
                    <div class="panel-footer">
                        Readme
                    </div>
                </div>
                <!-- /.col-lg-4 -->
            </div>
        </div> --}}


        <!-- Modal CREATE-->
        <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="myModalCreate"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form role="form" method="POST" action="/master/tarif">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalCreate">Tambah tarif</h4>
                        </div>
                        <div class="modal-body">


                            @method('POST')
                            @csrf
                            <div class="form-group "> {{-- has-success, has-warning, has-error --}}
                                <label>Tarif per KG</label>
                                <input type="number" class="form-control" name="tarif_perkg"
                                    value="{{ old('tarif_perkg') }}">
                            </div>


                            <div class="form-group">
                                <label>Kategori</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="type_karyawan_id" id="modal-type1" value="2">TKBM
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="type_karyawan_id" id="modal-type2" value="1">Sopir
                                    </label>
                                </div>
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


        <!-- /.row -->
        <!-- Modal EDIT-->
        <div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalEdit"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form role="form" method="POST" id="form-edit">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalEdit">Ubah Tarif</h4>
                        </div>
                        <div class="modal-body">


                            @method('PUT')
                            @csrf
                            <div class="form-group "> {{-- has-success, has-warning, has-error --}}
                                <label>Tarif per KG</label>
                                <input class="form-control" name="tarif_perkg" id="modal-tarifperkg">
                            </div>


                            <div class="form-group">
                                <label>Kategori</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="type_karyawan_id" id="modal-type1"
                                            value="1">TKBM
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="type_karyawan_id" id="modal-type2"
                                            value="2">Sopir
                                    </label>
                                </div>
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

        <!-- /.row -->


        <!-- /.row -->
    </div>
@endsection

@section('script')
    {{-- <script src="{{ asset('/js/jquery.min.js') }}"></script> --}}

@section('script')
    <script>
        $(document).ready(function() {

            $('.btn-edit').on('click', function() {
                var userId = $(this).data('id');
                var nama = $(this).data('tarifperkg');
                var type = $(this).data('typekaryawan');

                $('#form-edit').attr('action', '/master/tarif/' + userId);
                // Isi field
                $('#modal-tarifperkg').val(nama);
                $("input[name='type_karyawan'][value='" + type + "']").prop('checked', true);
            });
        });
    </script>
@endsection
@endsection
