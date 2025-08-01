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
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>
    @php
        $mesModules = \App\Helpers\DateHelper::dossier_info();
    @endphp
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
                    <li class="mx-2">
                        <button class="btn btn-outline-primary"
                            data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasWithBackdrop"
                            aria-controls="offcanvasWithBackdrop"
                        >{{ Auth::User()->societe->nom_societe }}</button>
                    </li>
                    <li class="mx-2 welcome-text">
                        <h5 class="mb-0 fw-semibold text-truncate">Salut, 
                            {{ Auth::User()->username ?? 'Utilisateur' }}</h5>
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
                        <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#" role="button">
                            <img src="{{ Auth::user()->societe && Auth::user()->societe->logo 
                                        ? asset('storage/' . Auth::user()->societe->logo) 
                                        : asset('assets/images/user.jpg') }}" 
                                 alt="Logo" class="thumb-md rounded shadow">
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
                        <li class="clinicdropdown bg-light rounded shadow">
                            <a href="javascript:void(0);" class="nav-link dropdown-toggle arrow-none d-flex px-2">
                                <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('assets/images/user.jpg') }}" 
                                     class="img-fluid me-3" alt="Profile" style="width: 40px; height: 40px; border-radius: 50%;" />
                                <div class="user-names">
                                    <h5 class="mb-1" style="margin: 0; font-size: 16px; font-weight: 600;">
                                        {{ Auth::user()->name ?? 'Nom utilisateur' }}
                                    </h5>
                                    <h6 style="margin: 0; font-size: 14px; font-weight: 400; color: #6c757d;">
                                        {{ Auth::user()->role ?? 'Rôle' }}
                                    </h6>
                                </div>
                            </a>
                        </li>
                        
                    </ul>

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
    <!-- Loader global -->
    <div id="global-loader"
        class="position-fixed top-0 start-0 w-100 h-100 d-flex flex-column justify-content-center align-items-center bg-white"
        style="z-index: 2000;">
        <i class="fas fa-spinner fa-spin fs-3 text-primary"></i>
        <small class="mt-2">Chargement ...</small>
    </div>


    <style>
        .mouvement-annule td {
            opacity: 0.6;
        }
    </style>
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row gap-0">
                <div class="col-sm-12">
                    <div class="page-title-content d-sm-flex justify-content-sm-between align-items-center">
                        <h4 class="page-title mt-3 mt-md-0"> @yield('title3')</h4>
                        @if (session('success'))
                            <div class="alert border-success text-success alert-dismissible fade show rounded-pill mt-2"
                                role="alert">
                                <div
                                    class="d-inline-flex justify-content-center align-items-center thumb-xxs bg-success rounded-circle mx-auto me-1">
                                    <i class="fas fa-check align-self-center mb-0 text-white "></i>
                                </div>
                                <strong>Succès !</strong> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert border-danger text-danger alert-dismissible fade show mb-0 mt-2"
                                role="alert">
                                <div
                                    class="d-inline-flex justify-content-center align-items-center thumb-xxs bg-danger rounded-circle mx-auto me-1">
                                    <i class="fas fa-xmark align-self-center mb-0 text-white "></i>
                                </div>
                                <strong>Erreur !</strong> {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="alert border-danger text-danger alert-dismissible fade show mb-0"
                                role="alert">
                                <div
                                    class="d-inline-flex justify-content-center align-items-center thumb-xxs bg-danger rounded-circle mx-auto me-1">
                                    <i class="fas fa-xmark align-self-center mb-0 text-white "></i>
                                </div>
                                <strong>Erreur !</strong> {{ $errors->first() }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
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
    @include('components.content_application.create_categorie_offcanvas')
    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasWithBackdrop"
    aria-labelledby="offcanvasWithBackdropLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title ps-3 mb-3" id="offcanvasWithBackdropLabel"
            style="border-left: 5px solid #05436b; color: #333;">
            Actuellement sur : {{ Str::limit($societe_nom, 15, '...') }}
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div> <!-- end offcanvas-header-->
    @php
        $lesSocietes = \App\Helpers\DateHelper::dossier_info();
    @endphp
    <div class="offcanvas-body">
        <div class="p-3" style="overflow-y: auto;">
            <div class="row row-cols-3 g-2">
                @foreach ($lesSocietes['societes'] as $societe)
                    <div class="col text-center  card-hover-zoom">
                        <a href="{{ route('change_societe', $societe->id) }}"
                            class="text-decoration-none text-dark d-block">
                            <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                                style="width: 80px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                                <img src="{{ asset('storage/' . $societe->logo) }}"
                                    alt="{{ $societe->nom_societe }}" class="img-fluid rounded"
                                    style="width: 80px;height: 70px; object-fit: contain;border-radius: 5px;border-radius: 20px">
                            </div>
                            <small class="fw-medium d-block text-truncate"
                                title="{{ $societe->nom_societe }}">{{ $societe->nom_societe }}</small>
                        </a>
                    </div>
                @endforeach
                <style>
                    .card-hover-zoom {
                        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    }

                    .card-hover-zoom:hover {
                        transform: scale(1.15);
                        z-index: 2;
                    }
                </style>
            </div>
            
        </div>
    </div> <!-- end offcanvas-body-->
</div>
    <script>
        // ✅ Fonction pour créer un cookie
        function setCookie(name, value, days) {
            const date = new Date();
            date.setTime(date.getTime() + (days*24*60*60*1000));
            document.cookie = name + "=" + value + "; expires=" + date.toUTCString() + "; path=/";
        }
    
        // ✅ Fonction pour lire un cookie
        function getCookie(name) {
            const nameEQ = name + "=";
            const ca = document.cookie.split(';');
            for (let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) === ' ') c = c.substring(1);
                if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length);
            }
            return null;
        }
    
        // ✅ Appliquer le thème au chargement
        document.addEventListener("DOMContentLoaded", function() {
            const theme = getCookie("theme");
            if (theme === "dark") {
                document.body.classList.add("dark-mode");
            } else {
                document.body.classList.remove("dark-mode");
            }
        });
    
        // ✅ Gestion du clic pour basculer le thème
        document.getElementById("light-dark-mode").addEventListener("click", function () {
            if (document.body.classList.contains("dark-mode")) {
                document.body.classList.remove("dark-mode");
                setCookie("theme", "light", 365);
            } else {
                document.body.classList.add("dark-mode");
                setCookie("theme", "dark", 365);
            }
        });
    </script>
    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const loader = document.getElementById("global-loader");

            // Quand toute la page est chargée (HTML + CSS + images)
            window.addEventListener("load", () => {
                loader.style.opacity = "0"; // transition en douceur
                loader.style.pointerEvents = "none"; // évite de bloquer les clics pendant la transition

                setTimeout(() => {
                    loader.style.display = "none"; // cache complètement le loader
                }, 500); // délai pour la transition
            });
        });
    </script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/iconify-icon/iconify-icon.min.js') }}"></script>
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/pages/finance.init.js') }}"></script>
    <script src="{{ asset('assets/js/pages/form-validation.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
</body>




<!-- Mirrored from phplaravel-1384472-5380003.cloudwaysapps.com/index by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 12 Jun 2025 01:59:29 GMT -->

</html>
