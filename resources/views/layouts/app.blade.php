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
    @php
        $lesSocietes = \App\Helpers\DateHelper::dossier_info();
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
                    <li class="mx-2">
                        <button class="btn btn-outline-primary"
                            @if (count($lesSocietes['societes']) > 1 || Auth::user()->role !== 'Administrateur') data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasWithBackdrop"
                            aria-controls="offcanvasWithBackdrop" @endif>{{ $societe_nom }}</button>
                    </li>
                    <li class="mx-2 welcome-text">
                        <h5 class="mb-0 fw-semibold text-truncate">Salut,
                            {{ Auth::User()->username ?? 'Utilisateur' }}</h5>
                        <!-- <h6 class="mb-0 fw-normal text-muted text-truncate fs-14">Voici un aperçu de vos caisses.</h6> -->
                    </li>
                </ul>

                <ul class="topbar-item list-unstyled d-inline-flex align-items-center mb-0">
                    <li class="hide-phone app-search me-5">
                        <a class="nav-link dropdown-toggle arrow-none nav-icon" href="https://nedcore.net/liste_modules"
                            role="button">
                            <img src="{{ asset('assets/images/logo_nedcore.JPG') }}" alt="Logo" class="shadow"
                                width="100">
                        </a>
                    </li>


                    <li class="topbar-item">
                        <a class="nav-link nav-icon" href="javascript:void(0);" id="light-dark-mode">
                            <iconify-icon icon="solar:moon-bold-duotone" class="dark-mode fs-20"></iconify-icon>
                            <iconify-icon icon="solar:sun-2-bold-duotone" class="light-mode fs-20"></iconify-icon>
                        </a>
                    </li>



                    <li class="dropdown topbar-item">
                        <a class="nav-link dropdown-toggle arrow-none nav-icon" data-bs-toggle="dropdown" href="#"
                            role="button">
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
        {{-- <div class="brand mt-3" style="margin-left: 60px;">
            <a href="#" class="logo">
                <span>
                    <img src="{{'assets/images/caisse.jpg'}}" alt="logo-small" class="logo-sm  rounded shadow" width="50" >
                </span>
                <span class="" >
                    <img src="{{'assets/images/caisse.jpg'}}" alt="logo-large" class="logo-lg logo-light  rounded shadow" width="100" >
                    <img src="{{'assets/images/caisse.jpg'}}" alt="logo-large" class="logo-lg logo-dark  rounded shadow" width="100" >
                </span>
            </a>
        </div> --}}
        <!--end brand-->
        <!--start startbar-menu-->
        <div class="startbar-menu">
            <div class="startbar-collapse" id="startbarCollapse" data-simplebar>
                <div class="d-flex align-items-start flex-column w-100">
                    <!-- Navigation -->
                    <ul class="navbar-nav mb-auto w-100 mt-3">
                        <li class="clinicdropdown bg-light rounded shadow">
                            <a href="javascript:void(0);" class="nav-link dropdown-toggle arrow-none d-flex px-2">
                                <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : asset('assets/images/user.jpg') }}"
                                    class="img-fluid me-3" alt="Profile"
                                    style="width: 40px; height: 40px; border-radius: 50%;" />
                                <div class="user-names">
                                    <h5 class="mb-1" style="margin: 0; font-size: 16px; font-weight: 600;">
                                        {{ Str::limit(Auth::user()->name, 15, '...') }}
                                    </h5>
                                    <h6 style="margin: 0; font-size: 14px; font-weight: 400; color: #6c757d;">
                                        {{ Auth::user()->role ?? 'Rôle' }}
                                    </h6>
                                </div>
                            </a>
                        </li>

                    </ul>

                    <ul class="navbar-nav mb-auto w-100">
                        <li class="menu-label mt-2 fs-17">
                            <span>Navigation</span>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link fs-17 {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                                href="{{ route('dashboard') }}">
                                <iconify-icon icon="solar:home-bold-duotone" class="menu-icon"></iconify-icon>
                                <span>Tableau de bord</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link fs-17 {{ request()->routeIs('caisse.index', 'operations') ? 'active' : '' }}"
                                href="{{ route('caisse.index') }}">
                                <iconify-icon icon="solar:transfer-horizontal-bold-duotone"
                                    class="menu-icon"></iconify-icon>
                                <span>Liste des caisses</span>
                            </a>
                        </li>
                        @if (Auth::user()->super_admin === 1 || Auth::user()->role === 'Administrateur')
                            <li class="nav-item">
                                <a class="nav-link fs-17 {{ request()->routeIs('user.index') ? 'active' : '' }}"
                                    href="{{ route('user.index') }}">
                                    <iconify-icon icon="solar:users-group-rounded-bold-duotone"
                                        class="menu-icon me-1"></iconify-icon>
                                    <span>Gestion des utilisateurs</span>
                                </a>
                            </li>
                        @endif

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
    @include('components.content_application._libeller_mouvement')

    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasWithBackdrop"
        aria-labelledby="offcanvasWithBackdropLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title ps-3 mb-3" id="offcanvasWithBackdropLabel"
                style="border-left: 5px solid #05436b; color: #333;">
                Actuellement sur : {{ Str::limit($societe_nom, 15, '...') }}
            </h5>
            <button type="button" class="btn-close text-reset fs-22 border border-dark" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div> <!-- end offcanvas-header-->

        <div class="offcanvas-body">
            <div class="p-3" style="overflow-y: auto;">
                <div class="row row-cols-3 g-2">
                    @foreach ($lesSocietes['societes'] as $societe)
                        <div class="col text-center  card-hover-zoom">
                            <a href="{{ route('change_societe', $societe->societe->id) }}"
                                class="text-decoration-none text-dark d-block">
                                <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                                    style="width: 80px;height: 70px; transition: transform 0.3s;border-radius: 5px;">
                                    <img src="{{ asset('storage/' . $societe->societe->logo) }}"
                                        alt="{{ $societe->societe->nom_societe }}" class="img-fluid rounded"
                                        style="width: 80px;height: 70px; object-fit: contain;border-radius: 5px;border-radius: 20px">
                                </div>
                                <small class="fw-medium d-block text-truncate"
                                    title="{{ $societe->societe->nom_societe }}">{{ $societe->societe->nom_societe }}</small>
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

    <div class="modal fade" id="societeConfirmModal" tabindex="-1" aria-labelledby="societeConfirmLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-top">
            <form method="POST" action="{{ route('associer.utilisateur') }}" id="formSocieteConfirm">
                @csrf
                <input type="hidden" name="societe_id" id="modalSocieteId">
                <input type="hidden" name="user_id" id="modalUserId">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirmer l'association</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center">
                        <img src="" id="modalSocieteLogo" class="rounded mb-3" width="80"
                            height="70" style="object-fit: contain;">
                        <p>Voulez-vous vraiment associer <strong><span id="modalSocieteNom"></span></strong> à cet
                            utilisateur ?</p>
                    </div>

                    <div class="modal-footer justify-content-center">
                        <button type="button" class="btn btn-secondary me-3"
                            data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success monBouton" data-loader-target="creer">
                            <i class="las la-check me-2"></i> Confirmer le choix
                        </button>
                        <button type="button" id="creer" class="btn btn-success" style="display: none;"
                            disabled>
                            <i class="fas fa-spinner fa-spin me-2"></i>Traitement...
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        const societeModal = document.getElementById('societeConfirmModal');

        societeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const societeId = button.getAttribute('data-societe-id');
            const userId = button.getAttribute('data-user-id');
            const nom = button.getAttribute('data-societe-nom');
            const logo = button.getAttribute('data-societe-logo');

            // Injecter dans le formulaire
            document.getElementById('modalSocieteId').value = societeId;
            document.getElementById('modalUserId').value = userId;

            // Texte et image dans le modal
            document.getElementById('modalSocieteNom').textContent = nom;
            document.getElementById('modalSocieteLogo').src = logo;
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".date-format").forEach(input => {
                // ✅ Auto-formatage pendant la saisie
                input.addEventListener("input", function() {
                    let value = input.value.replace(/[^0-9]/g,
                        ""); // garder uniquement les chiffres

                    if (value.length > 2 && value.length <= 4) {
                        value = value.slice(0, 2) + "/" + value.slice(2);
                    } else if (value.length > 4) {
                        value = value.slice(0, 2) + "/" + value.slice(2, 4) + "/" + value.slice(4,
                            8);
                    }

                    input.value = value;
                });

                // ✅ Validation du format à la perte de focus
                input.addEventListener("blur", function() {
                    const regex = /^([0-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/\d{4}$/;

                    if (input.value && !regex.test(input.value)) {
                        alert("Veuillez entrer une date valide au format jj/mm/aaaa");
                        input.focus();
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const boutons = document.querySelectorAll('.monBouton');

            boutons.forEach(function(bouton) {
                bouton.addEventListener('click', function() {
                    const formId = bouton.getAttribute('data-button-for');
                    const formulaire = document.querySelector('.monFormulaire[data-form-id="' +
                        formId + '"]');
                    const messageErreur = formulaire.querySelector('.messageErreur');

                    if (!formulaire.checkValidity()) {
                        // Trouver le premier champ invalide
                        const premierChampInvalide = formulaire.querySelector(':invalid');

                        // Récupérer le label associé (si possible)
                        let label = document.querySelector('label[for="' + premierChampInvalide.id +
                            '"]');
                        let texteLabel = label ? label.textContent :
                            'Ce champ'; // Fallback si pas de label

                        // Afficher le message d'erreur
                        messageErreur.textContent = texteLabel.replace('(obligatoire)', '').trim() +
                            ' est obligatoire.';
                        messageErreur.style.display = 'block';

                        // Empêcher la soumission
                        return;
                    }

                    document.querySelectorAll('[data-loader-target-form]').forEach(function(
                        btn) { // Simplification du sélecteur
                        btn.addEventListener('click', function(event) {
                            const targetId = btn.getAttribute(
                                'data-loader-target-form');
                            const loaderBtn = document.getElementById(targetId);

                            // Validation du formulaire (toujours exécutée)
                            const form = btn.closest('form');
                            if (form && !form.checkValidity()) {
                                // Si le formulaire n'est pas valide, empêche l'action par défaut
                                event.preventDefault();
                                event.stopPropagation();
                                form.classList.add(
                                    'was-validated'
                                ); // Ajoute la classe Bootstrap pour afficher les erreurs
                                return; // Ne pas afficher le loader si le formulaire est invalide
                            }

                            // Affichage du loader (uniquement si le formulaire est valide)
                            if (loaderBtn) {
                                btn.style.display = 'none';
                                loaderBtn.style.display = 'inline-block';
                            }
                        });
                    });
                    // Si le formulaire est valide, changer le type du bouton et soumettre le formulaire
                    bouton.type = 'submit';
                    //formulaire.submit(); // Décommenter si tu veux soumettre le formulaire automatiquement
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cible tous les boutons ayant l'attribut data-loader-target
            document.querySelectorAll('[data-loader-target]').forEach(function(btn) { // Simplification du sélecteur
                btn.addEventListener('click', function(event) {
                    const targetId = btn.getAttribute('data-loader-target');
                    const loaderBtn = document.getElementById(targetId);

                    // Validation du formulaire (toujours exécutée)
                    const form = btn.closest('form');
                    if (form && !form.checkValidity()) {
                        // Si le formulaire n'est pas valide, empêche l'action par défaut
                        event.preventDefault();
                        event.stopPropagation();
                        form.classList.add(
                            'was-validated'
                        ); // Ajoute la classe Bootstrap pour afficher les erreurs
                        return; // Ne pas afficher le loader si le formulaire est invalide
                    }

                    // Affichage du loader (uniquement si le formulaire est valide)
                    if (loaderBtn) {
                        btn.style.display = 'none';
                        loaderBtn.style.display = 'inline-block';
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sélectionne tous les champs avec la classe .separateur-nombre
            const inputFields = document.querySelectorAll('.separateur-nombre');

            inputFields.forEach(function(inputField) {
                // Ajoute un écouteur pour mettre à jour les séparateurs en temps réel
                inputField.addEventListener('input', function() {
                    // Supprime tout ce qui n'est pas un chiffre
                    let value = inputField.value.replace(/[^0-9]/g, '');

                    // Ajoute les séparateurs de milliers
                    inputField.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
                });

                // Ajoute un écouteur pour empêcher la saisie de caractères non numériques
                inputField.addEventListener('keydown', function(event) {
                    const allowedKeys = ["Backspace", "Delete", "ArrowLeft", "ArrowRight", "Tab"];
                    // Permet uniquement les chiffres et les touches autorisées
                    if (!/^\d$/.test(event.key) && !allowedKeys.includes(event.key)) {
                        event.preventDefault();
                    }
                });
            });
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
