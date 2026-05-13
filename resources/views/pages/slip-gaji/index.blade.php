@extends('layouts.main')

@section('container')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Slip Gaji</h1>
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
                                Master Karyawan
                            </div>

                            {{-- <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                data-target="#modalCreate">
                                <i class="fa fa-user-plus"></i> Tambah Data
                            </button> --}}
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
                                        <th>Kategori</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr class="">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->type_karyawan }}</td>


                                            {{-- <button type="submit"
                                                class="font-medium text-red-600  hover:underline ml-3">Hapus</button> --}}

                                            <td class="center" style="display: flex">
                                                <a href="/slipgaji/karyawan/{{ $item->id }}?bulan={{ request('bulan') ?? now()->format('m') }}&tahun={{ request('tahun') ?? now()->format('Y') }}"
                                                    class="btn btn-info  btn-edit">Detail</a>

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



        <!-- Modal CREATE-->
        <div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="myModalCreate"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form role="form" method="POST" action="/master/karyawan">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalCreate">Tambah Karyawan</h4>
                        </div>
                        <div class="modal-body">


                            @method('POST')
                            @csrf
                            <div class="form-group "> {{-- has-success, has-warning, has-error --}}
                                <label>Nama</label>
                                <input class="form-control" name="nama" value="{{ old('nama') }}">
                                {{-- <p class="help-block">Example block-level help text here.</p> --}}
                            </div>

                            <div class="form-group">
                                <label>Kategori</label>
                                {{-- <div class="radio">
                                <label>
                                    <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1" checked>Radio 1
                                </label>
                            </div> --}}
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="type_karyawan" id="optionsRadios2" value="TKBM">TKBM
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="type_karyawan" id="optionsRadios3" value="SOPIR">Sopir
                                    </label>
                                </div>
                                {{-- <select id="status" name="status" required>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select><br><br> --}}
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
                            <h4 class="modal-title" id="myModalEdit">Ubah Karyawan</h4>
                        </div>
                        <div class="modal-body">


                            @method('PUT')
                            @csrf
                            <div class="form-group "> {{-- has-success, has-warning, has-error --}}
                                <label>Nama</label>
                                <input class="form-control" name="nama" id="modal-nama">
                                {{-- <p class="help-block">Example block-level help text here.</p> --}}
                            </div>

                            <div class="form-group">
                                <label>Kategori</label>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="type_karyawan" id="modal-type1" value="TKBM">TKBM
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input type="radio" name="type_karyawan" id="modal-type2" value="SOPIR">Sopir
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
    <script>
        $(document).ready(function() {

            $('.btn-edit').on('click', function() {
                var userId = $(this).data('id');
                var nama = $(this).data('nama');
                var type = $(this).data('typekaryawan');

                $('#form-edit').attr('action', '/master/karyawan/' + userId);
                // Isi field
                $('#modal-nama').val(nama);
                $("input[name='type_karyawan'][value='" + type + "']").prop('checked', true);
            });
        });
    </script>
@endsection
