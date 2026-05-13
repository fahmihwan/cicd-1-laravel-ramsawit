<!DOCTYPE html>
{{-- <html lang="{{ str_replace('_', '-', app()->getLocale()) }}"> --}}
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sistem Rekapitulasi Sawit</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('/css/metisMenu.min.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/dataTables/dataTables.bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/dataTables/dataTables.responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/startmin.css') }}" rel="stylesheet">
    <link href="{{ asset('/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">

    <!-- jQuery UI (kompatibel dengan jQuery 2.1.3) -->





    {{-- <link href="../css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="../css/metisMenu.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="../css/dataTables/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="../css/dataTables/dataTables.responsive.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="../css/startmin.css" rel="stylesheet"> --}}

    <!-- Custom Fonts -->
    {{-- <link href="../css/font-awesome.min.css" rel="stylesheet"> --}}

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @yield('style')
    @vite('resources/css/app.css')
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        @include('layouts.navbar')


        <!-- /.sidebar -->
        @include('layouts.sidebar')
        <div id="page-wrapper">
            <div class="container-fluid">
                @yield('container')
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    {{-- <script src="../js/jquery.min.js"></script> --}}
    <script src="{{ asset('/js/jquery.min.js') }}"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('/js/bootstrap.min.js') }}"></script>

    {{-- <link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <x-alert />


    <script>
        $(document).ready(function() {
            $('#dataTables-example').DataTable({
                responsive: true
            });

            $('.btn-confirm-delete').click(function(e) {
                e.preventDefault(); // hentikan form submit langsung

                let form = $(this).closest('form'); // ambil form terdekat

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: 'Data yang dihapus tidak bisa dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // submit form jika dikonfirmasi
                    }
                });
            });

        });
    </script>

    @yield('script')

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{ asset('/js/metisMenu.min.js') }}"></script>

    <!-- DataTables JavaScript -->
    <script src="{{ asset('/js/dataTables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('/js/dataTables/dataTables.bootstrap.min.js') }}"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{ asset('/js/startmin.js') }}"></script>


</body>

</html>
