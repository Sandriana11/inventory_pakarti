<header id="page-header">

    <!-- Header Content -->

    <div class="content-header">

        <!-- Left Section -->

        <div class="space-x-1">

            <!-- Toggle Sidebar -->

            <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->

            <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="layout"

                data-action="sidebar_toggle">

                <i class="fa fa-fw fa-bars"></i>

            </button>

            <!-- END Toggle Sidebar -->

        </div>

        <!-- END Left Section -->



        <!-- Right Section -->

        <div class="space-x-1">

            <!-- User Dropdown -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn btn-sm btn-alt-secondary" id="page-header-notifications"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fa fa-flag"></i>
                    <span class="text-primary" id="notif-badge">•</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications" style="">
                    <div class="px-2 py-3 bg-body-light rounded-top">
                        <h5 class="h6 text-center mb-0">
                            Notifikasi
                        </h5>
                    </div>
                    <ul class="nav-items my-2 fs-sm" id="notif_list">
                    </ul>
                    <div class="p-2 bg-body-light rounded-bottom">
                        <a class="dropdown-item text-center mb-0" href="{{ route('crash.index') }}">
                            <i class="fa fa-fw fa-flag opacity-50 me-1"></i> Lihat Semua
                        </a>
                    </div>
                </div>
            </div>
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

            <!-- END User Dropdown -->

        </div>

        <!-- END Right Section -->

    </div>

    <!-- END Header Content -->



    <!-- Header Search -->

    <div id="page-header-search" class="overlay-header bg-body-extra-light">

        <div class="content-header">

            <form class="w-100" action="be_pages_generic_search.html" method="POST">

                <div class="input-group">

                    <!-- Close Search Section -->

                    <!-- Layout API, functionality initialized in Codebase() -> uiApiLayout() -->

                    <button type="button" class="btn btn-secondary" data-toggle="layout"

                        data-action="header_search_off">

                        <i class="fa fa-fw fa-times"></i>

                    </button>

                    <!-- END Close Search Section -->

                    <input type="text" class="form-control" placeholder="Search or hit ESC.."

                        id="page-header-search-input" name="page-header-search-input">

                    <button type="submit" class="btn btn-secondary">

                        <i class="fa fa-fw fa-search"></i>

                    </button>

                </div>

            </form>

        </div>

    </div>

    <!-- END Header Search -->



    <!-- Header Loader -->

    <div id="page-header-loader" class="overlay-header bg-primary">

        <div class="content-header">

            <div class="w-100 text-center">

                <i class="far fa-sun fa-spin text-white"></i>

            </div>

        </div>

    </div>

    <!-- END Header Loader -->

</header>