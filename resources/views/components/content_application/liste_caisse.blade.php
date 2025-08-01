@extends('layouts.app')

@section('title3', 'Gestion des caisses')
@section('title2', 'Caisses')
@section('title', 'Liste des caisses')

@section('content')
    <style>
        .scroll-container {
            overflow-x: auto;
            overflow-y: hidden;
            white-space: nowrap;
            scrollbar-width: auto;
            /* Firefox */
        }

        .scroll-container::-webkit-scrollbar {
            height: 12px;
        }

        .scroll-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .scroll-container::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .scroll-container::-webkit-scrollbar-thumb:hover {
            background: #555;
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
                                <button type="button" class="btn rounded-pill btn-light me-3" data-bs-toggle="offcanvas"
                                    data-bs-target="#myOffcanvas" aria-controls="myOffcanvas">Créer une caisse <i
                                        class="fas fa-plus-circle"></i></button>
                                <button type="button" class="btn rounded-pill btn-light me-3 text-dark"
                                    data-bs-toggle="offcanvas" data-bs-target="#myOffcanvasC" aria-controls="myOffcanvas">
                                    Créer une catégorie
                                    <i class="fas fa-plus-circle"></i>
                                </button>
                                <button type="button" class="btn rounded-pill btn-light me-3 text-info"
                                    data-bs-toggle="offcanvas" data-bs-target="#myOffcanvasM" aria-controls="myOffcanvas">
                                    Créer un libellé de mouvement
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

                <div class="scroll-container d-flex  mt-4" style="gap: 1rem;">
                    {{-- Boucle sur chaque caisse pour créer une carte --}}
                    @forelse ($caisses as $caisse)
                        {{-- On ajoute la classe 'caisse-card' et l'attribut data-caisse-id --}}
                        <div class="card me-2" style="max-width: 430px; flex: 0 0 auto; cursor:pointer;">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="text-dark fw-semibold mb-2 fs-18">
                                                <i class="fas fa-cash-register fs-22"></i>
                                                {{-- On utilise le nom de la caisse --}}
                                                {{ $caisse->libelle_caisse }}
                                            </p>
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-outline-light dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v me-2"></i>Actions
                                                </a>
                                                <ul class="dropdown-menu mb-3" aria-labelledby="dropdownMenuButton">
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ route('operations', $caisse->id) }}">
                                                            <i class="fas fa-plus-circle me-2"></i>Nouvelle opération
                                                        </a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"><i
                                                                class="fas fa-edit me-2"></i>Modifier les informations de
                                                            la
                                                            caisse</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item text-danger" href="#"><i
                                                                class="fas fa-trash-alt me-2"></i>Supprimer la caisse</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"><i
                                                                class="fas fa-archive me-2"></i>Archiver la caisse</a></li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"><i
                                                                class="fas fa-eye me-2"></i>Paramètre de visibilité
                                                            caisse</a>
                                                    </li>
                                                    <li>
                                                        <hr class="dropdown-divider">
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="caisse-card px-3 py-1 rounded {{ $caisse->id === $activeCaisse->id ? 'active' : '' }}"
                                    data-caisse-id="{{ $caisse->id }}">
                                    <div class="row">
                                        <div class="col">
                                            <h4 class="text-dark mb-0 fw-semibold fs-20">
                                                {{ number_format($caisse->seuil_encaissement, 0, ',', ' ') }}
                                            </h4>
                                        </div>
                                        <div class="col-auto align-self-center">
                                            <ul class="list-inline url-list mb-0">
                                                <li class="list-item mb-1">
                                                    <i class="fas fa-arrow-up text-success fs-10"></i>
                                                    <span class="fs-13">Versements :
                                                        {{ number_format($caisse->versements, 0, ',', ' ') }}</span>
                                                </li>
                                                <li class="list-item mb-1">
                                                    <i class="fas fa-arrow-down text-danger fs-10"></i>
                                                    <span class="fs-13">Retraits :
                                                        {{ number_format($caisse->retraits, 0, ',', ' ') }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center py-2">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('assets/images/user.jpg') }}" height="34"
                                                class="me-3 align-self-center rounded border bg-white" alt="...">
                                            <div class="flex-grow-1 text-truncate">
                                                <h6 class="m-0 mb-n1 fs-13">{{ $caisse->user->name }}
                                                    {{ $caisse->user->username }}</h6>
                                                <p class="mb-0 text-truncate fs-13 text-muted">{{ $caisse->user->email }}
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

                                    {{-- Barre de progression dynamique --}}
                                    <div class="progress rounded-0 mt-2" style="height: 12px">
                                        <div class="progress-bar fs-9 bg-success" role="progressbar"
                                            style="width: {{ $caisse->pourcentVersements }}%;"
                                            aria-valuenow="{{ $caisse->pourcentVersements }}">
                                            {{ round($caisse->pourcentVersements) }}%
                                        </div>

                                        <div class="progress-bar fs-9 bg-danger" role="progressbar"
                                            style="width: {{ $caisse->pourcentRetraits }}%;"
                                            aria-valuenow="{{ $caisse->pourcentRetraits }}">
                                            {{ round($caisse->pourcentRetraits) }}%
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        {{-- @include('components.content_application.create_mouvement_offcanvas', [
                            'caisse' => $caisse,
                        ]) --}}
                    @empty
                        <div class="alert alert-info">Aucune caisse n'a été trouvée pour votre compte.</div>
                    @endforelse
                </div>

                {{-- Conteneur pour les mouvements de caisse --}}

                {{-- On inclut le tableau pour l'affichage initial --}}

                {{-- Conteneur initial avec les mouvements de la première caisse --}}
                <div id="mouvements-container" class="mt-4">
                    @include('components.content_application._mouvements_table', [
                        'mouvements' => $mouvements,
                    ])
                </div>

            </div>


            <!--Start Rightbar-->
            <!--Start Endbar-->
            @include('layouts.lateralContent')

            <!--end Endbar-->
            <div class="endbar-overlay d-print-none"></div>

            <!--end Rightbar-->
            <!--Start Footer-->

            <footer class="footer text-center text-sm-start d-print-none">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 px-0">
                            <div class="card mb-0 rounded-bottom-0 border-0">
                                <div class="card-body">
                                    <p class="text-muted mb-0">
                                        ©
                                        <script>
                                            document.write(new Date().getFullYear())
                                        </script>
                                        Materialy
                                        <span class="text-muted d-none d-sm-inline-block float-end">
                                            Design with
                                            <i class="iconoir-heart-solid text-danger align-middle"></i>
                                            by Mannatthemes</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>

            <!--end footer-->
        </div>
        <!-- end page content -->
    </div>
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
