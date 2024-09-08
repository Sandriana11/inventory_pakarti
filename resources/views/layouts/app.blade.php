<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="/js/plugins/sweetalert2/sweetalert2.min.css">
        <link rel="stylesheet" href="/js/plugins/datatables-bs5/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="/js/plugins/datatables-buttons-bs5/css/buttons.bootstrap5.min.css">
        <link rel="stylesheet" href="/js/plugins/datatables-responsive-bs5/css/responsive.bootstrap5.min.css">
        <link rel="stylesheet" href="/js/plugins/select2/css/select2.min.css">
        <link rel="stylesheet" href="/js/plugins/flatpickr/flatpickr.min.css">
        @stack('styles')
        <!-- Scripts -->
        @vite(['resources/sass/main.scss', 'resources/js/codebase/app.js', 'resources/js/app.js'])
    </head>
    <body>
        <div id="page-container" class="{{ (auth()->user()->level == 'admin') ? 'sidebar-o enable-page-overlay side-scroll page-header-modern' : '' }} main-content-boxed">

            @if(auth()->user()->level == 'admin')
                @include('layouts.sidebar')
                @include('layouts.header')
            @else
                @include('layouts.header_staff')
            @endif
            <!-- Page Content -->
            <main id="main-container">
                {{ $slot }}
            </main>
        </div>
        
        <script src="/js/jquery.min.js"></script>
        <script src="/js/plugins/sweetalert2/sweetalert2.min.js"></script>
        <script src="/js/plugins/select2/js/select2.full.min.js"></script>
        <script src="/js/plugins/flatpickr/flatpickr.min.js"></script>
        <script src="/js/plugins/flatpickr/l10n/id.js"></script>
        <script src="/js/plugins/datatables/jquery.dataTables.min.js"></script>
        <script src="/js/plugins/datatables-bs5/js/dataTables.bootstrap5.min.js"></script>
        <script src="/js/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
        <script src="/js/plugins/datatables-responsive-bs5/js/responsive.bootstrap5.min.js"></script>
        <script src="/js/plugins/datatables-buttons/dataTables.buttons.min.js"></script>
        <script src="/js/plugins/datatables-buttons-bs5/js/buttons.bootstrap5.min.js"></script>
        <script src="/js/plugins/datatables-buttons-jszip/jszip.min.js"></script>
        <script src="/js/plugins/datatables-buttons-pdfmake/pdfmake.min.js"></script>
        <script src="/js/plugins/datatables-buttons-pdfmake/vfs_fonts.js"></script>
        <script src="/js/plugins/datatables-buttons/buttons.print.min.js"></script>
        <script src="/js/plugins/datatables-buttons/buttons.html5.min.js"></script>
        <script type="text/javascript">
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        @stack('scripts')

        <script>
            $(document).ready(function () {
                // updating the view with notifications using ajax
                function load_notification() {
                    $.ajax({
                        url: "{{ route('notifikasi') }}",
                        method: "POST",
                        data: {
                            "_token": "{{ csrf_token() }}"
                        },
                        dataType: "json",
                        success: function (data) {
                            var item = '';
                            for (let i = 0; i < data.length; i++) {
                                item += `<li>
                                    <a class="text-dark d-flex py-2" href="/kerusakan/${ data[i].id }">
                                        <div class="flex-shrink-0 me-2 ms-3">
                                            <i class="fa fa-fw fa-exclamation-triangle text-warning"></i>
                                        </div>
                                        <div class="flex-grow-1 pe-2">
                                            <p class="fw-bold mb-1">${ data[i].nomor }</p>
                                            <p class="fw-medium mb-1">${ data[i].pelapor.name }</p>
                                            <div class="text-muted">${ data[i].date_human }</div>
                                        </div>
                                    </a>
                                </li>`;
                            }
                            $("#notif_list").html(item);
                        }
                    });
                }

                load_notification();

                // load new notifications
                $(document).on('click', '.dropdown-toggle', function () {
                    $('.count').html('');
                    load_notification('yes');
                });
                setInterval(function () {
                    load_notification();
                }, 5000);
            });
        </script>
    </body>
</html>
