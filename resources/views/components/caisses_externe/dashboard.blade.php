@extends('layouts.appexterne')

@section('title', 'Mon Tableau de bord')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Vérifier si des données sont disponibles --}}
                @if (isset($caisses) && !empty($caisses))

                    {{-- PARTIE 1 : LES CARTES DE STATISTIQUES --}}
                    <div class="row">
                        <div class="col-md-6 col-lg-3">
                            <div class="card border-0 bg-gradient-purple">
                                <div class="card-body">
                                    <span class="fs-14 fw-semibold">Encaissements du jour</span>
                                    <h4 class="my-2 fs-22 fw-semibold">{{ number_format($totalEncaissements, 0, ',', ' ') }}
                                    </h4>
                                    {{-- Le calcul de pourcentage nécessite les données de la veille, on le laisse statique pour le moment --}}
                                    <p class="text-muted fs-13"><span class="text-success"><i
                                                class="fas fa-arrow-up me-1"></i>--,--%</span> Augmentation du solde</p>
                                </div>
                                {{-- <div id="apexBar2"></div> --}}
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-3">
                            <div class="card border-0 bg-gradient-info">
                                <div class="card-body">
                                    <span class="fs-14 fw-semibold">Décaissements du jour</span>
                                    <h4 class="text-dark my-2 fw-semibold fs-22">
                                        {{ number_format($totalDecaissements, 0, ',', ' ') }}</h4>
                                    <p class="text-muted fs-13"><span class="text-danger"><i
                                                class="fas fa-arrow-down me-1"></i>--,--%</span> Diminution du solde</p>
                                </div>
                                {{-- <div id="apexBar4"></div> --}}
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="card border-0 bg-gradient-danger">
                                <div class="card-body">
                                    <span class="fs-14 fw-semibold">Solde net du jour</span>
                                    <h4 class="text-dark my-2 fw-semibold fs-22">{{ number_format($soldeNet, 0, ',', ' ') }}
                                    </h4>
                                    @if ($soldeNet >= 0)
                                        <p class="text-muted fs-13"><span class="text-success"><i
                                                    class="fas fa-arrow-up me-1"></i>--,--%</span> Solde positif</p>
                                    @else
                                        <p class="text-muted fs-13"><span class="text-danger"><i
                                                    class="fas fa-arrow-down me-1"></i>--,--%</span> Solde négatif</p>
                                    @endif
                                </div>
                                {{-- <div id="apexBar3"></div> --}}
                            </div>
                        </div>
                        <div class="col-md-12 col-lg-3">
                            <div class="card border-0 bg-gradient-warning">
                                <div class="card-body">
                                    <span class="fs-14 fw-semibold">Opérations du jour</span>
                                    <h4 class="text-dark my-2 fw-semibold fs-22">
                                        {{ number_format($totalOperationsValue, 0, ',', ' ') }}</h4>
                                    <p class="text-muted fs-13"><span class="text-success"><i
                                                class="fas fa-arrow-up me-1"></i>--,--%</span> Valeur totale déplacée</p>
                                </div>
                                {{-- <div id="apexBar5"></div> --}}
                            </div>
                        </div>
                    </div>

                    <div class="row justify-content-center">
                        {{-- PARTIE 2 : TABLEAU DES TRANSACTIONS RÉCENTES --}}
                        <div class="col-12 col-lg-8">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Transactions du jour</h4>
                                </div>
                                <div class="card-body pt-0">
                                    <div class="table-responsive">
                                        <table class="table mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Nom</th>
                                                    <th>Date</th>
                                                    <th>Type</th>
                                                    <th>Description</th>
                                                    <th>Montant</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($allMouvements as $mvt)
                                                    @php
                                                        $isDebit = ($mvt->montant_debit ?? 0) > 0;
                                                        $montant = $isDebit
                                                            ? $mvt->montant_debit
                                                            : $mvt->montant_credit;
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <img src="{{ asset('assets/images/user.jpg') }}"
                                                                    height="34"
                                                                    class="me-3 align-self-center rounded border bg-white"
                                                                    alt="...">
                                                                <div class="flex-grow-1 text-truncate">
                                                                    {{-- Utilisateur qui a fait le mouvement --}}
                                                                    <h6 class="m-0 mb-2 fs-13">
                                                                        {{ $mvt->username ?? 'Utilisateur inconnu' }}</h6>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::createFromFormat('YmdHisu', $mvt->datemvtcaisse)->format('d/m/Y H:i') }}
                                                        </td>
                                                        <td>
                                                            @if ($isDebit)
                                                                <span
                                                                    class="badge text-danger border border-danger px-2">Débit</span>
                                                            @else
                                                                <span
                                                                    class="badge text-success border border-success px-2">Crédit</span>
                                                            @endif
                                                        </td>
                                                        <td>{{ $mvt->motif_mouvement_caisse ?? 'N/A' }}</td>
                                                        <td
                                                            class="fw-semibold {{ $isDebit ? 'text-danger' : 'text-success' }}">
                                                            {{ number_format($montant, 0, ',', ' ') }}
                                                        </td>
                                                        
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center py-4">Aucune transaction
                                                            enregistrée aujourd'hui.</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- PARTIE 3 : LISTE DES CAISSES IMPACTÉES --}}
                        <div class="col-12 col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title mb-1">Vos Caisses</h4>
                                    <p class="mb-0 text-truncate fs-13 text-muted fw-medium">{{ count($caisses) }}
                                        caisse(s)
                                        trouvée(s)</p>
                                </div>
                                <div class="card-body p-0">
                                    @forelse ($caisses as $caisse)
                                        {{-- @if ($soldeNet > 0) --}}
                                            <div
                                                class="d-flex border-bottom justify-content-between align-items-center p-3">
                                                <div class="d-flex">
                                                    <div
                                                        class="bg-body d-flex justify-content-center align-items-center thumb-md align-self-center rounded-circle">
                                                        <i class="fas fa-cash-register fs-22"></i>
                                                    </div>
                                                    <div class="flex-grow-1 text-truncate align-self-center ms-2">
                                                        <p class="text-dark mb-0 fw-semibold">
                                                            {{ $caisse->nomcaisse ?? 'Caisse' }}</p>
                                                        <p class="mb-0 text-truncate fs-13 text-muted">
                                                            {{ $caisse->typeCaisse ?? '' }}</p>
                                                    </div>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <p class="mb-0 text-truncate fs-13 text-muted d-inline-block fw-medium">
                                                        {{ number_format($caisse->soldecaisse, 0, ',', ' ') }}</p>
                                                    {{-- Logique simple pour le statut : 0 = Inactif --}}
                                                    @if (($caisse->soldecaisse ?? 0) != 0)
                                                        <span
                                                            class="badge text-success border border-success px-2 ms-2">Actif</span>
                                                    @else
                                                        <span
                                                            class="badge text-danger border border-danger px-2 ms-2">Inactif</span>
                                                    @endif
                                                </div>
                                            </div>
                                        {{-- @endif --}}
                                    @empty
                                        <p class="p-3">Aucune caisse n'est configurée.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        {{ $error ?? "Aucune caisse n'a été trouvée pour votre compte. Veuillez en configurer une." }}
                    </div>
                @endif
            </div>
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
@endsection
