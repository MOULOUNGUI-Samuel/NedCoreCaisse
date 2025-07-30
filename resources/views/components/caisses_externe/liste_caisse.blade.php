@extends('layouts.appexterne')

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
                {{-- <div class="row justify-content-center">
                    <div class="col-md-12 mb-3">
                        <nav class="navbar navbar-expand-lg navbar-light">
                            <div class="container-fluid">
                                <button type="button" class="btn rounded-pill btn-light me-3" data-bs-toggle="offcanvas"
                                    data-bs-target="#myOffcanvas">Créer une caisse <i
                                        class="fas fa-plus-circle"></i></button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <div class="me-3">
                                        <input type="radio" class="btn-check" name="options-outlined"
                                            id="success-outlined" autocomplete="off" checked>
                                        <label class="btn btn-outline-primary rounded-pill" for="success-outlined"><i
                                                class="las la-circle fs-18 me-2"></i>Afficher les caisses normales</label>

                                        <input type="radio" class="btn-check" name="options-outlined" id="danger-outlined"
                                            autocomplete="off">
                                        <label class="btn btn-outline-primary rounded-pill" for="danger-outlined"><i
                                                class="las la-circle fs-18 me-2"></i>Afficher les caisses collecte</label>
                                    </div>
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
                                    <form class="d-flex">
                                        <div class="input-group">
                                            <input type="text" class="form-control shadow"
                                                placeholder="Tapez votre recherche..." aria-label="Recipient's username"
                                                aria-describedby="basic-addon2">
                                            <button class="btn btn-soft-secondary shadow" type="button"
                                                id="button-addon2"><i class="fas fa-search"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div><!--end container-->
                        </nav> <!--end nav-->
                    </div> <!--end col-->
                </div><!--end row--> --}}

                <div class="offcanvas offcanvas-start" tabindex="-1" id="myOffcanvas" aria-labelledby="offcanvasLabel">
                    <div class="offcanvas-header">
                        <h4 class="offcanvas-title text-primary" id="offcanvasLabel"><i class="fas fa-plus-circle me-2"></i>
                            Création d'une caisse</h4>
                        <button type="button" class="btn-close fs-28 text-primary" data-bs-dismiss="offcanvas"
                            aria-label="Fermer"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="card p-3">
                            <form id="form-creation-caisse" class="form">
                                <div class="text-end mb-2">
                                    <button type="submit" class="btn btn-light rounded-pill w-50 shadow">Enregistrer
                                    </button>
                                </div>
                                <div class="mb-3">
                                    <label for="libelle_compte" class="form-label fs-15">Libellé du compte</label>
                                    <input class="form-control shadow" type="text" id="libelle_compte"
                                        placeholder="Libellé du compte">
                                </div>

                                <div class="mb-3">
                                    <div class="form-check form-switch form-switch-primary">
                                        <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault">
                                        <label class="form-check-label fs-15" for="flexSwitchCheckDefault"
                                            style="cursor: pointer">
                                            Est une caisse de collecte
                                        </label>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fs-15">Limiter le solde du compte</label>
                                    <div class="me-3 d-flex">
                                        <input type="radio" class="btn-check" name="limiter_solde"
                                            id="limiter_solde_non" autocomplete="off" checked>
                                        <label class="btn btn-outline-primary rounded-pill w-50 btn-sm me-3"
                                            for="limiter_solde_non"><i class="las la-circle fs-18 me-2"></i>Non
                                            (Illimité)</label>

                                        <input type="radio" class="btn-check" name="limiter_solde"
                                            id="limiter_solde_oui" autocomplete="off">
                                        <label class="btn btn-outline-primary rounded-pill w-50 btn-sm"
                                            for="limiter_solde_oui"><i class="las la-circle fs-18 me-2"></i>Oui</label>
                                    </div>
                                </div>
                                <div class="mb-3" id="seuil_maximum_div" style="display: none;">
                                    <label for="seuil_maximum" class="form-label fs-15">Seuil maximum du compte</label>
                                    <input class="form-control shadow" type="text" id="seuil_maximum"
                                        placeholder="0,00">
                                </div>


                                <div class="mb-3">
                                    <label class="form-label fs-15">Gestionnaire du compte</label>
                                    <input type="radio" class="btn-check" name="gestionnaire_compte"
                                        id="gestionnaire_compte" autocomplete="off">
                                    <label class="btn btn-outline-primary rounded-pill w-100 btn-sm"
                                        for="gestionnaire_compte">
                                        <i class="las la-users fs-18 me-2"></i> Sélectionner le gestionnaire du compte
                                    </label>
                                </div>

                                <div id="gestionnaire_list" class="card p-2" style="display: none;">
                                    <input type="text" class="form-control shadow mb-2"
                                        id="gestionnaire_compte_recherche" placeholder="Rechercher un gestionnaire">
                                    <div id="gestionnaire_items_container" class="ms-0 px-3" style="max-height:300px;"
                                        data-simplebar>
                                        <!-- item -->
                                        <a href="#" class="dropdown-item py-3 gestionnaire-item" data-id="1"
                                            data-name="Jamie Buckley">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 ">
                                                    <img src="{{ asset('assets/images/users/avatar-2.jpg') }}"
                                                        alt="" class="thumb-md rounded-circle">
                                                </div>
                                                <div class="flex-grow-1 ms-2 text-truncate">
                                                    <h6 class="my-0 fw-normal text-dark fs-13 gestionnaire-name">Jamie
                                                        Buckley</h6>
                                                    <small class="text-success mb-0">Online</small>
                                                </div>
                                                <i class="fas fa-check text-success ms-auto" style="display: none;"></i>
                                            </div>
                                        </a>
                                        <!-- item -->
                                        <a href="#" class="dropdown-item py-3 gestionnaire-item" data-id="2"
                                            data-name="Jamie Forster">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 ">
                                                    <img src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                                                        alt="" class="thumb-md rounded-circle">
                                                </div>
                                                <div class="flex-grow-1 ms-2 text-truncate">
                                                    <h6 class="my-0 fw-normal text-dark fs-13 gestionnaire-name">Jamie
                                                        Forster</h6>
                                                    <small class="text-muted mb-0">Last seen at 11:00 am</small>
                                                </div>
                                                <i class="fas fa-check text-success ms-auto" style="display: none;"></i>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                <input type="hidden" name="selected_gestionnaire_id" id="selected_gestionnaire_id">

                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const radioGestionnaire = document.getElementById('gestionnaire_compte');
                                        const radioGestionnaireLabel = document.querySelector('label[for="gestionnaire_compte"]');
                                        const blocListe = document.getElementById('gestionnaire_list');
                                        const searchInput = document.getElementById('gestionnaire_compte_recherche');
                                        const gestionnaireItems = document.querySelectorAll('.gestionnaire-item');
                                        const selectedGestionnaireInput = document.getElementById('selected_gestionnaire_id');

                                        // Toggle visibility of the list
                                        radioGestionnaire.addEventListener('change', () => {
                                            blocListe.style.display = radioGestionnaire.checked ? 'block' : 'none';
                                        });

                                        // Handle search/filter
                                        searchInput.addEventListener('input', function() {
                                            const filter = this.value.toLowerCase();
                                            gestionnaireItems.forEach(item => {
                                                const name = item.dataset.name.toLowerCase();
                                                item.style.display = name.includes(filter) ? '' : 'none';
                                            });
                                        });

                                        // Handle selection
                                        gestionnaireItems.forEach(item => {
                                            item.addEventListener('click', function(e) {
                                                e.preventDefault();

                                                // Remove selection from all items
                                                gestionnaireItems.forEach(i => {
                                                    i.classList.remove('bg-light');
                                                    i.querySelector('.fa-check').style.display = 'none';
                                                });

                                                // Add selection to the clicked item
                                                this.classList.add('bg-light');
                                                this.querySelector('.fa-check').style.display = 'inline';

                                                // Update hidden input and button label
                                                const selectedId = this.dataset.id;
                                                const selectedName = this.dataset.name;
                                                selectedGestionnaireInput.value = selectedId;
                                                radioGestionnaireLabel.innerHTML =
                                                    `<i class="las la-user-check fs-18 me-2"></i>${selectedName}`;

                                                // Hide the list after selection
                                                radioGestionnaire.checked = false;
                                                blocListe.style.display = 'none';
                                            });
                                        });
                                    });
                                </script>

                            </form><!--end form-->
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const limiterSoldeRadios = document.querySelectorAll('input[name="limiter_solde"]');
                                const seuilMaximumDiv = document.getElementById('seuil_maximum_div');

                                function toggleSeuilMaximum() {
                                    if (document.getElementById('limiter_solde_oui').checked) {
                                        seuilMaximumDiv.style.display = 'block';
                                    } else {
                                        seuilMaximumDiv.style.display = 'none';
                                    }
                                }

                                limiterSoldeRadios.forEach(radio => {
                                    radio.addEventListener('change', toggleSeuilMaximum);
                                });

                                // Initial check
                                toggleSeuilMaximum();
                            });
                        </script>
                    </div>
                </div>
                <div class="scroll-container d-flex  mt-4" style="gap: 1rem;">
                    {{-- Boucle sur chaque caisse pour créer une carte --}}
                    @forelse ($caisses as $caisse)
                        {{-- On ajoute la classe 'caisse-card' et l'attribut data-caisse-id --}}
                        <div class="card me-2 caisse-card @if ($activeCaisse->idcaisse == $caisse->idcaisse) active @endif"
                            style="max-width: 430px; flex: 0 0 auto; cursor:pointer;"
                            data-caisse-id="{{ $caisse->idcaisse }}">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 mb-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="text-dark fw-semibold mb-2 fs-18">
                                                <i class="fas fa-cash-register fs-22"></i>
                                                {{ $caisse->nomcaisse ?? 'Caisse sans nom' }}
                                            </p>
                                            {{-- <div class="dropdown">
                                                <a class="btn btn-sm btn-outline-light dropdown-toggle" type="button"
                                                    id="dropdownMenuButton" data-bs-toggle="dropdown"
                                                    aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v me-2"></i>Actions
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li>
                                                        <a class="dropdown-item" href="#"
                                                            data-bs-toggle="offcanvas"
                                                            data-bs-target="#myOffcanvasmouvement">
                                                            <i class="fas fa-plus-circle me-2"></i>Créer un mouvement de
                                                            caisse
                                                        </a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"><i
                                                                class="fas fa-exchange-alt me-2"></i>Transfert de fond</a>
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
                                                </ul>
                                            </div> --}}
                                        </div>
                                    </div>
                                    <div class="col">
                                        <h4 class="text-dark mb-0 fw-semibold fs-20">
                                            {{ number_format($caisse->soldecaisse ?? 0, 0, ',', ' ') }}</h4>
                                    </div>
                                    {{-- Ces données sont maintenant spécifiques à chaque caisse --}}
                                    <div class="col-auto align-self-center">
                                        <ul class="list-inline url-list mb-0">
                                            <li class="list-item mb-1">
                                                <i class="fas fa-arrow-up text-success fs-10"></i>
                                                <span class="fs-13">Versements :
                                                    {{ number_format($caisse->totalVersements, 0, ',', ' ') }}</span>
                                            </li>
                                            <li class="list-item mb-1">
                                                <i class="fas fa-arrow-down text-danger fs-10"></i>
                                                <span class="fs-13">Retraits :
                                                    {{ number_format($caisse->totalRetraits, 0, ',', ' ') }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center py-2">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('assets/images/user.jpg') }}" height="34"
                                            class="me-3 align-self-center rounded border bg-white" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            {{-- Détenteur de la caisse dynamique --}}
                                            <h6 class="m-0 mb-n1 fs-13">{{ $caisse->detenteur_caisse ?? 'N/A' }}</h6>
                                            <p class="mb-0 text-truncate fs-13 text-muted">
                                                        {{ $caisse->typeCaisse ?? '' }}</p>
                                        </div>
                                    </div>
                                    {{-- TODO: Le plafond n'est pas dans l'API, il faudra le gérer autrement --}}
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-lock text-success fs-20 me-2"></i>
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0 mb-n1 fs-13">Max... autorisé</h6>
                                            <a href="#" class="fs-13 text-primary">Inconnu</a>
                                        </div>
                                    </div>
                                </div>
                                {{-- La barre de progression utilise maintenant les pourcentages pré-calculés --}}
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
                    @empty
                        <div class="alert alert-info">Aucune caisse n'a été trouvée pour votre compte.</div>
                    @endforelse
                </div>
                <div class="offcanvas offcanvas-start" tabindex="-1" id="myOffcanvasmouvement"
                    aria-labelledby="offcanvasLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title text-primary" id="offcanvasLabel">
                            <i class="fas fa-plus-circle me-2"></i> Création d'un mouvement de compte
                        </h5>
                        <button type="button" class="btn-close fs-28 text-primary" data-bs-dismiss="offcanvas"
                            aria-label="Fermer"></button>
                    </div>
                    <div class="offcanvas-body">
                        <div class="card">
                            <div class="card-body">

                                <!-- Informations du compte (non-éditable) -->
                                <div class="mb-3">
                                    <p class="mb-1 fs-12">Compte N° : IMMO_COMP20221019105904738_4954</p>
                                    <p class="mb-1">Intitulé du compte : FCI B</p>
                                    <h5 class="mb-3"><strong>Solde du compte : <span style="color: #B22222;">7 409
                                                900,00</span></strong></h5>
                                </div>

                                <hr>

                                <!-- Formulaire de création de mouvement -->
                                <form id="form-creation-mouvement" class="form">

                                    <div class="mb-3">
                                        <label for="type_mouvement" class="form-label fs-15">Type de mouvement</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control shadow" id="type_mouvement"
                                                placeholder="Sélectionner la nature de l'opération">
                                            <button class="btn btn-primary" type="button" data-bs-toggle="modal"
                                                data-bs-target="#exampleModalPrimary" id="button-addon2">Choisir</button>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="date_operation" class="form-label fs-15">Date de l'opération</label>
                                        <input class="form-control shadow" type="text" id="date_operation"
                                            value="18/07/2025 16:36">
                                    </div>

                                    <div class="mb-3">
                                        <label for="montant_operation" class="form-label fs-15">Montant de
                                            l'opération</label>
                                        <input class="form-control shadow" type="text" id="montant_operation"
                                            value="0,00">
                                    </div>

                                    <div class="mb-3">
                                        <label for="observation" class="form-label fs-15">Observation</label>
                                        <textarea class="form-control shadow" id="observation" rows="4"></textarea>
                                    </div>

                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary shadow"
                                            style="width: 200px;">Enregistrer
                                        </button>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>
                </div><!--end offcanvas-->
                <div class="modal fade" id="exampleModalPrimary" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalPrimary1" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header bg-primary">
                                <h6 class="modal-title m-0 text-white" id="exampleModalPrimary1">Type de mouvement</h6>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div><!--end modal-header-->
                            <div class="modal-body">
                                <div class="operations-container">
                                    <!-- Barre de recherche et fermeture -->
                                    <div class="search-header d-flex justify-content-between align-items-center">
                                        <div class="input-group">
                                            <input type="text" class="form-control shadow" placeholder="Recherche">
                                        </div>
                                    </div>

                                    <!-- Début du Tableau des Opérations -->
                                    <div class="table-responsive">
                                        <table class="table operations-table align-middle">
                                            <thead class="table-light">
                                                <tr>
                                                    <th scope="col" style="width: 100px;">Type</th>
                                                    <th scope="col">Libellé</th>
                                                    <th scope="col" class="text-end" style="width: 100px;">Actions
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Ligne Sélectionnée -->
                                                <tr class="selected">
                                                    <td><span class="badge-op debit">Débit</span></td>
                                                    <td>Fournitures non stockables - Eau</td>
                                                    <td class="d-flex justify-content-end">
                                                        <button class="btn btn-sm btn-light border"
                                                            data-bs-toggle="tooltip" data-bs-title="Modifier">
                                                            <i class="fas fa-edit text-secondary fs-18"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-light border ms-1"
                                                            data-bs-toggle="tooltip" data-bs-title="Supprimer">
                                                            <i class="fas fa-times-circle text-danger fs-18"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                                <!-- Autres Lignes -->
                                                <tr>
                                                    <td><span class="badge-op debit">Débit</span></td>
                                                    <td>frais de gestion</td>
                                                    <td class="d-flex justify-content-end">
                                                        <button class="btn btn-sm btn-light border"
                                                            data-bs-toggle="tooltip" data-bs-title="Modifier">
                                                            <i class="fas fa-edit text-secondary fs-18"></i>
                                                        </button>
                                                        <button class="btn btn-sm btn-light border ms-1"
                                                            data-bs-toggle="tooltip" data-bs-title="Supprimer">
                                                            <i class="fas fa-times-circle text-danger fs-18"></i>
                                                        </button>
                                                    </td>
                                                </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="collapse" id="collapseExample">
                                        <div class="mb-0 card-body border-dashed border-theme-color rounded">
                                            <p class="mb-0 text-muted">Anim pariatur cliche reprehenderit.</p>
                                        </div>
                                    </div>
                                    <!-- Fin du Tableau des Opérations -->
                                </div>

                            </div><!--end modal-body-->
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="collapse"
                                    href="#collapseExample" aria-expanded="true"
                                    aria-controls="collapseExample">Annuler</button>
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse"
                                    href="#collapseExample" aria-expanded="true" aria-controls="collapseExample"><i
                                        class="fas fa-plus-circle me-2"></i> Nouveau</button>
                            </div><!--end modal-footer-->

                        </div><!--end modal-content-->
                    </div><!--end modal-dialog-->
                </div><!--end modal-->

                {{-- Conteneur qui sera mis à jour dynamiquement par le JavaScript --}}
                <div id="mouvements-container" class="mt-4">
                    {{-- On inclut le tableau pour l'affichage initial --}}
                    @include('components.caisses_externe._mouvements_table')
                </div>
            </div>


            <!--Start Rightbar-->
            <!--Start Endbar-->
            @include('layouts.lateralContentExterne')

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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const caisseCards = document.querySelectorAll('.caisse-card');
            const mouvementsContainer = document.getElementById('mouvements-container');

            caisseCards.forEach(card => {
                card.addEventListener('click', function() {
                    const caisseId = this.dataset.caisseId;

                    // Mettre à jour la classe 'active' pour le feedback visuel
                    caisseCards.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');

                    // Afficher un état de chargement
                    mouvementsContainer.innerHTML =
                        `<div class="text-center p-5"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>`;

                    // URL de la nouvelle route
                    const url = `/caisses/${caisseId}/mouvementsExterne`;

                    // Appel Fetch (AJAX) pour récupérer le HTML du tableau
                    fetch(url)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('La réponse du réseau était incorrecte.');
                            }
                            return response.text(); // On attend du HTML, donc .text()
                        })
                        .then(html => {
                            // On remplace le contenu du conteneur par le nouveau tableau
                            mouvementsContainer.innerHTML = html;
                        })
                        .catch(error => {
                            console.error('Erreur lors de la récupération des mouvements:',
                                error);
                            mouvementsContainer.innerHTML =
                                `<div class="alert alert-danger">Impossible de charger les mouvements. Veuillez réessayer.</div>`;
                        });
                });
            });
        });
    </script>
@endsection
