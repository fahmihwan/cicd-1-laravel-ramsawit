@extends('layouts.main')

@section('container')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Master User</h1>
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
                                Master Users
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
                                        <th>Nama</th>
                                        <th>Username</th>
                                        <th>Created at</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr class="">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->nama }}</td>
                                            <td>{{ $item->username }}</td>
                                            <td class="center">{{ $item->formatted_created_at }}</td>


                                            <td class="center" style="display: flex">
                                                <form method="POST" action="/setting/user/{{ $item->id }}">
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
                    <form role="form" method="POST" action="/setting/user">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalCreate">Tambah user</h4>
                        </div>
                        <div class="modal-body">
                            @method('POST')
                            @csrf
                            <div class="form-group "> {{-- has-success, has-warning, has-error --}}
                                <label>Nama</label>
                                <input type="text" class="form-control" name="nama" value="{{ old('nama') }}"
                                    required>
                            </div>
                            <div class="form-group "> {{-- has-success, has-warning, has-error --}}
                                <label>Username</label>
                                <input type="text" class="form-control" name="username" value="{{ old('username') }}"
                                    required>
                            </div>

                            <div class="form-group "> {{-- has-success, has-warning, has-error --}}
                                <label>Passsword</label>
                                <input type="text" class="form-control" name="password" value="{{ old('password') }}"
                                    required>
                            </div>
                            <div class="form-group "> {{-- has-success, has-warning, has-error --}}
                                <label>Konfirmasi Password</label>
                                <input type="text" class="form-control" name="password_confirmation"
                                    value="{{ old('password_confirmation') }}" required>
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


{{-- <script src="{{ asset('/js/jquery.min.js') }}"></script> --}}

@section('script')
    <script>
        $(document).ready(function() {


        });
    </script>
@endsection
