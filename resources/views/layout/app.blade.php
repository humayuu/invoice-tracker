@php
    $prefix = Request::route()->getPrefix();
    $route = Route::current()->getName();
@endphp

<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Invoice Tracker</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesdesign" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <!-- Select 2 -->
    <link href="{{ asset('assets/libs/select2/css/select2.min.css') }}" rel="stylesheet" type="text/css">
    <!-- end Select 2  -->

    <!-- jquery.vectormap css -->
    <link href="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css') }}"
        rel="stylesheet" type="text/css" />

    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />

    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- DataTables -->
    <link href="{{ asset('assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}" rel="stylesheet"
        type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css') }}"
        rel="stylesheet" type="text/css" />

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body data-topbar="dark">

    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">


        <header id="page-topbar">
            <div class="navbar-header">
                <div class="d-flex">
                    <!-- LOGO -->
                    <div class="navbar-brand-box">
                        <a href="{{ url('/') }}">
                            <h3 class="mt-3 text-white">Invoice Tracker</h3>
                        </a>
                    </div>
                </div>

                <div class="d-flex">






                    <div class="dropdown d-none d-lg-inline-block ms-1">
                        <button type="button" class="btn header-item noti-icon waves-effect" data-toggle="fullscreen">
                            <i class="ri-fullscreen-line"></i>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- ========== Left Sidebar Start ========== -->
        <div class="vertical-menu">

            <div data-simplebar class="h-100">



                <!--- Sidemenu -->
                <div id="sidebar-menu">
                    <!-- Left Menu Start -->
                    <ul class="metismenu list-unstyled" id="side-menu">
                        <li class="menu-title">Menu</li>


                        <ul class="metismenu list-unstyled" id="side-menu">
                            <!-- Invoices Menu -->
                            <li class="{{ str_starts_with(Route::current()->getName(), 'invoice.') ? 'active' : '' }}">
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-file-list-line"></i> <!-- Invoice-specific icon -->
                                    <span>Sales Invoices</span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="{{ Route::current()->getName() == 'invoice.all' ? 'active' : '' }}">
                                        <a href="{{ route('invoice.all') }}">All Invoices</a>
                                    </li>
                                    <li class="{{ Route::current()->getName() == 'invoice.add' ? 'active' : '' }}">
                                        <a href="{{ route('invoice.add') }}">Add Invoice</a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Clients Menu -->
                            <li class="{{ str_starts_with(Route::current()->getName(), 'clients.') ? 'active' : '' }}">
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-user-line"></i> <!-- Client-specific icon -->
                                    <span>Clients</span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="{{ Route::current()->getName() == 'clients.all' ? 'active' : '' }}">
                                        <a href="{{ route('clients.all') }}">All Clients</a>
                                    </li>
                                    <li class="{{ Route::current()->getName() == 'clients.add' ? 'active' : '' }}">
                                        <a href="{{ route('clients.add') }}">Add Clients</a>
                                    </li>

                                </ul>
                            </li>

                            <!-- Purchases Menu -->
                            <li class="{{ str_starts_with(Route::current()->getName(), 'purchase.') ? 'active' : '' }}">
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-shopping-cart-line"></i> <!-- Purchase-specific icon -->
                                    <span>Purchase Invoices</span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="{{ Route::current()->getName() == 'purchase.all' ? 'active' : '' }}">
                                        <a href="{{ route('purchase.all') }}">All Purchases</a>
                                    </li>
                                    <li class="{{ Route::current()->getName() == 'purchase.add' ? 'active' : '' }}">
                                        <a href="{{ route('purchase.add') }}">Add Purchase</a>
                                    </li>
                                </ul>
                            </li>

                            <!-- Suppliers Menu -->
                            <li
                                class="{{ str_starts_with(Route::current()->getName(), 'suppliers.') ? 'active' : '' }}">
                                <a href="javascript: void(0);" class="has-arrow waves-effect">
                                    <i class="ri-truck-line"></i> <!-- Supplier-specific icon -->
                                    <span>Suppliers</span>
                                </a>
                                <ul class="sub-menu">
                                    <li class="{{ Route::current()->getName() == 'suppliers.all' ? 'active' : '' }}">
                                        <a href="{{ route('suppliers.all') }}">All Suppliers</a>
                                    </li>
                                    <li class="{{ Route::current()->getName() == 'suppliers.add' ? 'active' : '' }}">
                                        <a href="{{ route('suppliers.add') }}">Add Supplier</a>
                                    </li>
                                </ul>
                            </li>
                            <!-- Reports Section Heading (Not clickable, not active) -->
                            <li class="nav-item">
                                <div class="px-3 py-2 mt-3 mb-2 border-bottom fw-semibold text-uppercase text-primary small"
                                    style="letter-spacing: 0.5px;">
                                    <i class="ri-folder-chart-line me-1"></i> Reports
                                </div>
                            </li>

                            <!-- Purchase Summary Link -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('purchase.summary.report') }}">
                                    <i class="ri-file-chart-line me-2"></i> Purchase Summary
                                </a>
                            </li>

                            <!-- Sales Summary Link -->
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('clients.summary.report') }}">
                                    <i class="ri-file-chart-line me-2"></i> Sales Summary
                                </a>
                            </li>



                        </ul>



                    </ul>
                </div>
                <!-- Sidebar -->
            </div>
        </div>
        <!-- Left Sidebar End -->
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            @yield('main')


        </div>

        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-sm-6">
                        <script>document.write(new Date().getFullYear())</script> © Invoice Tracker
                    </div>
                    <div class="col-sm-6">
                        <div class="text-sm-end d-none d-sm-block">
                            Crafted by Humayun
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div>
    <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    </div>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/node-waves/waves.min.js') }}"></script>


    <!-- apexcharts -->
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>

    <!-- jquery.vectormap map -->
    <script src="{{ asset('assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script
        src="{{ asset('assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js') }}"></script>

    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- Responsive examples -->
    <script src="{{ asset('assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('assets/js/pages/dashboard.init.js') }}"></script>

    <!--  For Select2 -->
    <script src="{{ asset('assets/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-advanced.init.js') }}"></script>
    <!-- end  For Select2 -->

    <!-- App js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <!-- Required datatable js -->
    <script src="{{ asset('assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/code.js') }}"></script>

    <!-- Datatable init js -->
    <script src="{{ asset('assets/js/pages/datatables.init.js') }}"></script>
    <script src="{{ asset('assets/js/validate.min.js') }}"></script>

    <script>
        @if (Session::has('message'))
            var type = "{{ Session::get('alert-type', 'info') }}"
            switch (type) {
                case 'info':
                    toastr.info(" {{ Session::get('message') }} ");
                    break;

                case 'success':
                    toastr.success(" {{ Session::get('message') }} ");
                    break;

                case 'warning':
                    toastr.warning(" {{ Session::get('message') }} ");
                    break;

                case 'error':
                    toastr.error(" {{ Session::get('message') }} ");
                    break;
            }
        @endif
    </script>

    <!-- Scripts -->
    @stack('scripts')
</body>

</html>
