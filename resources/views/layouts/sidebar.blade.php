  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ url(auth()->user()->foto) }}" class="img-circle img-profil" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{ auth()->user()->username }}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        <li class="{{ (request()->is('dashboard')) ? 'active' : '' }}">
          <a href="{{ route('dashboard') }}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li> 
        @if (auth()->user()->role == 'admin')
        <li class="header">MASTER</li>
        <li class="{{ (request()->is('outlet')) ? 'active' : '' }}">
          <a href="{{ route('outlet.index') }}">
            <i class="fa fa-home"></i> <span>Outlet</span>
          </a>
        </li>
        <li class="{{ (request()->is('paket')) ? 'active' : '' }}">
          <a href="{{ route('paket.index') }}">
            <i class="fa fa-cubes"></i> <span>Paket</span>
          </a>
        </li>
        <li class="{{ (request()->is('pelanggan')) ? 'active' : '' }}">
          <a href="{{ route('pelanggan.index') }}">
            <i class="fa fa-id-card"></i> <span>Pelanggan</span>
          </a>
        </li>
        <li class="header">TRANSAKSI</li>
        <li class="{{ (request()->is('penjualan')) ? 'active' : '' }}">
          <a href="{{ route('penjualan.index') }}">
            <i class="fa fa-upload"></i> <span>Penjualan</span>
          </a>
        </li>
        <li class="{{ (request()->is('belumbayar')) ? 'active' : '' }}">
          <a href="{{ route('belum_bayar.index') }}">
            <i class="fa fa-money"></i> <span>Belum Dibayar</span>
          </a>
        </li>
        <li class="{{ (request()->is('transaksi')) ? 'active' : '' }}">
          <a href="{{ route('transaksi.index') }}">
            <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Aktif</span>
          </a>
        </li>
        <li class="{{ (request()->is('transaksi/baru')) ? 'active' : '' }}">
          <a href="{{ route('transaksi.baru') }}">
            <i class="fa fa-cart-arrow-down"></i> <span>Transaksi baru</span>
          </a>
        </li>
        <li class="header">REPORT</li>
        <li class="{{ (request()->is('laporan')) ? 'active' : '' }}">
          <a href="{{ route('laporan.index') }}">
            <i class="fa fa-file-pdf-o"></i> <span>Laporan</span>
          </a>
        </li>
        <li class="{{ (request()->is('laporan/outlet')) ? 'active' : '' }}">
          <a href="{{ route('outlets.laporan.index') }}">
            <i class="fa fa-home"></i> <span>Laporan per-Outlet</span>
          </a>
        </li>
        <li class="header">System</li>
        <li class="{{ (request()->is('user')) ? 'active' : '' }}">
          <a href="{{ route('user.index') }}">
            <i class="fa fa-user"></i> <span>Pengguna</span>
          </a>
        </li>
        <li class="{{ (request()->is('setting')) ? 'active' : '' }}">
          <a href="{{ route('setting.index') }}">
            <i class="fa fa-cog"></i> <span>Pengaturan</span>
          </a>
        </li>
        @elseif(auth()->user()->role == 'kasir') 
        <li class="{{ (request()->is('pelanggan')) ? 'active' : '' }}">
          <a href="{{ route('pelanggan.index') }}">
            <i class="fa fa-id-card"></i> <span>Pelanggan</span>
          </a>
        </li>
        <li class="header">TRANSAKSI</li>
        <li class="{{ (request()->is('penjualan')) ? 'active' : '' }}">
          <a href="{{ route('penjualan.index') }}">
            <i class="fa fa-upload"></i> <span>Penjualan</span>
          </a>
        </li>
        <li class="{{ (request()->is('belumbayar')) ? 'active' : '' }}">
          <a href="{{ route('belum_bayar.index') }}">
            <i class="fa fa-money"></i> <span>Belum Dibayar</span>
          </a>
        </li>
        <li class="{{ (request()->is('transaksi')) ? 'active' : '' }}">
          <a href="{{ route('transaksi.index') }}">
            <i class="fa fa-cart-arrow-down"></i> <span>Transaksi Aktif</span>
          </a>
        </li>
        <li class="{{ (request()->is('transaksi/baru')) ? 'active' : '' }}">
          <a href="{{ route('transaksi.baru') }}">
            <i class="fa fa-cart-arrow-down"></i> <span>Transaksi baru</span>
          </a>
        </li>
        <li class="header">REPORT</li>
        <li class="{{ (request()->is('laporan')) ? 'active' : '' }}">
          <a href="{{ route('laporan.index') }}">
            <i class="fa fa-file-pdf-o"></i> <span>Laporan</span>
          </a>
        </li>
        @else
        <li class="header">REPORT</li>
        <li class="{{ (request()->is('laporan')) ? 'active' : '' }}">
          <a href="{{ route('outlet.laporan', auth()->user()->id_outlet) }}">
            <i class="fa fa-file-pdf-o"></i> <span>Laporan</span>
          </a>
        </li>
        @endif             
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>