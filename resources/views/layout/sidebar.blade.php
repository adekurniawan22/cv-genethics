<!--start sidebar -->
<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="<?= url('assets/onedash') ?>/images/logo.png" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h5 class="logo-text text-dark">CV. GENETHICS</h5>
        </div>
        <div class="toggle-icon ms-auto"><i class="bi bi-list"></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        {{-- Role Owner --}}
        @if (session('role') == 'owner')
            <li class="{{ Request::is('owner/dashboard*') ? 'mm-active' : '' }}">
                <a href="<?= url('owner/dashboard') ?>">
                    <div class="parent-icon"><i class="bi bi-speedometer2"></i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>

            <li class="{{ Request::is('owner/pengguna*') ? 'mm-active' : '' }}">
                <a href="<?= url('owner/pengguna') ?>">
                    <div class="parent-icon"><i class="bi bi-people-fill"></i></div>
                    <div class="menu-title">Pengguna</div>
                </a>
            </li>

            <li class="{{ Request::is('owner/pesanan*') ? 'mm-active' : '' }}">
                <a href="<?= url('owner/pesanan') ?>">
                    <div class="parent-icon"><i class="bi bi-bag-fill"></i></div>
                    <div class="menu-title">Pesanan</div>
                </a>
            </li>
        @endif

        {{-- Role Manajer --}}
        @if (session('role') == 'manajer')
            <li class="{{ Request::is('manajer/dashboard*') ? 'mm-active' : '' }}">
                <a href="<?= url('manajer/dashboard') ?>">
                    <div class="parent-icon"><i class="bi bi-speedometer2"></i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>

            <li class="{{ Request::is('manajer/mesin*') ? 'mm-active' : '' }}">
                <a href="<?= url('manajer/mesin') ?>">
                    <div class="parent-icon"><i class="bi bi-gear-fill"></i></div>
                    <div class="menu-title">Mesin</div>
                </a>
            </li>

            <li class="{{ Request::is('manajer/hari-libur*') ? 'mm-active' : '' }}">
                <a href="<?= url('manajer/hari-libur') ?>">
                    <div class="parent-icon"><i class="bi bi-calendar-date-fill"></i></div>
                    <div class="menu-title">Hari Libur</div>
                </a>
            </li>

            <li class="{{ Request::is('manajer/produk*') ? 'mm-active' : '' }}">
                <a href="<?= url('manajer/produk') ?>">
                    <div class="parent-icon"><i class="bi bi-box-seam"></i></div>
                    <div class="menu-title">Produk</div>
                </a>
            </li>

            <li class="{{ Request::is('manajer/pesanan*') ? 'mm-active' : '' }}">
                <a href="<?= url('manajer/pesanan') ?>">
                    <div class="parent-icon"><i class="bi bi-bag-fill"></i></div>
                    <div class="menu-title">Pesanan</div>
                </a>
            </li>

            <li class="{{ Request::is('manajer/penjadwalan*') ? 'mm-active' : '' }}">
                <a href="<?= url('manajer/penjadwalan') ?>">
                    <div class="parent-icon"><i class="bi bi-calendar-week-fill"></i></div>
                    <div class="menu-title">Penjadwalan</div>
                </a>
            </li>
        @endif

        {{-- Role Admin --}}
        @if (session('role') == 'admin')
            <li class="{{ Request::is('admin/dashboard*') ? 'mm-active' : '' }}">
                <a href="<?= url('admin/dashboard') ?>">
                    <div class="parent-icon"><i class="bi bi-speedometer2"></i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>

            <li class="{{ Request::is('admin/pesanan*') ? 'mm-active' : '' }}">
                <a href="<?= url('admin/pesanan') ?>">
                    <div class="parent-icon"><i class="bi bi-bag-fill"></i></div>
                    <div class="menu-title">Pesanan</div>
                </a>
            </li>
        @endif

        {{-- Role Super Super Admin --}}
        @if (session('role') == 'super')
            <li class="{{ Request::is('super/dashboard*') ? 'mm-active' : '' }}">
                <a href="<?= url('super/dashboard') ?>">
                    <div class="parent-icon"><i class="bi bi-speedometer2"></i></div>
                    <div class="menu-title">Dashboard</div>
                </a>
            </li>

            <li class="{{ Request::is('super/pengguna*') ? 'mm-active' : '' }}">
                <a href="<?= url('super/pengguna') ?>">
                    <div class="parent-icon"><i class="bi bi-people-fill"></i></div>
                    <div class="menu-title">Pengguna</div>
                </a>
            </li>

            <li class="{{ Request::is('super/mesin*') ? 'mm-active' : '' }}">
                <a href="<?= url('super/mesin') ?>">
                    <div class="parent-icon"><i class="bi bi-gear-fill"></i></div>
                    <div class="menu-title">Mesin</div>
                </a>
            </li>

            <li class="{{ Request::is('super/hari-libur*') ? 'mm-active' : '' }}">
                <a href="<?= url('super/hari-libur') ?>">
                    <div class="parent-icon"><i class="bi bi-calendar-date-fill"></i></div>
                    <div class="menu-title">Hari Libur</div>
                </a>
            </li>

            <li class="{{ Request::is('super/produk*') ? 'mm-active' : '' }}">
                <a href="<?= url('super/produk') ?>">
                    <div class="parent-icon"><i class="bi bi-box-seam"></i></div>
                    <div class="menu-title">Produk</div>
                </a>
            </li>

            <li class="{{ Request::is('super/pesanan*') ? 'mm-active' : '' }}">
                <a href="<?= url('super/pesanan') ?>">
                    <div class="parent-icon"><i class="bi bi-bag-fill"></i></div>
                    <div class="menu-title">Pesanan</div>
                </a>
            </li>

            <li class="{{ Request::is('super/penjadwalan*') ? 'mm-active' : '' }}">
                <a href="<?= url('super/penjadwalan') ?>">
                    <div class="parent-icon"><i class="bi bi-calendar-week-fill"></i></div>
                    <div class="menu-title">Penjadwalan</div>
                </a>
            </li>
        @endif
    </ul>
    <!--end navigation-->
</aside>
<!--end sidebar -->
