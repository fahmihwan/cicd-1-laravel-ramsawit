<aside class="sidebar navbar-default" role="navigation">
    <div class="sidebar-nav navbar-collapse">
        <ul class="nav" id="side-menu">
            <li class="sidebar-search">
                <div class="input-group custom-search-form">
                    <input type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-btn">
                        <button class="btn btn-primary" type="button">
                            <i class="fa fa-search"></i>
                        </button>
                    </span>
                </div>
                <!-- /input-group -->
            </li>
            <li>
                <a href="{{ url('/dashboard') }}"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-fw" aria-hidden="true" title="Copy to use tag">&#xf02b</i>
                    Master Data<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url('/master/karyawan') }}">Master Karyawan</a>
                    </li>
                    <li>
                        <a href="{{ url('/master/pabrik') }}">Master Pabrik</a>
                    </li>
                    <li>
                        <a href="{{ url('/master/pekerjaan-lain') }}">Master Pekerjaan Non Sawit</a>
                    </li>
                    <li>
                        <a href="{{ url('/master/tarif') }}">Master Tarif</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            <li>
                <a href="{{ url('/periode') }}">
                    <i class="fa fa-fw" aria-hidden="true" title="Copy to use hourglass-half">&#xf252</i>
                    Periode</a>
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-fw" aria-hidden="true" title="Copy to use cart-arrow-down">&#xf218</i>
                    Pembelian TBS<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url('/pembelian/tbs/RUMAH/periode') }}">TBS RUMAH</a>
                    </li>
                    <li>
                        <a href="{{ url('/pembelian/tbs/LAHAN/periode') }}">TBS LAHAN</a>
                    </li>
                    <li>
                        <a href="{{ url('/pembelian/tbs/RAM/periode') }}">TBS RAM</a>
                    </li>
                </ul>
                <!-- /.nav-second-level -->
            </li>
            <li>
                <a href="#">
                    <i class="fa fa-fw" aria-hidden="true" title="Copy to use truck">&#xf0d1</i>
                    Penjualan TBS<span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li>
                        <a href="{{ url('/penjualan/tbs/PLASMA/periode') }}">Plasma</a>
                    </li>
                    <li>
                        <a href="{{ url('/penjualan/tbs/LU/periode') }}">LU</a>
                    </li>
                    <li>
                        <a href="{{ url('/penjualan/tbs/LAINNYA/periode') }}">PKS Lainnya</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="{{ url('/pekerjaan-nonsawit/periode') }}">
                    <i class="fa fa-fw" aria-hidden="true" title="Copy to use briefcase">&#xf0b1</i>
                    Pekerjaan Non Sawit</a>
            </li>
            <li>
                <a href="{{ url('/penggajian') }}">
                    {{-- <i class="fa fa-table fa-fw"></i> --}}
                    <i class="fa fa-fw" aria-hidden="true" title="Copy to use file-text-o">&#xf0f6</i>
                    Penggajian</a>
            </li>
            <li>
                <a href="{{ url('/pinjaman') }}">
                    {{-- <i class="fa fa-fw" aria-hidden="true" title="Copy to use exchange">&#xf0ec</i> --}}
                    <i class="fa fa-fw" aria-hidden="true" title="Copy to use money">&#xf0d6</i>
                    Pinjaman (Kasbon)</a>
            </li>


            <li>
                <a href="{{ url('/laba') }}">
                    <i class="fa fa-fw" aria-hidden="true" title="Copy to use line-chart">&#xf201</i>
                    Perhitungan Laba</a>
            </li>
            <li>
                <a href="{{ url('/laporan/laporan-stock') }}">
                    {{-- <i class="fa fa-table fa-fw"></i> --}}
                    <i class="fa fa-fw" aria-hidden="true" title="Copy to use cubes">&#xf1b3</i>
                    Laporan Stok</a>
            </li>
        </ul>
    </div>
</aside>
