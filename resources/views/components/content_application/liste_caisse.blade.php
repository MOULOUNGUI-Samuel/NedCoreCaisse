@extends('layouts.app')

@section('title3', 'Gestion des caisses')
@section('title2', 'Caisses')
@section('title', 'Liste des caisses')

@section('content')
    <style>
    /* 1. Le conteneur principal pour le défilement */
    .caisse-scroll-container {
        display: flex;
        overflow-x: auto;
        overflow-y: hidden;
        padding-bottom: 15px; /* Espace pour la barre de défilement */
        white-space: nowrap;
        scrollbar-width: thin; /* Pour Firefox */
        scrollbar-color: #6c757d #e9ecef; /* Pour Firefox */
    }

    /* Style de la barre de défilement pour Chrome, Safari, etc. */
    .caisse-scroll-container::-webkit-scrollbar {
        height: 8px;
    }
    .caisse-scroll-container::-webkit-scrollbar-track {
        background: #e9ecef;
        border-radius: 4px;
    }
    .caisse-scroll-container::-webkit-scrollbar-thumb {
        background: #6c757d;
        border-radius: 4px;
    }
    .caisse-scroll-container::-webkit-scrollbar-thumb:hover {
        background: #5c636a;
    }

    /* 2. Le "wrapper" qui contient chaque carte individuelle */
    .caisse-wrapper {
        /* Par défaut (mobile first), une carte prend 90% de la largeur de l'écran */
        flex: 0 0 90%; 
        /* On retire la classe me-2 pour un contrôle total ici */
        margin-right: 1rem;
    }

    /* 3. Style de la carte active (non modifiée) */
    .caisse-card.active {
        background-color: #e7f1ff; /* Un fond bleu léger pour la sélection */
        border: 1px solid #0d6efd;
    }
    
    /* 4. Media Query pour les écrans plus grands (tablettes et bureau) */
    @media (min-width: 768px) {
        .caisse-wrapper {
            /* Sur les grands écrans, on fixe la largeur de la carte */
            flex-basis: 400px;
        }
    }
</style>
    <div class="page-wrapper">
        <!-- Page Content-->
        <div class="page-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-12 mb-3">
                        <nav class="navbar navbar-expand-lg navbar-light">
                            <div class="container-fluid">
                                @if (Auth::user()->super_admin === 1 || Auth::user()->role === 'Administrateur')
                                <button type="button" class="btn rounded-pill btn-light me-3 mb-2" data-bs-toggle="offcanvas"
                                    data-bs-target="#myOffcanvas" aria-controls="myOffcanvas">Créer une caisse <i
                                        class="fas fa-plus-circle"></i></button>
                                        
                                    <button type="button" class="btn rounded-pill btn-light me-3 text-dark mb-2"
                                        data-bs-toggle="offcanvas" data-bs-target="#myOffcanvasC"
                                        aria-controls="myOffcanvas">
                                        Créer une catégorie
                                        <i class="fas fa-plus-circle"></i>
                                    </button>
                                @endif
                                <button type="button" class="btn rounded-pill btn-light me-3 text-dark mb-2"
                                    data-bs-toggle="modal" data-bs-target="#exampleModalFullscreen">
                                    Libellés de mouvements
                                    <i class="fas fa-plus-circle"></i>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                        <li class="nav-item dropdown">
                                            <button type="button" class="btn rounded-pill btn-light" href="#"
                                                id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Filtre des caisses <i class="la la-angle-down"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                <li><a class="dropdown-item" href="#"><i
                                                            class="las la-list fs-18 me-2"></i> Toutes les caisses</a></li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li><a class="dropdown-item text-success" href="#"><i
                                                            class="las la-circle fs-18 me-2 text-success"></i> Caisse
                                                        active</a></li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li><a class="dropdown-item text-danger" href="#"><i
                                                            class="las la-circle fs-18 me-2 text-danger"></i> Caisse
                                                        inactive</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div><!--end container-->
                        </nav> <!--end nav-->
                    </div> <!--end col-->
                </div><!--end row-->
                @include('components.content_application.create_caisse_offcanvas', ['users' => $users])



                {{-- Conteneur pour les cartes de caisse --}}

                <div class="caisse-scroll-container">

                    {{-- On boucle sur les caisses ici --}}
                    @forelse ($caisses as $caisse)
                        <!-- Voici le wrapper qui gère la taille et l'espacement de chaque carte -->
                        <div class="caisse-wrapper">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="text-dark fw-semibold mb-2 fs-18 text-truncate">
                                                    <i class="fas fa-cash-register fs-22"></i>
                                                    {{ Str::limit($caisse->libelle_caisse, 22, '...') }}
                                                </p>
                                                @if (Auth::user()->id === $caisse->user_id)
                                                    <div class="dropdown">
                                                        <a class="btn btn-sm btn-outline-light dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="fas fa-ellipsis-v me-2"></i>Actions
                                                        </a>
                                                        <ul class="dropdown-menu mb-3" aria-labelledby="dropdownMenuButton">

                                                            <li><a class="dropdown-item"
                                                                    href="{{ route('operations', $caisse->id) }}"><i
                                                                        class="fas fa-plus-circle fa-fw me-2"></i>Opération</a>
                                                            </li>
                                                            <li><a class="dropdown-item" href="#"
                                                                    data-bs-toggle="offcanvas"
                                                                    data-bs-target="#myOffcanvasEdit"><i
                                                                        class="fas fa-edit fa-fw me-2"></i>Modifier</a>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li><a class="dropdown-item text-primary" href="#"><i
                                                                        class="fas fa-archive fa-fw me-2"></i>Archiver</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    @else
                                                    <span class="badge rounded-pill bg-transparent border border-primary text-primary fs-12">{{ Str::limit($caisse->societe->nom_societe, 12, '...')}}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- ========================================================== -->
                                    <!--   VOTRE BLOC DE LOGIQUE NON MODIFIÉ COMMENCE ICI        -->
                                    <!-- ========================================================== -->
                                    <div class="caisse-card px-3 py-1 rounded {{ $caisse->id === $activeCaisse->id ? 'active' : '' }}"
                                        data-caisse-id="{{ $caisse->id }}">
                                        <div class="row">
                                            <div class="col">
                                                <h4 class="text-dark mb-0 fw-semibold fs-20">
                                                    {{ number_format($caisse->seuil_encaissement, 0, ',', ' ') }}
                                                </h4>
                                            </div>
                                            
                                        </div>

                                        <!-- ========================================================== -->
                                        <!--   VOTRE BLOC DE LOGIQUE NON MODIFIÉ SE TERMINE ICI          -->
                                        <!-- ========================================================== -->

                                        <!-- Reste de la carte (informations utilisateur, barre de progression) -->
                                        <div class="d-flex justify-content-between align-items-center pt-3">
                                            <div class="d-flex align-items-center">
                                                <img src="{{ $caisse->user->photo ? asset('storage/' . $caisse->user->photo) : asset('assets/images/user.jpg') }}" height="34"
                                                    class="me-3 align-self-center rounded border bg-white" alt="...">
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 mb-n1 fs-14">
                                                        {{ Str::limit($caisse->user->name . ' ' . $caisse->user->username, 15, '...') }}
                                                    </h6>
                                                    <p class="mb-0 text-truncate fs-14 text-muted">
                                                        {{ Str::limit($caisse->user->role, 15, '...') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-lock text-success fs-20 me-2"></i>
                                                <div class="flex-grow-1 text-truncate">
                                                    <h6 class="m-0 mb-n1 fs-13">Max autorisé</h6>
                                                    <a href="#"
                                                        class="fs-13 text-primary">{{ number_format($caisse->seuil_maximum, 0, ',', ' ') }}</a>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- <div class="progress rounded-0 mt-2" style="height: 12px">
                                            <div class="progress-bar fs-12 bg-success" role="progressbar"
                                                style="width: {{ $caisse->pourcentVersements }}%;"
                                                aria-valuenow="{{ $caisse->pourcentVersements }}">
                                                {{ round($caisse->pourcentVersements) }}%
                                            </div>
                                            <div class="progress-bar fs-12 bg-danger" role="progressbar"
                                                style="width: {{ $caisse->pourcentRetraits }}%;"
                                                aria-valuenow="{{ $caisse->pourcentRetraits }}">
                                                {{ round($caisse->pourcentRetraits) }}%
                                            </div>
                                        </div> --}}
                                    </div>
                                </div><!--end card-body-->
                            </div><!--end card-->
                        </div><!--end caisse-wrapper-->
                        @include('components.content_application.create_caisseModif_offcanvas', [
                            'caisse' => $caisse,
                            'users' => $users,
                        ])
                        {{-- On inclut le formulaire de création de mouvement --}}
                    @empty
                        <div class="alert alert-info">Aucune caisse n'a été trouvée pour votre compte.</div>
                    @endforelse
                </div>
               

            </div>
                {{-- Conteneur initial avec les mouvements de la première caisse --}}
                <div id="mouvements-container" class="mt-4">
                    @include('components.content_application._mouvements_table', [
                        'mouvements' => $mouvements,
                    ])
                </div>
        </div>
        <!-- end page content -->
    </div>
            <!--end Endbar-->
            <div class="endbar-overlay d-print-none"></div>

    <style>
        .caisse-card {
            border: 2px solid transparent;
            transition: border-color 0.3s;
        }

        .caisse-card.active {
            border-color: #0c3169;
            /* Couleur de bootstrap 'primary' */
        }
    </style>

@endsection
