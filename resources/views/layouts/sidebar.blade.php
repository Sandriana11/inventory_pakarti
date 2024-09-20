<nav id="sidebar">

    <!-- Sidebar Content -->

    <div class="sidebar-content">

        <!-- Side Header -->

        <div class="content-header justify-content-lg-center">

            <!-- Logo -->

            <div>

                <img src="/images/logo1_pakarti.png" width="100px"/>

            </div>

            <!-- END Logo -->



            <!-- Options -->

            <div>

                <!-- Close Sidebar, Visible only on mobile screens -->

                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->

                <button type="button" class="btn btn-sm btn-alt-danger d-lg-none" data-toggle="layout"

                    data-action="sidebar_close">

                    <i class="fa fa-fw fa-times"></i>

                </button>

                <!-- END Close Sidebar -->

            </div>

            <!-- END Options -->

        </div>

        <!-- END Side Header -->



        <!-- Sidebar Scrolling -->

        <div class="js-sidebar-scroll">

            <!-- Side Navigation -->

            <div class="content-side content-side-full">

                <ul class="nav-main">

                    <li class="nav-main-item">

                        <a class="nav-main-link {{ request()->is('dashboard') ? ' active' : '' }}" href="/dashboard">

                            <i class="nav-main-link-icon fa fa-house-user"></i>

                            <span class="nav-main-link-name">Dashboard</span>

                        </a>

                    </li>

                    <li class="nav-main-item">

                        <a class="nav-main-link {{ request()->is('kerusakan', 'kerusakan/*') ? ' active' : '' }}" href="{{ route('crash.index') }}">

                            <i class="nav-main-link-icon fa fa-hammer"></i>

                            <span class="nav-main-link-name">Kerusakan</span>

                        </a>

                    </li>

                    <li class="nav-main-item">

                        <a class="nav-main-link {{ request()->is('maintenance', 'maintenance/*') ? ' active' : '' }}" href="{{ route('maintenance.index') }}">

                            <i class="nav-main-link-icon fa fa-screwdriver-wrench"></i>

                            <span class="nav-main-link-name">Perbaikan</span>

                        </a>

                    </li>

                    <li class="nav-main-item">

                        <a class="nav-main-link {{ request()->is('kategori', 'kategori/*') ? ' active' : '' }}" href="{{ route('kategori.index') }}">

                            <i class="nav-main-link-icon fa fa-archive"></i>

                            <span class="nav-main-link-name">Kategori</span>

                        </a>

                    </li>

                    <li class="nav-main-item">

                        <a class="nav-main-link {{ request()->is('lokasi', 'lokasi/*') ? ' active' : '' }}" href="{{ route('lokasi.index') }}">

                            <i class="fa fa-map-marker-alt nav-main-link-icon"></i>

                            <span class="nav-main-link-name">Departemen</span>

                        </a>

                    </li>

                    <li class="nav-main-item">

                        <a class="nav-main-link {{ request()->is('inventaris', 'inventaris/*') ? ' active' : '' }}" href="{{ route('inventaris.index') }}">

                            <i class="nav-main-link-icon fa fa-boxes"></i>

                            <span class="nav-main-link-name">Inventaris</span>

                        </a>

                    </li>


                    <li class="nav-main-item">

                        <a class="nav-main-link {{ request()->is('pindah', 'pindah/*') ? ' active' : '' }}" href="{{ route('pindah.index') }}">

                            <i class="nav-main-link-icon fa fa-cart-flatbed"></i>

                            <span class="nav-main-link-name">Pemindahan</span>

                        </a>

                    </li>

                    <li class="nav-main-item">

                        <a class="nav-main-link {{ request()->is('jabatan', 'jabatan/*') ? ' active' : '' }}" href="{{ route('jabatan.index') }}">

                            <i class="nav-main-link-icon fa fa-archive"></i>

                            <span class="nav-main-link-name">Jabatan</span>

                        </a>

                    </li>

                    <li class="nav-main-item">

                        <a class="nav-main-link {{ request()->is('users', 'users/*') ? ' active' : '' }}" href="{{ route('user.index') }}">

                            <i class="nav-main-link-icon fa fa-user"></i>

                            <span class="nav-main-link-name">Pengguna</span>

                        </a>

                    </li>

                </ul>

            </div>

            <!-- END Side Navigation -->

        </div>

        <!-- END Sidebar Scrolling -->

    </div>

    <!-- Sidebar Content -->

</nav>
