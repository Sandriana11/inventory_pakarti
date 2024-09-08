<header id="page-header">
    <!-- Header Content -->
    <div class="content-header">
        <!-- Left Section -->
        <div class="space-x-1">
            <!-- Logo -->
            <a href="{{ route('dashboard') }}">
                <img src="/images/logo1_pakarti.png" width="150px"/>
            </a>
            <!-- END Logo -->
        </div>
        <!-- END Left Section -->

        <!-- Middle Section -->
        <div class="d-none d-lg-block">
            <!-- Header Navigation -->
            <!-- Desktop Navigation, mobile navigation can be found in #sidebar -->
            <ul class="nav-main nav-main-horizontal nav-main-hover">
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('dashboard') ? ' active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="nav-main-link-icon fa fa-house-user"></i>
                        <span class="nav-main-link-name">Beranda</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{  request()->is('kerusakan', 'kerusakan/*') ? ' active' : '' }}" href="{{ route('crash.index') }}">
                        <i class="nav-main-link-icon fa fa-hammer"></i>
                        <span class="nav-main-link-name">Laporan</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('maintenance', 'maintenance/*') ? ' active' : '' }}" href="{{ route('maintenance.index') }}">
                        <i class="nav-main-link-icon fa fa-screwdriver-wrench"></i>
                        <span class="nav-main-link-name">Perbaikan</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link {{ request()->is('pengadaan', 'pengadaan/*') ? ' active' : '' }}" href="{{ route('pengadaan.index') }}">
                        <i class="nav-main-link-icon fa fa-cart-plus"></i>
                        <span class="nav-main-link-name">Pengadaan</span>
                    </a>
                </li>
            </ul>
            <!-- END Header Navigation -->
        </div>
        <!-- END Middle Section -->

        <!-- Right Section -->
        <div class="space-x-1">
            <div class="dropdown d-inline-block">
                <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-user d-sm-none"></i>
                    <span class="d-none d-sm-inline-block fw-semibold">{{ Auth::user()->name }}</span>
                    <i class="fa fa-angle-down opacity-50 ms-1"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0"
                    aria-labelledby="page-header-user-dropdown">
                    <div class="px-2 py-3 bg-body-light rounded-top">
                        <h5 class="h6 text-center mb-0">
                            {{ Auth::user()->name }}
                        </h5>
                    </div>
                    <div class="p-2">
                        <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1"
                            href="{{ route('profile.edit') }}">
                            <span>Profil</span>
                            <i class="fa fa-fw fa-user opacity-25"></i>
                        </a>
                        <a class="dropdown-item d-flex align-items-center justify-content-between"
                            href="{{ route('password') }}">
                            <span>Ubah Password</span>
                            <i class="fa fa-fw fa-envelope-open opacity-25"></i>
                        </a>
                        <div class="dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a class="dropdown-item d-flex align-items-center justify-content-between space-x-1" :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                                <span>Keluar</span>
                                <i class="fa fa-fw fa-sign-out-alt opacity-25"></i>
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Right Section -->
    </div>
    <!-- END Header Content -->
</header>
