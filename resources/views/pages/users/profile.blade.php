@extends('layouts.main')

@section('container')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Profile User</h1>
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
            <div class="col-lg-6">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Informasi Akun
                    </div>
                    <div class="panel-body">
                        <form action="/user-profile/information" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Nama</label>
                                        <input class="form-control" type="text" name="nama" id="nama"
                                            value="{{ $user['nama'] }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Username</label>
                                        <input class="form-control" type="text" name="username" id="username"
                                            value="{{ $user['username'] }}" disabled>
                                    </div>
                                </div>
                            </div>
                            <button class="btn btn-primary" type="submit">
                                Simpan Perubahan
                            </button>
                        </form>
                    </div>

                </div>


                <div class="panel panel-default">
                    <div class="panel-heading">
                        Ganti Password
                    </div>
                    <div class="panel-body">
                        <form action="/user-profile/changepassword" method="POST">
                            @method('PUT')
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Password saat ini </label>
                                        <input class="form-control" type="text" name="current_password"
                                            id="current_password" value="{{ '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Password Baru </label>
                                        <input class="form-control" type="text" name="new_password" id="new_password"
                                            value="{{ '' }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Konfirmasi Password Baru </label>
                                        <input class="form-control" type="text" name="confirm_password"
                                            id="confirm_password" value="{{ '' }}">
                                    </div>
                                </div>
                            </div>
                            <button class="btn  btn-primary" type="submit">
                                Update Password
                            </button>
                        </form>
                    </div>

                </div>


                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
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
