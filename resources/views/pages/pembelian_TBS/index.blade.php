@extends('layouts.main')

@section('container')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">Pembelian {{ $title }}</h1>
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
            <div class="col-lg-4">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Periode pembelian
                    </div>
                    <div class="panel-body">
                        <table>
                            <tr>
                                <td>Periode </td>
                                <td style="padding-left:20px">: {{ $periode->periode }}</td>
                            </tr>
                            <tr>
                                <td>Periode berakhir</td>
                                <td style="padding-left:20px">:
                                    @if ($periode->periode_berakhir == null)
                                        <span class="label label-success" style="font-size: 12px">Periode
                                            masih berjalan</span>
                                    @else
                                        {{ $periode->formatted_berakhir }}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Total netto </td>
                                <td style="padding-left:20px">: {{ number_format($periode->total_netto, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading ">
                        <div style=" display: flex; justify-content: space-between; align-items: center">
                            <div>
                                Pembelian TBS
                            </div>
                            <div>
                                <a href="/pembelian/tbs/{{ $menu }}/periode" type="button"
                                    class="btn btn-outline btn-default">
                                    <i class="fa fa-arrow-left"></i> Kembali
                                </a>
                                @if ($periode->periode_berakhir == null)
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal"
                                        id="btn-create" data-target="#modalCreateEdit">
                                        <i class="fa fa-plus"></i> Tambah Data
                                    </button>
                                @endif


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
                                    <a href="{{ '/pembelian/tbs/' . $menu . '/' . $periode->id . '/view' }}"
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
                                        <th>Tgl Pembelian / Periode</th>
                                        <th>Nama</th>
                                        @if ($menu == 'RAM')
                                            <th>Timbangan 1</th>
                                            <th>Timbangan 2</th>
                                            <th>Bruto</th>
                                            <th>Sortasi</th>
                                        @endif
                                        <th>Netto</th>
                                        <th>Harga</th>
                                        <th>Uang</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($items as $item)
                                        <tr class="">
                                            <td>{{ $loop->iteration }}</td>

                                            <td>{{ $item->formatted_tgl_pembelian }} /
                                                <span class="label label-danger">{{ $item->periode->periode ?? '' }}</span>
                                                {{-- <span class="" style="color: red">{{ $item->periode->periode }}</span> </td> --}}
                                            <td>{{ $item->nama_customer }}</td>
                                            @if ($menu == 'RAM')
                                                <td>{{ $item->timbangan_first_formatted }}</td>
                                                <td>{{ $item->timbangan_second_formatted }}</td>
                                                <td>{{ $item->bruto_formatted }}</td>
                                                <td>{{ $item->sortasi_formatted }}</td>
                                            @endif
                                            <td>{{ $item->netto_formatted }}</td>
                                            <td>{{ $item->harga_formatted }}</td>
                                            <td>{{ $item->uang_formatted }}</td>

                                            {{-- <td class="center">{{ $item->formatted_created_at }}</td> --}}

                                            <td class="center" style="display: flex">

                                                @if ($item->periode && $item->periode->periode_berakhir != null)
                                                    <button data-bs-toggle="modal" type="button"
                                                        class="btn  btn-circle btn-edit"
                                                        style="background-color: gray; color:white margin-right: 5px"
                                                        onclick="alert('Periode sudah ditutup');">
                                                        <i class="fa fa-lock"></i>
                                                    </button>
                                                @else
                                                    <button data-id="{{ $item->id }}" style="margin-right: 5px"
                                                        data-tanggalpembelian={{ $item->tanggal_pembelian }}
                                                        data-periode={{ $item->periode->periode ?? '' }}
                                                        data-nama="{{ $item->nama_customer }}"
                                                        @if ($menu == 'RAM') data-timbangan1="{{ $item->timbangan_first }}"
                                                        data-timbangan2="{{ $item->timbangan_second }}"
                                                        data-bruto="{{ $item->bruto }}"
                                                        data-sortasi="{{ $item->sortasi }}" @endif
                                                        data-netto="{{ $item->netto }}" data-harga="{{ $item->harga }}"
                                                        data-uang="{{ $item->uang }}" data-bs-toggle="modal"
                                                        type="button" class="btn btn-warning btn-circle btn-edit"
                                                        data-toggle="modal" data-target="#modalCreateEdit"
                                                        data-id="{{ $item->id }}"><i class="fa fa-edit"></i>
                                                    </button>

                                                    <form method="POST"
                                                        action="/pembelian/tbs/{{ $menu }}/delete/{{ $item->id }}">
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
        <input type="hidden" value="{{ $periode->id }}" id="periode">

        <!-- Modal CREATE-->
        <div class="modal fade" id="modalCreateEdit" tabindex="-1" role="dialog" aria-labelledby="mymodalCreateEdit"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id='mainForm' role="form" method=POST
                        action={{ '/pembelian/tbs/' . $menu . '/' . $periode->id . '/view' }}>
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
                                        <label>Tgl Pembelian</label>
                                        <input type="date" class="form-control" name="tanggal_pembelian"
                                            value="{{ now()->toDateString() }}" id="tanggal_pembelian">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Peroide</label>
                                        <input class="form-control" name="periode" value="{{ $periode->periode }}"
                                            id="periode_id_text" readonly>
                                    </div>

                                </div>
                            </div>

                            <div class="form-group "> {{-- has-success, has-warning, has-error --}}
                                <label>Nama Customer</label>
                                <input class="form-control" name="nama_customer" value="{{ old('nama_customer') }}"
                                    id="nama_customer" required>
                            </div>


                            @if ($menu == 'RAM')
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label>Timbangan 1</label>
                                            <div class="form-group input-group">
                                                <input type="number" class="form-control" name="timbangan_first"
                                                    value="{{ old('timbangan_first') }}" id="timbangan_first" required>
                                                <span class="input-group-addon">Kg</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label>Timbangan 2</label>
                                            <div class="form-group input-group">
                                                <input required type="number" class="form-control"
                                                    name="timbangan_second" value="{{ old('timbangan_second') }}"
                                                    id="timbangan_second">
                                                <span class="input-group-addon">Kg</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label>Bruto</label>
                                            <div class="form-group input-group">
                                                <input required type="number" class="form-control" name="bruto"
                                                    value="{{ old('bruto') }}" readonly id="bruto">
                                                <span class="input-group-addon">Kg</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group ">
                                            <label>Sortasi</label>
                                            <div class="form-group input-group">
                                                <input required class="form-control" name="sortasi"
                                                    value="{{ old('sortasi') }}" id="sortasi">
                                                <span class="input-group-addon">%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            @if ($menu == 'RAM')
                                <div class="form-group ">
                                    <label>Netto</label>
                                    <div class="form-group input-group">
                                        <input required class="form-control" name="netto" readonly
                                            value="{{ old('netto') }}" id="netto" type="number">
                                        <span class="input-group-addon">Kg</span>
                                    </div>
                                </div>
                            @else
                                <div class="form-group ">
                                    <label>Netto</label>
                                    <div class="form-group input-group">
                                        <input required class="form-control" type="number" name="netto"
                                            value="{{ old('netto') }}" id="netto">
                                        <span class="input-group-addon">Kg</span>
                                    </div>
                                </div>
                            @endif


                            <div class="form-group ">
                                <label>Harga</label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input required type="number" class="form-control" name="harga"
                                        value="{{ old('harga') }}" id="harga">
                                </div>
                            </div>

                            <div class="form-group ">
                                <label>Uang</label>
                                <div class="form-group input-group">
                                    <span class="input-group-addon">Rp</span>
                                    <input required type="text" class="form-control" readonly name="uang"
                                        value="{{ old('uang') }}" id="uang">
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

    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {

            let menu = $('#menu').val()
            let periodeId = $('#periode').val()


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
                let nama = $('#nama').val(menu);
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

            if (menu == 'RAM') {
                $('#nama, #timbangan_first, #timbangan_second, #sortasi, #harga').on('input', hitungRam);
            } else {
                $('#netto, #harga').on('input', hitungRUMAH_LAHAN);
            }

            $('#btn-create').on('click', function() {
                $('#mainForm')[0].reset(); // Kosongkan form
                $('#mymodalCreateEdit').text('Tambah TBS ' + menu);
                $('#mainForm').attr('action', '/pembelian/tbs/' + menu + '/' + periodeId + '/view');
                $('#tanggal_pembelian').prop('disabled', false);
                // $('#periode_id').prop('disabled', false);
                $('#form-periode-select').show()
                $('#form-periode-text').hide()
                $('#formMethod').val('POST')
            });


            $('.btn-edit').on('click', function() {

                let id = $(this).data('id');
                $('#mainForm')[0].reset(); // Kosongkan form
                $('#mymodalCreateEdit').text('Edit TBS ' + menu);
                $('#mainForm').attr('action', '/pembelian/tbs/' + menu + '/' + periodeId + '/view/' + id);
                $('#formMethod').val('PUT')

                $('#form-periode-select').hide()
                $('#form-periode-text').show()

                $('#tanggal_pembelian').val($(this).data('tanggalpembelian'));
                $('#periode_id_text').val($(this).data('periode'));

                $('#tanggal_pembelian').prop('disabled', true);
                $('#periode_id_text').prop('disabled', true);


                $('#nama_customer').val($(this).data('nama'));
                $('#netto').val($(this).data('netto'));
                $('#harga').val($(this).data('harga'));
                $('#uang').val(formatRupiah($(this).data('uang')));

                if (menu == 'RAM') {
                    $('#timbangan_first').val($(this).data('timbangan1'));
                    $('#timbangan_second').val($(this).data('timbangan2'));
                    $('#bruto').val($(this).data('bruto'));
                    $('#sortasi').val($(this).data('sortasi'));
                }

            });


        });
    </script>
@endsection
