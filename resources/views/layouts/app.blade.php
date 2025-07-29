<!DOCTYPE html>
<html lang="fr" dir="ltr" data-startbar="dark" data-bs-theme="light">


<!-- Mirrored from phplaravel-1384472-5380003.cloudwaysapps.com/index by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 12 Jun 2025 01:58:15 GMT -->
<!-- Added by HTTrack -->
<meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->

<head>
    <!-- All meta and title start-->
    <meta content="text/html; charset=UTF-8" http-equiv="Content-Type">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        content="admin template, ki-admin admin template, dashboard template, flat admin template, responsive admin template, web app"
        name="keywords">

    <link rel="icon" href="{{ asset('assets/images/logo/iconlogo1.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo/iconlogo1.png') }}" type="image/x-icon">

    <title>@yield('title')</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
</head>

<body>
    <!-- Top Bar Start -->
    <div class="topbar d-print-none">
        <div class="container-fluid">
            <nav class="topbar-custom d-flex justify-content-between" id="topbar-custom">

                <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                    <li>
                        <button class="nav-link mobile-menu-btn nav-icon" id="togglemenu">
                            <iconify-icon icon="solar:hamburger-menu-line-duotone" class="fs-20"></iconify-icon>
                        </button>
                    </li>
                    <li class="mx-2 welcome-text">
                        <h5 class="mb-0 fw-semibold text-truncate">Bienvenue, {{ Auth::User()->identifiant ?? 'Utilisateur' }}</h5>
                        <!-- <h6 class="mb-0 fw-normal text-muted text-truncate fs-14">Voici un aperçu de vos caisses.</h6> -->
                    </li>
                </ul>

                <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                    <li class="hide-phone app-search">
                        <form role="search" action="#" method="get">
                            <input type="search" name="search" class="form-control top-search mb-0"
                                placeholder="Rechercher une caisse, un utilisateur...">
                            <button type="submit"><i class="iconoir-search"></i></button>
                        </form>
                    </li>


                    <li class="topbar-item">
                        <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                            <iconify-icon icon="solar:moon-bold-duotone" class="dark-mode fs-20"></iconify-icon>
                            <iconify-icon icon="solar:sun-2-bold-duotone" class="light-mode fs-20"></iconify-icon>
                        </a>
                    </li>

                    

                    <li class="dropdown topbar-item">
                        <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown"
                            href="#" role="button">
                            <img src="{{ asset('assets/images/user.jpg') }}" alt="" class="thumb-md rounded">
                        </a>
                        <div class="dropdown-menu dropdown-menu-end py-0">
                            <div class="d-flex align-items-center dropdown-item py-2 bg-secondary-subtle">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('assets/images/user.jpg') }}" alt=""
                                        class="thumb-md rounded-circle">
                                </div>
                                <div class="flex-grow-1 ms-2 text-truncate align-self-center">
                                    <h6 class="my-0 fw-medium text-dark fs-13">
                                        {{ Auth::User()->identifiant ?? 'Utilisateur' }}
                                    </h6>
                                    <small class="text-muted">Gestionnaire de caisse</small>
                                </div>
                            </div>
                            <div class="dropdown-divider mb-0"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf <!-- Jeton de sécurité obligatoire pour les requêtes POST dans Laravel -->

                                <button type="submit" class="dropdown-item text-danger"
                                    style="border: none; background: none; width: 100%; text-align: left;">
                                    <i class="las la-power-off fs-18 me-1"></i> Déconnexion
                                </button>
                            </form>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>
    </div>

    <!-- Top Bar End -->
    <!-- leftbar-tab-menu -->
    <div class="startbar d-print-none">
        <!--start brand-->
        <div class="brand">
            <a href="#" class="logo">
                <span>
                    <img src="assets/images/logo-sm.png" alt="logo-small" class="logo-sm">
                </span>
                <span class="">
                    <img src="assets/images/logo-light.png" alt="logo-large" class="logo-lg logo-light">
                    <img src="assets/images/logo-dark.png" alt="logo-large" class="logo-lg logo-dark">
                </span>
            </a>
        </div>
        <!--end brand-->
        <!--start startbar-menu-->
        <div class="startbar-menu">
            <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
                <div class="d-flex align-items-start flex-column w-100">
                    <!-- Navigation -->
                    <ul class="navbar-nav mb-auto w-100">
                        <li class="menu-label mt-2">
                            <span>Navigation</span>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <iconify-icon icon="solar:dashboard-bold-duotone" class="menu-icon"></iconify-icon>
                                <span>Tableau de bord</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('caisse.index') }}">
                                <iconify-icon icon="solar:transfer-horizontal-bold-duotone"
                                    class="menu-icon"></iconify-icon>
                                <span>Liste des caisses</span>
                            </a>
                        </li>

                        {{-- <li class="nav-item">
                            <a class="nav-link" href="#">
                                <iconify-icon icon="solar:history-bold-duotone" class="menu-icon"></iconify-icon>
                                <span>Historique</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <iconify-icon icon="solar:lock-bold-duotone" class="menu-icon"></iconify-icon>
                                <span>Clôture de caisse</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <iconify-icon icon="solar:scale-bold-duotone" class="menu-icon"></iconify-icon>
                                <span>Rapprochement bancaire</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <iconify-icon icon="solar:users-group-rounded-bold-duotone"
                                    class="menu-icon"></iconify-icon>
                                <span>Utilisateurs</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="#">
                                <iconify-icon icon="solar:document-bold-duotone" class="menu-icon"></iconify-icon>
                                <span>Rapports</span>
                            </a>
                        </li> --}}
                    </ul>

                </div>
            </div><!--end startbar-collapse-->
        </div><!--end startbar-menu-->
    </div><!--end startbar-->
    <div class="startbar-overlay d-print-none"></div>

    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row gap-0">
                <div class="col-sm-12">
                    <div class="page-title-content d-sm-flex justify-content-sm-between align-items-center">
                        <h4 class="page-title mt-3 mt-md-0"> @yield('title3')</h4>
                        <div class="">
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="#">@yield('title2')</a>
                                </li><!--end nav-item-->
                                <li class="breadcrumb-item active">@yield('title')</li>
                            </ol>
                        </div>
                    </div><!--end page-title-box-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </div><!--end page title-->

    @yield('content')


    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/iconify-icon/iconify-icon.min.js') }}"></script>
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/finance.init.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>




<!-- Mirrored from phplaravel-1384472-5380003.cloudwaysapps.com/index by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 12 Jun 2025 01:59:29 GMT -->

</html>
