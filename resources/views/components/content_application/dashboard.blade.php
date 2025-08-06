@extends('layouts.app')

@section('title', 'Mon Tableau de bord')

@section('content')
    <div class="page-wrapper"> 
    <div class="page-content">
        <div class="container-fluid">

            {{-- PARTIE 1 : LES CARTES DE STATISTIQUES --}}
            <div class="row">
                {{-- ✅ Encaissements --}}
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 bg-gradient-purple">
                        <div class="card-body">
                            <span class="fs-14 fw-semibold">Encaissements du jour</span>
                            <h4 class="my-2 fs-22 fw-semibold">{{ number_format($encToday, 0, ',', ' ') }}</h4>
                            <p class="text-muted fs-13">
                                <span class="{{ $encPercent >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $encPercent >= 0 ? 'up' : 'down' }} me-1"></i>
                                    {{ $encPercent }}%
                                </span> par rapport à hier
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ✅ Décaissements --}}
                <div class="col-md-12 col-lg-3">
                    <div class="card border-0 bg-gradient-info">
                        <div class="card-body">
                            <span class="fs-14 fw-semibold">Décaissements du jour</span>
                            <h4 class="text-dark my-2 fw-semibold fs-22">{{ number_format($decToday, 0, ',', ' ') }}</h4>
                            <p class="text-muted fs-13">
                                <span class="{{ $decPercent >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $decPercent >= 0 ? 'up' : 'down' }} me-1"></i>
                                    {{ $decPercent }}%
                                </span> par rapport à hier
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ✅ Solde net --}}
                <div class="col-md-6 col-lg-3">
                    <div class="card border-0 bg-gradient-danger">
                        <div class="card-body">
                            <span class="fs-14 fw-semibold">Solde net du jour</span>
                            <h4 class="text-dark my-2 fw-semibold fs-22">{{ number_format($soldeToday, 0, ',', ' ') }}</h4>
                            <p class="text-muted fs-13">
                                <span class="{{ $soldePercent >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $soldePercent >= 0 ? 'up' : 'down' }} me-1"></i>
                                    {{ $soldePercent }}%
                                </span> par rapport à hier
                            </p>
                        </div>
                    </div>
                </div>

                {{-- ✅ Nombre d'opérations --}}
                <div class="col-md-12 col-lg-3">
                    <div class="card border-0 bg-gradient-warning">
                        <div class="card-body">
                            <span class="fs-14 fw-semibold">Opérations du jour</span>
                            <h4 class="text-dark my-2 fw-semibold fs-22">{{ $opsToday }}</h4>
                            <p class="text-muted fs-13">
                                <span class="{{ $opsPercent >= 0 ? 'text-success' : 'text-danger' }}">
                                    <i class="fas fa-arrow-{{ $opsPercent >= 0 ? 'up' : 'down' }} me-1"></i>
                                    {{ $opsPercent }}%
                                </span> par rapport à hier
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PARTIE 2 : TABLEAU DES TRANSACTIONS RÉCENTES --}}
            <div class="row justify-content-center">
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
                                        @forelse ($transactions as $t)
                                        <tr class="align-middle bg-white {{ $t->est_annule ? 'mouvement-annule' : '' }}" style="border-bottom: 1px solid #dee2e6;">
                                            <!-- ✅ Colonne opérateur -->
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('assets/images/user.jpg') }}" height="34"
                                                        class="me-3 rounded border bg-white" alt="...">
                                                    <div class="flex-grow-1 text-truncate">
                                                        <h6 class="m-0 mb-2 fs-13">{{ $t->operateur->username ?? 'Utilisateur' }}</h6>
                                                    </div>
                                                </div>
                                            </td>
                                        
                                            <!-- ✅ Date du mouvement -->
                                            <td class="text-center">
                                                <span class="fw-bold text-dark">{{ $t->date_mouvement->format('d/m/Y H:i') }}</span>
                                            </td>
                                        
                                            <!-- ✅ Type d'opération + motif -->
                                            <td>
                                                <div style="border-left: 3px solid {{ $t->montant_debit > 0 ? '#ff0000' : '#086721' }}; padding-left: 10px;">
                                                    <span class="badge text-white mb-1"
                                                        style="background-color: {{ $t->montant_debit > 0 ? '#ff0000' : '#086721' }};">
                                                        {{ $t->montant_debit > 0 ? 'Débit' : 'Crédit' }}
                                                    </span><br>
                                                    <span class="text-dark fw-semibold">
                                                        {{ $t->motifStandard->libelle_motif ?? $t->libelle_personnalise }}
                                                    </span><br>
                                                    <small class="text-muted">{{ $t->observations ?? '' }}</small>
                                                </div>
                                            </td>
                                        
                                            <!-- ✅ Montant -->
                                            <td class="text-end fw-semibold">
                                                <span class="badge bg-transparent text-dark fs-12 mb-2">Montant</span><br>
                                                <span class="{{ $t->montant_debit > 0 ? 'text-danger' : 'text-success' }} fw-semibold">
                                                    {{ number_format($t->montant_debit ?: $t->montant_credit, 0, ',', ' ') }}
                                                </span>
                                            </td>
                                        
                                            <!-- ✅ Action -->
                                            <td class="text-end">
                                                <span class="badge bg-transparent text-primary fs-12 mb-2">Action</span><br>
                                                <div class="d-flex justify-content-end">
                                                    <button class="btn btn-sm btn-light border" data-bs-toggle="tooltip" data-bs-title="Imprimer">
                                                        <i class="fas fa-print text-secondary fs-18"></i>
                                                    </button>
                                        
                                                    @if ($t->est_annule)
                                                        <button class="btn btn-sm btn-light border ms-1 text-info"
                                                            data-bs-toggle="offcanvas"
                                                            data-bs-target="#offcanvasMotifAnnulation{{ $t->id }}">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center">Aucune transaction</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- PARTIE 3 : LISTE DES CAISSES --}}
                <div class="col-12 col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title mb-1">Vos Caisses</h4>
                            <p class="mb-0 text-truncate fs-13 text-muted fw-medium">
                                {{ $caisses->count() }} caisse(s) trouvée(s)
                            </p>
                        </div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                @forelse ($caisses as $caisse)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span>{{ $caisse->libelle_caisse }}</span>
                                        <span class="badge bg-primary">{{ number_format($caisse->seuil_encaissement, 0, ',', ' ') }} XAF</span>
                                    </li>
                                @empty
                                    <li class="list-group-item text-center text-muted">Aucune caisse disponible</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

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

    @include('layouts.footer')

@endsection
