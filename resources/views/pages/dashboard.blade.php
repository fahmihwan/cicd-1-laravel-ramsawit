@extends('layouts.main')

@section('container')
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">Dashboard</h1>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-users fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $stat['total_karyawan'] }}</div>
                            <div>Total Karyawan</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-yellow">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-cart-plus fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $stat['total_pembelian_tbs'] }}</div>
                            <div>Total Pembelian</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-green">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-truck fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $stat['total_penjualan'] }}</div>
                            <div>Total Penjualan</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        {{--  --}}
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-red">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-fw fa-5x">&#xf252</i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $stat['periode_terbaru'] }}</div>
                            <div>Periode terbaru</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">View Details</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-8">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Grafik total pembelian TBS perbulan
                    <div class="pull-right">
                        <div class="btn-group">
                            {{-- dsd --}}
                        </div>
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div id="pembeliantbs-line-chart"></div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
            {{-- <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Bar Chart Example
                    <div class="pull-right">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                Actions
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu pull-right" role="menu">
                                <li><a href="#">Action</a>
                                </li>
                                <li><a href="#">Another action</a>
                                </li>
                                <li><a href="#">Something else here</a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover table-striped">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>3326</td>
                                            <td>10/21/2013</td>
                                            <td>3:29 PM</td>
                                            <td>$321.33</td>
                                        </tr>
                                        <tr>
                                            <td>3325</td>
                                            <td>10/21/2013</td>
                                            <td>3:20 PM</td>
                                            <td>$234.34</td>
                                        </tr>
                                        <tr>
                                            <td>3324</td>
                                            <td>10/21/2013</td>
                                            <td>3:03 PM</td>
                                            <td>$724.17</td>
                                        </tr>
                                        <tr>
                                            <td>3323</td>
                                            <td>10/21/2013</td>
                                            <td>3:00 PM</td>
                                            <td>$23.71</td>
                                        </tr>
                                        <tr>
                                            <td>3322</td>
                                            <td>10/21/2013</td>
                                            <td>2:49 PM</td>
                                            <td>$8345.23</td>
                                        </tr>
                                        <tr>
                                            <td>3321</td>
                                            <td>10/21/2013</td>
                                            <td>2:23 PM</td>
                                            <td>$245.12</td>
                                        </tr>
                                        <tr>
                                            <td>3320</td>
                                            <td>10/21/2013</td>
                                            <td>2:15 PM</td>
                                            <td>$5663.54</td>
                                        </tr>
                                        <tr>
                                            <td>3319</td>
                                            <td>10/21/2013</td>
                                            <td>2:13 PM</td>
                                            <td>$943.45</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
                        </div>
                        <!-- /.col-lg-4 (nested) -->
                        <div class="col-lg-8">
                            <div id="morris-bar-chart"></div>
                        </div>
                        <!-- /.col-lg-8 (nested) -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.panel-body -->
            </div> --}}
            <!-- /.panel -->

            <!-- /.panel -->
        </div>
        <!-- /.col-lg-8 -->
        <div class="col-lg-4">
            {{-- <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> Notifications Panel
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item">
                            <i class="fa fa-comment fa-fw"></i> New Comment
                            <span class="pull-right text-muted small"><em>4 minutes ago</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                            <span class="pull-right text-muted small"><em>12 minutes ago</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-envelope fa-fw"></i> Message Sent
                            <span class="pull-right text-muted small"><em>27 minutes ago</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-tasks fa-fw"></i> New Task
                            <span class="pull-right text-muted small"><em>43 minutes ago</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-upload fa-fw"></i> Server Rebooted
                            <span class="pull-right text-muted small"><em>11:32 AM</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-bolt fa-fw"></i> Server Crashed!
                            <span class="pull-right text-muted small"><em>11:13 AM</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-warning fa-fw"></i> Server Not Responding
                            <span class="pull-right text-muted small"><em>10:57 AM</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-shopping-cart fa-fw"></i> New Order Placed
                            <span class="pull-right text-muted small"><em>9:49 AM</em>
                            </span>
                        </a>
                        <a href="#" class="list-group-item">
                            <i class="fa fa-money fa-fw"></i> Payment Received
                            <span class="pull-right text-muted small"><em>Yesterday</em>
                            </span>
                        </a>
                    </div>
                    <!-- /.list-group -->
                    <a href="#" class="btn btn-default btn-block">View All Alerts</a>
                </div>
                <!-- /.panel-body -->
            </div> --}}
            <!-- /.panel -->
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Total tonase pembelian secara keseluruhan
                </div>
                <div class="panel-body">
                    <div id="morris-donut-chart"></div>

                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->

            <!-- /.panel .chat-panel -->
        </div>
        <!-- /.col-lg-4 -->
    </div>

    <div class="row">
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Example Line Chart
                    <div class="pull-right">
                        <div class="btn-group">
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div id="myfirstchart" style="height: 250px;"></div>
                </div>
                <!-- /.panel-body -->
            </div>
        </div>
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bar-chart-o fa-fw"></i> Example Line Chart
                    <div class="pull-right">
                        <div class="btn-group">
                        </div>
                    </div>
                </div>
                <div class="panel-body">
                    <div id="morris-bar-chart"></div>
                </div>
                <!-- /.panel-body -->
            </div>
        </div>

    </div>
@endsection

@section('script')
    <!-- Morris Charts JavaScript -->
    <script src="{{ asset('/js/raphael.min.js') }}"></script>
    <script src="{{ asset('/js/morris.min.js') }}"></script>
    {{-- <script src="{{ asset('/js/morris-data.js') }}"></script> --}}

    <script>
        $(function() {
            let totalPembelian_lineChart = @json($totalPembelian_lineChart);
            let totalPembelian_donutChart = @json($totalPembelian_donutChart);

            Morris.Area({
                element: 'pembeliantbs-line-chart',
                data: totalPembelian_lineChart,
                xkey: 'period',
                ykeys: ['tbs_ram', 'tbs_rumah', 'tbs_lahan'],
                labels: ['TBS RAM', 'TBS RUMAH', 'TBS LAHAN'],
                pointSize: 2,
                hideHover: 'auto',
                resize: true,
                xLabels: "month",
                dateFormat: function(x) {
                    return new Date(x).toLocaleDateString('id-ID', {
                        month: 'long',
                        year: 'numeric'
                    });
                }
                // element: 'pembeliantbs-line-chart',
                // data: [{
                //     period: '2011',
                //     iphone: 2666,
                //     ipad: null,
                //     itouch: 2647
                // }, {
                //     period: '2022',
                //     iphone: 2778,
                //     ipad: 2294,
                //     itouch: 2441
                // }, ],
                // xkey: 'period',
                // ykeys: ['iphone', 'ipad', 'itouch'],
                // labels: ['iPhone', 'iPad', 'iPod Touch'],
                // pointSize: 2,
                // hideHover: 'auto',
                // resize: true
            });

            Morris.Donut({
                element: 'morris-donut-chart',
                data: totalPembelian_donutChart,
                resize: true
            });


            new Morris.Line({
                // ID elemen chart
                element: 'myfirstchart',

                // Data dengan 3 value (a, b, c)
                data: [{
                        year: '2008',
                        a: 20,
                        b: 15,
                        c: 10
                    },
                    {
                        year: '2009',
                        a: 10,
                        b: 25,
                        c: 20
                    },
                    {
                        year: '2010',
                        a: 5,
                        b: 10,
                        c: 15
                    },
                    {
                        year: '2011',
                        a: 5,
                        b: 20,
                        c: 25
                    },
                    {
                        year: '2012',
                        a: 20,
                        b: 30,
                        c: 40
                    }
                ],

                // sumbu X
                xkey: 'year',

                // sumbu Y, isi dengan semua key yg mau ditampilkan
                ykeys: ['a', 'b', 'c'],

                // label untuk tooltip
                labels: ['Series A', 'Series B', 'Series C'],

                hideHover: 'auto',
                resize: true
            });
            Morris.Bar({
                element: 'morris-bar-chart',
                data: [{
                        y: '2006',
                        a: 20,
                        b: 40,
                        c: 80
                    },
                    {
                        y: '2007',
                        a: 75,
                        b: 65,
                        c: 50
                    },
                    {
                        y: '2008',
                        a: 50,
                        b: 40,
                        c: 30
                    },
                    {
                        y: '2009',
                        a: 75,
                        b: 65,
                        c: 55
                    },
                    {
                        y: '2010',
                        a: 50,
                        b: 40,
                        c: 35
                    },
                    {
                        y: '2011',
                        a: 75,
                        b: 65,
                        c: 60
                    },
                    {
                        y: '2012',
                        a: 100,
                        b: 90,
                        c: 85
                    }
                ],
                xkey: 'y',
                ykeys: ['a', 'b', 'c'],
                labels: ['Series A', 'Series B', 'Series C'],
                hideHover: 'auto',
                resize: true
            });

        });
    </script>
@endsection
{{-- let dummyTotalPembelian_lineChart = [{
                period: '2022-01-01',
                tbs_ram: 120,
                tbs_rumah: 80,
                tbs_lahan: 60
            },
            {
                period: '2022-02-01',
                tbs_ram: 140,
                tbs_rumah: 85,
                tbs_lahan: 72
            },
            {
                period: '2022-03-01',
                tbs_ram: 135,
                tbs_rumah: 90,
                tbs_lahan: 75
            },
            {
                period: '2022-04-01',
                tbs_ram: 150,
                tbs_rumah: 95,
                tbs_lahan: 78
            },
            {
                period: '2022-05-01',
                tbs_ram: 160,
                tbs_rumah: 100,
                tbs_lahan: 82
            },
            {
                period: '2022-06-01',
                tbs_ram: 170,
                tbs_rumah: 110,
                tbs_lahan: 88
            }
        ] --}}
