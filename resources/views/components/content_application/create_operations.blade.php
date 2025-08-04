@extends('layouts.app')

@section('title3', 'Opérations')
@section('title2', 'Caisses')
@section('title', 'Opérations de caisse')

@section('content')

    <div class="page-wrapper">
        <!-- Page Content-->
        <div class="page-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-12 mb-3">
                        <nav class="navbar navbar-expand-lg navbar-light">
                            <div class="container-fluid">
                                <button type="button" class="btn rounded-pill btn-light me-3 text-dark"
                                    data-bs-toggle="offcanvas" data-bs-target="#myOffcanvasC" aria-controls="myOffcanvas">
                                    Créer une catégorie
                                    <i class="fas fa-plus-circle"></i>
                                </button>
                                {{-- <button type="button" class="btn rounded-pill btn-light me-3 text-info"
                                    data-bs-toggle="offcanvas" data-bs-target="#myOffcanvasM" aria-controls="myOffcanvas">
                                    Créer un libellé de mouvement
                                    <i class="fas fa-plus-circle"></i>
                                </button> --}}
                                <button type="button" data-bs-toggle="offcanvas" data-bs-target="#myOffcanvasT"
                                    aria-controls="myOffcanvas" class="btn rounded-pill btn-light me-3 text-danger">
                                    <span class="me-2">Transfert de fond</span>
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                        <li class="nav-item dropdown">
                                            <button type="button" class="btn rounded-pill btn-light" href="#"
                                                id="navbarDropdown" role="button" data-bs-toggle="dropdown"
                                                aria-expanded="false">
                                                Liste des caisses <i class="la la-angle-down"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                @foreach ($caisses as $caisseListe)
                                                    @if ($caisseListe->id !== $caisse->id)
                                                        <li>
                                                            <a class="dropdown-item"
                                                                href="{{ route('operations', $caisseListe->id) }}">
                                                                <i class="las la-wallet fs-18 me-2"></i>
                                                                {{ $caisseListe->libelle_caisse }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <hr class="dropdown-divider">
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="collapse navbar-collapse d-flex justify-content-center align-items-center"
                                    id="navbarSupportedContent">
                                    <div class="d-flex flex align-items-center me-5">
                                        <h5 class="mb-1 text-muted me-3"><strong>Libellé de la caisse :</strong></h5>
                                        <h5 class="mb-0 text-dark"><strong>{{ $caisse->libelle_caisse }}</strong></h5>
                                    </div>
                                    <div class="d-flex flex align-items-center">
                                        <h5 class="mb-1 text-muted me-3"><strong>Solde caisse :</strong></h5>
                                        <h4 class="mb-0 text-primary">
                                            <strong>{{ number_format($caisse->seuil_encaissement, 0, ',', ' ') }}
                                                XAF</strong>
                                        </h4>
                                    </div>
                                </div>
                            </div><!--end container-->
                        </nav> <!--end nav-->
                    </div> <!--end col-->
                </div><!--end row-->
                <div class="row justify-content-center">
                    <div class="col-md-4 col-lg-4">
                        <div class="card">
                            <div class="card-body">

                                <!-- Informations du compte (non-éditable) -->
                                <div
                                    class="mb-3 bg-success text-white p-3 rounded d-flex align-items-center justify-content-between">
                                    <h4 class="text-white">
                                        <i class="fas fa-plus-circle"></i>
                                        Nouveau encaissement
                                    </h4>
                                    <i class="fas fa-arrow-circle-down fs-24"></i>
                                </div>
                                <hr>

                                <!-- ✅ Formulaire de création de mouvement -->
                                <form id="form-creation-mouvement" class="form needs-validation monFormulaire"
                                    data-form-id="form1" novalidate method="POST" action="{{ route('mouvements.store') }}">
                                    @csrf

                                    <div class="messageErreur alert alert-danger my-2" style="display: none;"></div>
                                    <!-- Date de l'opération -->
                                    <div class="mb-3">
                                        <label for="date_mouvement" class="form-label fs-15">Date de l'opération</label>
                                        <input class="form-control shadow date-format" type="text" id="date_mouvement"
                                            name="date_mouvement" value="{{ now()->format('d/m/Y') }}" required>
                                    </div>
                                    <input type="hidden" id="caisse_id" name="caisse_id" value="{{ $caisse->id }}">
                                    <input type="hidden" id="type_mouvement" name="type_mouvement" value="credit">
                                    <!-- Montant -->
                                    <div class="mb-3">
                                        <label for="montant_operation1" class="form-label fs-15">Montant de
                                            l'opération</label>
                                        <input class="form-control shadow separateur-nombre" type="text"
                                            id="montant_operation1" value="{{ old('montant_operation') }}"
                                            name="montant_operation" placeholder="Montant de l'opération" required>
                                    </div>

                                    <!-- ✅ Sélection de la catégorie -->
                                    <div class="mb-3">
                                        <label for="categorie_motif_radio" class="mb-2">
                                            Veuillez sélectionner une catégorie pour l'opération
                                        </label>
                                        <br>
                                        <input type="radio" class="btn-check" id="categorie_motif_radio"
                                            autocomplete="off" name="categorie_motif_radio">
                                        <label id="label-affichage-categorie"
                                            class="btn btn-outline-primary rounded-pill w-100 btn-sm"
                                            for="categorie_motif_radio">
                                            <i class="las la-wallet fs-18 me-2"></i> Sélectionner la catégorie
                                        </label>
                                    </div>

                                    <!-- Liste des catégories -->
                                    <div id="liste-categories" class="card p-2" style="display: none;">
                                        <input type="text" class="form-control shadow mb-2" id="categorie_search"
                                            placeholder="Rechercher une catégorie">

                                        <div id="categorie_container" class="ms-0 px-3" style="max-height:300px;"
                                            data-simplebar>
                                            @if ($categorieMotifsEntrer->isEmpty())
                                                <p class="text-danger">Aucune catégorie d'encaissement disponible.</p>
                                            @else
                                                @foreach ($categorieMotifsEntrer as $categorieMotif)
                                                    <a href="#" class="dropdown-item py-3 categorie-item"
                                                        data-id="{{ $categorieMotif->id }}"
                                                        data-name="{{ $categorieMotif->nom_categorie }}">
                                                        <div class="d-flex align-items-center">
                                                            <div class="flex-shrink-0"><i class="fas fa-wallet fs-18"></i>
                                                            </div>
                                                            <div class="flex-grow-1 ms-2 text-truncate">
                                                                <h6 class="my-0 fw-normal text-dark fs-13">
                                                                    {{ $categorieMotif->nom_categorie }}</h6>
                                                                <small
                                                                    class="text-muted mb-0">{{ $categorieMotif->type_operation }}</small>
                                                            </div>
                                                            <i class="fas fa-check text-success ms-auto"
                                                                style="display: none;"></i>
                                                        </div>
                                                    </a>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    <!-- ✅ Bloc des motifs -->
                                    <div id="bloc-libelle" class="mt-3" style="display:none;">
                                        <label class="form-label fs-15 mb-2">Libellé d'encaissement</label>
                                        <br>
                                        <button type="button" id="btn-choisir-libelle"
                                            class="btn btn-outline-success w-100">
                                            <i class="las la-plus-circle me-2"></i> Choisir un libellé
                                        </button>

                                        <div id="motifs-list" class="card p-2 mt-2" style="display:none;">
                                            <!-- ✅ Barre de recherche -->
                                            <input type="text" id="search-motif" class="form-control mb-2"
                                                placeholder="Rechercher un motif...">

                                            <!-- ✅ Loader -->
                                            <div id="loader" class="text-center my-2" style="display:none;">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Chargement...</span>
                                                </div>
                                            </div>

                                            <!-- ✅ Liste des motifs -->
                                            <ul class="list-group" id="motifs-items"></ul>
                                        </div>
                                    </div>
                                    <div class="mb-3 mt-3">
                                        <label for="observations" class="form-label fs-15">Description de la
                                            caisse</label>
                                        <textarea class="form-control shadow" id="observations" name="observations" rows="3"
                                            placeholder="Description de la caisse"></textarea>
                                    </div>

                                    <!-- ✅ Champs cachés pour envoyer au back -->
                                    <input type="hidden" name="categorie_motif_id" id="selected_categorie_id" required
                                        class="validate-custom" data-error-message="Veuillez sélectionner une catégorie.">
                                    <input type="hidden" name="motif_standard_id" id="selected_motif_id" required
                                        class="validate-custom" data-error-message="Veuillez sélectionner un motif.">
                                    <!-- Bouton d'envoi -->
                                    <button type="button" id="btn_envoie" class="btn btn-success w-100"
                                        data-bs-toggle="modal" data-bs-target="#modalConfirmation" disabled>
                                        <i class="las la-check me-2"></i> Valider l'opération
                                    </button>

                                    <!-- ✅ Modal de confirmation -->
                                    <div class="modal fade" id="modalConfirmation" tabindex="-1"
                                        aria-labelledby="modalConfirmationLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top">
                                            <div class="modal-content shadow-lg">
                                                <div class="modal-header bg-success text-white">
                                                    <h5 class="modal-title fs-16" id="modalConfirmationLabel">Confirmation
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                </div>
                                                <div class="modal-body fs-5 text-center">
                                                    <p>Êtes-vous sûr de vouloir <strong>valider cette opération</strong> ?
                                                    </p>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-secondary me-3"
                                                        data-bs-dismiss="modal">Annuler</button>
                                                    <button type="button" class="btn btn-success monBouton"
                                                        data-button-for="form1" data-loader-target-form="creer">
                                                        <i class="las la-check me-2"></i> Oui, valider
                                                    </button>
                                                    <button type="button" id="creer" class="btn btn-success"
                                                        style="display: none;" disabled>
                                                        <i class="fas fa-spinner fa-spin me-2"></i>Traitement...
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div> <!--end col-->
                    <div class="col-md-4 col-lg-4">
                        <div class="card">
                            <div class="card-body">

                                <!-- Informations du compte (non-éditable) -->
                                <div
                                    class="mb-3 bg-danger text-white p-3 rounded d-flex align-items-center justify-content-between">
                                    <h4 class="text-white">
                                        <i class="fas fa-plus-circle"></i>
                                        Nouveau décaissement
                                    </h4>
                                    <i class="fas fa-arrow-circle-up fs-24"></i>
                                </div>
                                <hr>

                                <!-- ✅ Formulaire de création de mouvement -->
                                <form id="form-creation-mouvement1" class="form needs-validation monFormulaire"
                                    data-form-id="form2" novalidate method="POST"
                                    action="{{ route('mouvements.store') }}">
                                    @csrf
                                    <!-- Date de l'opération -->
                                    <div class="messageErreur alert alert-danger my-2" style="display: none;"></div>

                                    <div class="mb-3">
                                        <label for="date_mouvement1" class="form-label fs-15">Date de l'opération</label>
                                        <input class="form-control shadow date-format" type="text"
                                            id="date_mouvement1" name="date_mouvement"
                                            value="{{ now()->format('d/m/Y') }}" required>
                                    </div>


                                    <!-- Montant -->
                                    <!-- Montant disponible dans la caisse -->
                                    <input type="hidden" id="montant_caisse" value="{{ $caisse->seuil_encaissement }}">
                                    <input type="hidden" id="caisse_id" name="caisse_id" value="{{ $caisse->id }}">
                                    <input type="hidden" id="type_mouvement" name="type_mouvement" value="debit">

                                    <div class="mb-3">
                                        <label for="montant_operation" class="form-label fs-15">Montant de
                                            l'opération</label>
                                        <input class="form-control shadow separateur-nombre" type="text"
                                            id="montant_operation" value="{{ old('montant_operation') }}"
                                            name="montant_operation" placeholder="Montant de l'opération" required>
                                    </div>

                                    <!-- Message d'erreur -->
                                    <div id="alert_montant" class="alert alert-danger d-none">
                                        ⚠️ Le montant saisi dépasse le montant disponible dans la caisse
                                        ({{ number_format($caisse->seuil_encaissement, 0, ',', ' ') }} FCFA).
                                    </div>


                                    <!-- ✅ Sélection de la catégorie -->
                                    <div class="mb-3">
                                        <label for="categorie_motif_radio1" class="mb-2">
                                            Veuillez sélectionner une catégorie pour l'opération
                                        </label>
                                        <br>
                                        <input type="radio" class="btn-check" id="categorie_motif_radio1"
                                            autocomplete="off" name="categorie_motif_radio1">
                                        <label id="label-affichage-categorie1"
                                            class="btn btn-outline-primary rounded-pill w-100 btn-sm"
                                            for="categorie_motif_radio1">
                                            <i class="las la-wallet fs-18 me-2"></i> Sélectionner la catégorie
                                        </label>
                                    </div>

                                    <!-- Liste des catégories -->
                                    <div id="liste-categories1" class="card p-2" style="display: none;">
                                        <input type="text" class="form-control shadow mb-2" id="categorie_search1"
                                            placeholder="Rechercher une catégorie">

                                        <div id="categorie_container" class="ms-0 px-3" style="max-height:300px;"
                                            data-simplebar>
                                            @if ($categorieMotifsSorties->isEmpty())
                                                <p class="text-danger">Aucune catégorie de décaissement disponible.</p>
                                            @else
                                                @foreach ($categorieMotifsSorties as $categorieMotif)
                                                    @if ($categorieMotif->type_operation == 'Sortie')
                                                        <a href="#" class="dropdown-item py-3 categorie-item1"
                                                            data-id="{{ $categorieMotif->id }}"
                                                            data-name="{{ $categorieMotif->nom_categorie }}">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0"><i
                                                                        class="fas fa-wallet fs-18"></i>
                                                                </div>
                                                                <div class="flex-grow-1 ms-2 text-truncate">
                                                                    <h6 class="my-0 fw-normal text-dark fs-13">
                                                                        {{ $categorieMotif->nom_categorie }}</h6>
                                                                    <small
                                                                        class="text-muted mb-0">{{ $categorieMotif->type_operation }}</small>
                                                                </div>
                                                                <i class="fas fa-check text-success ms-auto"
                                                                    style="display: none;"></i>
                                                            </div>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>

                                    <!-- ✅ Bloc des motifs -->
                                    <div id="bloc-libelle1" class="mt-3" style="display:none;">
                                        <label class="form-label fs-15 mb-2">Libellé d'encaissement</label>
                                        <br>
                                        <button type="button" id="btn-choisir-libelle1"
                                            class="btn btn-outline-success w-100">
                                            <i class="las la-plus-circle me-2"></i> Choisir un libellé
                                        </button>

                                        <div id="motifs-list1" class="card p-2 mt-2" style="display:none;">
                                            <!-- ✅ Barre de recherche -->
                                            <input type="text" id="search-motif1" class="form-control mb-2"
                                                placeholder="Rechercher un motif...">

                                            <!-- ✅ loader1 -->
                                            <div id="loader1" class="text-center my-2" style="display:none;">
                                                <div class="spinner-border text-primary" role="status">
                                                    <span class="visually-hidden">Chargement...</span>
                                                </div>
                                            </div>

                                            <!-- ✅ Liste des motifs -->
                                            <ul class="list-group" id="motifs-items1"></ul>
                                        </div>
                                    </div>
                                    <div class="mb-3 mt-3">
                                        <label for="observations1" class="form-label fs-15">Description de la
                                            caisse</label>
                                        <textarea class="form-control shadow" id="observations1" name="observations" rows="3"
                                            placeholder="Description de la caisse"></textarea>
                                    </div>
                                    <!-- ✅ Champs cachés pour envoyer au back -->
                                    <input type="hidden" name="categorie_motif_id" id="selected_categorie_id1" required
                                        class="validate-custom" data-error-message="Veuillez sélectionner une catégorie.">
                                    <input type="hidden" name="motif_standard_id" id="selected_motif_id1" required
                                        class="validate-custom" data-error-message="Veuillez sélectionner un motif.">
                                    <!-- Bouton d'envoi -->
                                    <button type="button" id="btn_envoie1" class="btn btn-danger w-100 monBouton"
                                        data-bs-toggle="modal" data-bs-target="#modalConfirmation1" disabled>
                                        <i class="las la-check me-2"></i> Valider l'opération
                                    </button>
                                    <!-- ✅ Modal de confirmation -->
                                    <div class="modal fade" id="modalConfirmation1" tabindex="-1"
                                        aria-labelledby="modalConfirmationLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-top">
                                            <div class="modal-content shadow-lg">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title fs-16" id="modalConfirmationLabel">Confirmation
                                                    </h5>
                                                    <button type="button" class="btn-close btn-close-white"
                                                        data-bs-dismiss="modal" aria-label="Fermer"></button>
                                                </div>
                                                <div class="modal-body fs-5 text-center">
                                                    <p>Êtes-vous sûr de vouloir <strong>valider cette opération</strong> ?
                                                    </p>
                                                </div>
                                                <div class="modal-footer justify-content-center">
                                                    <button type="button" class="btn btn-secondary me-3"
                                                        data-bs-dismiss="modal">Annuler</button>
                                                    <button type="button" class="btn btn-danger monBouton"
                                                        data-button-for="form2" data-loader-target-form="creer1">
                                                        <i class="las la-check me-2"></i> Oui, valider
                                                    </button>
                                                    <button type="button" id="creer1" class="btn btn-danger"
                                                        style="display: none;" disabled>
                                                        <i class="fas fa-spinner fa-spin me-2"></i>Traitement...
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div> <!--end col-->
                    <div class="col-md-4 col-lg-4">
                        @include('components.content_application.create_transfert_offcanvas', [
                            'caisse' => $caisse,
                            'autreCaisses' => $autreCaisses,
                        ])
                        <div class="row">
                            <div class="col-md-12 col-lg-6">
                                <div class="card border-0 bg-gradient-purple">
                                    <div class="card-body">
                                        <span class="fs-14 fw-semibold">Encaissements du jour</span>
                                        <h4 class="my-2 fs-22 fw-semibold">
                                            {{ number_format($encaissementsJour, 0, ',', ' ') }}</h4>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-lg-6">
                                <div class="card border-0 bg-gradient-info">
                                    <div class="card-body">
                                        <span class="fs-14 fw-semibold">Décaissements du jour</span>
                                        <h4 class="text-dark my-2 fw-semibold fs-22">
                                            {{ number_format($decaissementsJour, 0, ',', ' ') }}</h4>

                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-lg-6">
                                <div class="card border-0 bg-gradient-purple">
                                    <div class="card-body">
                                        <span class="fs-14 fw-semibold">Opérations passées</span>
                                        <h4 class="my-2 fs-22 fw-semibold">{{ $operationsPassees }}</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 col-lg-6">
                                <div class="card border-0 bg-gradient-info">
                                    <div class="card-body">
                                        <span class="fs-14 fw-semibold">Opérations annulées</span>
                                        <h4 class="text-dark my-2 fw-semibold fs-22">{{ $operationsAnnulees }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div> <!--end col-->
                </div>


                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Mouvements récents de la caisse</h4>
                    </div>
                    <div class="card-body pt-0">
                        <div class="table-responsive">
                            <table class="table mb-0 table-striped">

                                <tbody>
                                    @forelse ($mouvementsRecents as $mvt)
                                        @php
                                            $isDebit = $mvt->montant_debit > 0;
                                        @endphp
                                        <tr class="align-middle bg-white {{ $mvt->est_annule ? 'mouvement-annule' : '' }}"
                                            style="border-bottom: 1px solid;">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('assets/images/user.jpg') }}" height="34"
                                                        class="me-3 rounded border bg-white">
                                                    <div class="flex-grow-1 text-truncate">
                                                        <h6 class="m-0 mb-2 fs-13">
                                                            {{ $mvt->operateur->username ?? 'Utilisateur' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span
                                                    class="fw-bold text-dark">{{ $mvt->date_mouvement->format('d/m/Y H:i') }}</span>
                                                <br>
                                                <span class="fw-bold text-dark">{{ $mvt->num_mouvement }}</span>
                                            </td>
                                            <td>
                                                <div
                                                    style="border-left: 3px solid {{ $isDebit ? '#ff0000' : '#086721' }}; padding-left: 10px;">
                                                    <span class="badge text-white mb-1"
                                                        style="background-color: {{ $isDebit ? '#ff0000' : '#086721' }};">
                                                        {{ $isDebit ? 'Débit' : 'Crédit' }}
                                                    </span><br>
                                                    <span
                                                        class="text-dark fw-semibold">{{ $mvt->motifStandard->libelle_motif ?? $mvt->libelle_personnalise }}</span><br>
                                                    <small class="text-muted">{{ $mvt->observations ?? '' }}</small>
                                                </div>
                                            </td>
                                            <td class="text-end fw-semibold">
                                                <span class="badge bg-transparent text-dark fs-12 mb-2">Montant
                                                    débit</span><br>
                                                <span
                                                    class="text-danger fw-semibold">{{ number_format($mvt->montant_debit, 0, ',', ' ') }}</span>
                                            </td>
                                            <td class="text-end text-muted">
                                                <span class="badge bg-transparent text-dark fs-12 mb-2">Montant
                                                    crédit</span><br>
                                                <span
                                                    class="text-dark fw-semibold">{{ number_format($mvt->montant_credit, 0, ',', ' ') }}</span>
                                            </td>
                                            <td class="text-end fw-bold text-info">
                                                <span class="badge bg-transparent text-info fs-12 mb-2">Ancien
                                                    solde</span><br>
                                                {{ number_format($mvt->solde_avant_mouvement, 0, ',', ' ') }}
                                            </td>
                                            <td class="text-end fw-bold text-primary">
                                                <span class="badge bg-transparent text-primary fs-12 mb-2">Nouveau
                                                    solde</span>
                                                <br>
                                                {{ number_format($mvt->solde_apres_mouvement, 0, ',', ' ') }}
                                            </td>

                                            <td
                                                class="text-end fw-bold {{ $mvt->est_annule ? 'text-danger' : 'text-primary' }} ">
                                                <span
                                                    class="badge bg-transparent {{ $mvt->est_annule ? 'text-danger' : 'text-primary' }}  fs-12 mb-2">Motif</span>
                                                @if ($mvt->est_annule)
                                                    <br>
                                                    Annulation...
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                <span
                                                    class="badge bg-transparent text-primary fs-12 mb-2">Action</span><br>

                                                <div class="d-flex justify-content-end">

                                                    {{-- ✅ Bouton d’impression toujours disponible --}}
                                                    <button class="btn btn-sm btn-light border" data-bs-toggle="tooltip"
                                                        data-bs-title="Imprimer">
                                                        <i class="fas fa-print text-secondary fs-18"></i>
                                                    </button>

                                                    @if ($mvt->est_annule)
                                                        <button class="btn btn-sm btn-light border ms-1 text-info"
                                                            data-bs-toggle="offcanvas"
                                                            data-bs-target="#offcanvasMotifAnnulation{{ $mvt->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    @else
                                                        @if (Auth::user()->id === $caisse->user_id)
                                                            {{-- ✅ Bouton annuler si non annulé --}}
                                                            <button
                                                                class="btn btn-sm btn-light border ms-1 btn-show-mouvements"
                                                                data-num="{{ $mvt->num_mouvement }}"
                                                                data-bs-toggle="offcanvas"
                                                                data-bs-target="#myOffcanvas{{ $mvt->num_mouvement }}"
                                                                aria-controls="myOffcanvas{{ $mvt->num_mouvement }}">
                                                                <i class="fas fa-times-circle text-danger fs-18"></i>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                        @include(
                                            'components.content_application.create_annulermouvement_offcanvas',
                                            ['mvt' => $mvt]
                                        )
                                        @if ($mvt->est_annule)
                                            <div class="offcanvas offcanvas-bottom rounded-top shadow-lg" tabindex="-1"
                                                id="offcanvasMotifAnnulation{{ $mvt->id }}"
                                                aria-labelledby="offcanvasLabel{{ $mvt->id }}"
                                                style="height: 35vh;">

                                                <div class="offcanvas-header bg-info text-white">
                                                    <h5 class="offcanvas-title text-white"
                                                        id="offcanvasLabel{{ $mvt->id }}">
                                                        <i class="fas fa-info-circle me-2"></i> Détail de
                                                        l'annulation
                                                    </h5>
                                                    <button type="button" class="btn-close fs-28 btn-close-white"
                                                        data-bs-dismiss="offcanvas" aria-label="Fermer"></button>
                                                </div>

                                                <div class="offcanvas-body">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <div class="card border-0 shadow-sm mb-3">
                                                                <div class="card-body">
                                                                    <h6 class="fw-bold text-muted mb-2">
                                                                        Informations de l'opération annulée</h6>
                                                                    <p class="mb-1"><strong>Opérateur :</strong>
                                                                        {{ $mvt->operateur->username ?? 'Utilisateur inconnu' }}
                                                                    </p>
                                                                    <p class="mb-1"><strong>Type :</strong>
                                                                        <span
                                                                            class="badge {{ $mvt->montant_debit > 0 ? 'bg-danger' : 'bg-success' }}">
                                                                            {{ $mvt->montant_debit > 0 ? 'Débit' : 'Crédit' }}
                                                                        </span>
                                                                    </p>
                                                                    <p class="mb-1"><strong>Montant :</strong>
                                                                        <span
                                                                            class="fw-bold">{{ number_format($mvt->montant_debit > 0 ? $mvt->montant_debit : $mvt->montant_credit, 0, ',', ' ') }}
                                                                            FCFA</span>
                                                                    </p>
                                                                    <p class="mb-0"><strong>Date du mouvement
                                                                            :</strong>
                                                                        {{ $mvt->date_mouvement->format('d/m/Y H:i') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-4">
                                                            <div class="card border-0 shadow-sm bg-light">
                                                                <div class="card-body">
                                                                    <div class="card border-0 shadow-sm">
                                                                        <div class="card-body">
                                                                            <h6 class="fw-bold text-danger mb-3">
                                                                                <i class="fas fa-ban me-2"></i>
                                                                                Détails de l'annulation
                                                                            </h6>

                                                                            <div class="alert alert-danger d-flex align-items-start"
                                                                                role="alert">
                                                                                <i
                                                                                    class="fas fa-info-circle fs-4 me-3"></i>
                                                                                <div>
                                                                                    <p class="mb-1"><strong>Motif
                                                                                            :</strong></p>
                                                                                    <p class="mb-0 fw-semibold">
                                                                                        {{ $mvt->motif_annulation }}
                                                                                    </p>
                                                                                </div>
                                                                            </div>

                                                                            <p class="mb-1"><strong>Annulé par
                                                                                    :</strong>
                                                                                {{ $mvt->annulateur->username ?? 'Utilisateur inconnu' }}
                                                                            </p>
                                                                            <p class="mb-0"><strong>Date
                                                                                    d'annulation :</strong>
                                                                                {{ \Carbon\Carbon::parse($mvt->date_annulation)->format('d/m/Y H:i') }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif

                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">Aucun mouvement récent.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
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
                                            Yodingenierie
                                            <span class="text-muted d-none d-sm-inline-block float-end">
                                                Yodingenierie
                                                <i class="iconoir-heart-solid text-danger align-middle"></i>
                                                tous droits réservés.</span>
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const inputMontant = document.getElementById("montant_operation1");
                const btnEnvoyer = document.getElementById("btn_envoie");

                function parseMontant(val) {
                    // Convertir 1 000,50 en 1000.50
                    return parseFloat(val.replace(/\s/g, '').replace(',', '.')) || 0;
                }

                inputMontant.addEventListener("input", function() {
                    const montant = parseMontant(inputMontant.value);
                    // ✅ Si montant vide, 0 ou null → désactiver le bouton
                    if (!montant || montant <= 0) {
                        btnEnvoyer.disabled = true;
                    } else {
                        btnEnvoyer.disabled = false;
                    }
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const inputMontant = document.getElementById("montant_operation");
                const montantCaisse = parseFloat(document.getElementById("montant_caisse").value);
                const alertBox = document.getElementById("alert_montant");
                const btnEnvoyer = document.getElementById("btn_envoyer");

                function parseMontant(val) {
                    // Convertir 1 000,50 en 1000.50
                    return parseFloat(val.replace(/\s/g, '').replace(',', '.')) || 0;
                }

                inputMontant.addEventListener("input", function() {
                    const montant = parseMontant(inputMontant.value);
                    // ✅ Si montant vide, 0 ou null → cacher le bouton
                    if (!montant || montant <= 0) {
                        btnEnvoyer.disabled = true;
                        alertBox.classList.add("d-none");
                        return;
                    }
                    if (montant > montantCaisse) {
                        alertBox.classList.remove("d-none");
                        btnEnvoyer.disabled = true;
                    } else {
                        alertBox.classList.add("d-none");
                        btnEnvoyer.disabled = false;
                    }
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                function setupCategorieMotif(options) {
                    const radioCategorie = document.getElementById(options.radioId);
                    const blocListe = document.getElementById(options.listeId);
                    const searchInput = document.getElementById(options.searchCategorieId);
                    const categorieItems = document.querySelectorAll(options.categorieClass);
                    const radioLabel = document.getElementById(options.labelAffichageId);

                    const blocLibelle = document.getElementById(options.blocLibelleId);
                    const btnChoisirLibelle = document.getElementById(options.btnLibelleId);
                    const motifsList = document.getElementById(options.motifsListId);
                    const motifsItems = document.getElementById(options.motifsItemsId);
                    const searchMotif = document.getElementById(options.searchMotifId);

                    const hiddenCategorie = document.getElementById(options.hiddenCategorieId);
                    const hiddenMotif = document.getElementById(options.hiddenMotifId);
                    const loader = document.getElementById(options.loaderId);

                    let selectedCategorieId = null;

                    // ✅ Afficher/masquer la liste
                    radioCategorie.addEventListener("change", () => {
                        blocListe.style.display = radioCategorie.checked ? "block" : "none";
                    });

                    // ✅ Recherche dans catégories
                    searchInput.addEventListener("input", function() {
                        const filter = this.value.toLowerCase();
                        categorieItems.forEach((item) => {
                            const name = item.dataset.name.toLowerCase();
                            item.style.display = name.includes(filter) ? "" : "none";
                        });
                    });

                    // ✅ Sélection d'une catégorie
                    categorieItems.forEach((item) => {
                        item.addEventListener("click", function(e) {
                            e.preventDefault();

                            categorieItems.forEach((i) => {
                                i.classList.remove("bg-light");
                                i.querySelector(".fa-check").style.display = "none";
                            });

                            this.classList.add("bg-light");
                            this.querySelector(".fa-check").style.display = "inline";

                            selectedCategorieId = this.dataset.id;
                            const selectedName = this.dataset.name;

                            hiddenCategorie.value = selectedCategorieId;
                            radioLabel.innerHTML =
                                `<i class="las la-wallet fs-18 me-2"></i> ${selectedName}`;

                            radioCategorie.checked = false;
                            blocListe.style.display = "none";

                            motifsItems.innerHTML = "";
                            hiddenMotif.value = "";
                            motifsList.style.display = "none";
                            btnChoisirLibelle.innerHTML =
                                `<i class="las la-plus-circle me-2"></i> Choisir un libellé`;

                            blocLibelle.style.display = "block";
                            validateForm(); // Mettre à jour l'état du bouton
                        });
                    });

                    // ✅ Charger motifs via AJAX
                    btnChoisirLibelle.addEventListener("click", function() {
                        if (!selectedCategorieId) return;

                        loader.style.display = "block";
                        motifsList.style.display = "none";

                        fetch(`/categorie/${selectedCategorieId}/motifs`)
                            .then((res) => res.json())
                            .then((data) => {
                                motifsItems.innerHTML = "";

                                if (data.length === 0) {
                                    motifsItems.innerHTML =
                                        `<li class="list-group-item text-danger">Aucun motif trouvé</li>`;
                                } else {
                                    data.forEach((motif) => {
                                        const li = document.createElement("li");
                                        li.className =
                                            "list-group-item d-flex justify-content-between align-items-center";
                                        li.dataset.libelle = motif.libelle_motif.toLowerCase();
                                        li.innerHTML = `
                                ${motif.libelle_motif}
                                <button type="button" class="btn btn-sm btn-success select-motif" data-id="${motif.id}">
                                    Sélectionner
                                </button>
                            `;
                                        motifsItems.appendChild(li);
                                    });
                                }

                                loader.style.display = "none";
                                motifsList.style.display = "block";
                            })
                            .catch(() => {
                                loader.style.display = "none";
                                motifsItems.innerHTML =
                                    `<li class="list-group-item text-danger">Erreur de chargement</li>`;
                                motifsList.style.display = "block";
                            });
                    });

                    // ✅ Recherche dans motifs
                    searchMotif.addEventListener("input", function() {
                        const filter = this.value.toLowerCase();
                        Array.from(motifsItems.children).forEach((li) => {
                            const libelle = li.dataset.libelle || "";
                            li.style.display = libelle.includes(filter) ? "" : "none";
                        });
                    });

                    // ✅ Sélection d'un motif
                    motifsItems.addEventListener("click", function(e) {
                        if (e.target.classList.contains("select-motif")) {
                            e.preventDefault();

                            const selectedId = e.target.dataset.id;
                            const selectedName = e.target.parentElement.firstChild.textContent.trim();

                            hiddenMotif.value = selectedId;
                            btnChoisirLibelle.innerHTML = `<i class="las la-check me-2"></i> ${selectedName}`;
                            motifsList.style.display = "none";
                            validateForm(); // Mettre à jour l'état du bouton
                        }
                    });
                }

                // ✅ Initialiser pour les 2 blocs
                setupCategorieMotif({
                    radioId: "categorie_motif_radio",
                    listeId: "liste-categories",
                    searchCategorieId: "categorie_search",
                    categorieClass: ".categorie-item",
                    blocLibelleId: "bloc-libelle",
                    btnLibelleId: "btn-choisir-libelle",
                    motifsListId: "motifs-list",
                    motifsItemsId: "motifs-items",
                    searchMotifId: "search-motif",
                    hiddenCategorieId: "selected_categorie_id",
                    hiddenMotifId: "selected_motif_id",
                    loaderId: "loader",
                    labelAffichageId: "label-affichage-categorie" // ✅ Nouveau !
                });

                setupCategorieMotif({
                    radioId: "categorie_motif_radio1",
                    listeId: "liste-categories1",
                    searchCategorieId: "categorie_search1",
                    categorieClass: ".categorie-item1",
                    blocLibelleId: "bloc-libelle1",
                    btnLibelleId: "btn-choisir-libelle1",
                    motifsListId: "motifs-list1",
                    motifsItemsId: "motifs-items1",
                    searchMotifId: "search-motif1",
                    hiddenCategorieId: "selected_categorie_id1",
                    hiddenMotifId: "selected_motif_id1",
                    loaderId: "loader1",
                    labelAffichageId: "label-affichage-categorie1" // ✅ Nouveau !
                });

                const btnEnvoie = document.getElementById("btn_envoie");
                const form = document.getElementById('form-creation-mouvement');
                const categorieMotifId = document.getElementById('selected_categorie_id');
                const motifStandardId = document.getElementById('selected_motif_id');

                // Fonction pour valider le formulaire et activer/désactiver le bouton
                function validateForm() {
                    if (form.checkValidity() && categorieMotifId.value && motifStandardId.value) {
                        btnEnvoie.removeAttribute('disabled');
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
                    } else {
                        btnEnvoie.setAttribute('disabled', 'disabled');
                    }
                }


                // Validation initiale
                validateForm();

                //Surveiller les changements dans les champs
                form.addEventListener('input', validateForm);
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const btn = document.getElementById("btn_envoie1");
                const champs = [
                    document.getElementById("date_mouvement1"),
                    document.getElementById("montant_operation"),
                    document.getElementById("selected_categorie_id1"),
                    document.getElementById("selected_motif_id1")
                ];

                function verifierChamps() {
                    const tousRemplis = champs.every(champ => champ.value.trim() !== "");
                    btn.disabled = !tousRemplis;
                }

                // Pour les champs visibles → écouteurs d’événements
                ["date_mouvement1", "montant_operation"].forEach(id => {
                    document.getElementById(id).addEventListener("input", verifierChamps);
                    document.getElementById(id).addEventListener("change", verifierChamps);
                });

                // Pour les champs hidden → observer les changements de value
                champs.filter(champ => champ.type === "hidden").forEach(hiddenChamp => {
                    const observer = new MutationObserver(verifierChamps);
                    observer.observe(hiddenChamp, {
                        attributes: true,
                        attributeFilter: ["value"]
                    });
                });
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
                // Vérification initiale
                verifierChamps();
            });
        </script>
    </div>

@endsection
