@extends('layouts.main')

@section('container')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Laporan Stok</h1>
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
                                Laporan Stok
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
                                        <th>Periode ke</th>
                                        <th>Periode mulai</th>
                                        <th>Periode berakhir</th>
                                        <th>Total bruto masuk</th>
                                        <th>Total netto masuk</th>
                                        <th>Total bruto keluar</th>
                                        <th>Total netto keluar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr class="">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>Ke - {{ $item->periode }}</td>
                                            <td>{{ $item->periode_mulai ? \Carbon\Carbon::parse($item->periode_mulai)->translatedFormat('d F Y') : '' }}
                                            </td>
                                            <td> {{ $item->periode_berakhir ? \Carbon\Carbon::parse($item->periode_berakhir)->translatedFormat('d F Y') : '' }}
                                            </td>
                                            <td>{{ number_format($item->total_bruto_masuk, 0, ',', '.') }} Kg</td>
                                            <td>{{ number_format($item->total_netto_masuk, 0, ',', '.') }} Kg</td>
                                            <td>{{ number_format($item->total_bruto_keluar, 0, ',', '.') }} Kg</td>
                                            <td>{{ number_format($item->total_netto_keluar, 0, ',', '.') }} Kg</td>
                                        </tr>
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
