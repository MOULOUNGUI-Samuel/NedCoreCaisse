<div class="card">
    <div class="card-header">
        <h4 class="card-title">Mouvements récents de la caisse</h4>
    </div>
    <div class="card-body pt-0">

        <!-- ======================================================== -->
        <!--   VERSION POUR GRANDS ÉCRANS (DESKTOP) - VOTRE TABLEAU   -->
        <!--   Visible uniquement sur les écrans larges (lg) et plus -->
        <!-- ======================================================== -->
        <div class="table-responsive d-none d-lg-block">
            <table class="table mb-0 table-striped">
                <thead>
                    {{-- On ajoute des en-têtes pour la clarté --}}
                    <tr>
                        <th>Opérateur</th>
                        <th class="text-center">Date</th>
                        <th>Description</th>
                        <th class="text-end">Débit</th>
                        <th class="text-end">Crédit</th>
                        <th class="text-end">Ancien Solde</th>
                        <th class="text-end">Nouveau Solde</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                   @forelse ($mouvements as $mvt)
                        @php $isDebit = $mvt->montant_debit > 0; @endphp
                        <tr class="align-middle bg-white  {{ $mvt->est_annule ? 'mouvement-annule' : '' }}" style="border-bottom: 1px solid #dee2e6;">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('assets/images/user.jpg') }}" height="34"
                                        class="me-3 rounded border bg-white" alt="...">
                                    <div class="flex-grow-1 text-truncate">
                                        <h6 class="m-0 mb-2 fs-13">{{ $mvt->operateur->username ?? 'Utilisateur' }}</h6>
                                    </div>
                                </div>
                            </td>

                            <td class="text-center">
                                <span class="fw-bold text-dark">{{ $mvt->date_mouvement->format('d/m/Y H:i') }}</span>
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
                                <span class="badge bg-transparent text-dark fs-12 mb-2">Montant débit</span><br>
                                <span
                                    class="text-danger fw-semibold">{{ number_format($mvt->montant_debit, 0, ',', ' ') }}</span>
                            </td>

                            <td class="text-end text-muted">
                                <span class="badge bg-transparent text-dark fs-12 mb-2">Montant crédit</span><br>
                                <span
                                    class="text-dark fw-semibold">{{ number_format($mvt->montant_credit, 0, ',', ' ') }}</span>
                            </td>

                            <td class="text-end fw-bold text-info">
                                <span class="badge bg-transparent text-info fs-12 mb-2">Ancien solde</span><br>
                                {{ number_format($mvt->solde_avant_mouvement, 0, ',', ' ') }}
                            </td>

                            <td class="text-end fw-bold text-primary">
                                <span class="badge bg-transparent text-primary fs-12 mb-2">Nouveau solde</span>
                                @if ($mvt->est_annule)
                                    <span class="badge bg-danger ms-2">ANNULÉ</span>
                                @endif
                                <br>
                                {{ number_format($mvt->solde_apres_mouvement, 0, ',', ' ') }}
                            </td>

                            <td class="text-end">
                                <span class="badge bg-transparent text-primary fs-12 mb-2">Action</span><br>
                                <div class="d-flex justify-content-end">
                                    {{-- ✅ Bouton d’impression --}}
                                    <button class="btn btn-sm btn-light border" data-bs-toggle="tooltip"
                                        data-bs-title="Imprimer">
                                        <i class="fas fa-print text-secondary fs-18"></i>
                                    </button>

                                    @if ($mvt->est_annule)
                                        {{-- ✅ Bouton voir motif --}}
                                        <button class="btn btn-sm btn-light border ms-1 text-info"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvasMotifAnnulation{{ $mvt->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>

                        {{-- ✅ Inclure l’offcanvas d’annulation --}}
                        @include('components.content_application.create_annulermouvement_offcanvas', [
                            'mvt' => $mvt,
                        ])

                        {{-- ✅ Offcanvas pour voir le motif si déjà annulé --}}
                        @if ($mvt->est_annule)
                            <div class="offcanvas offcanvas-bottom rounded-top shadow-lg" tabindex="-1"
                                id="offcanvasMotifAnnulation{{ $mvt->id }}"
                                aria-labelledby="offcanvasLabel{{ $mvt->id }}" style="height: 35vh;">

                                <div class="offcanvas-header bg-info text-white">
                                    <h5 class="offcanvas-title text-white" id="offcanvasLabel{{ $mvt->id }}">
                                        <i class="fas fa-info-circle me-2"></i> Détail de l'annulation
                                    </h5>
                                    <button type="button" class="btn-close fs-28 btn-close-white"
                                        data-bs-dismiss="offcanvas"></button>
                                </div>

                                <div class="offcanvas-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="card border-0 shadow-sm mb-3">
                                                <div class="card-body">
                                                    <h6 class="fw-bold text-muted mb-2">Informations de l'opération
                                                        annulée</h6>
                                                    <p><strong>Opérateur :</strong>
                                                        {{ $mvt->operateur->username ?? 'Utilisateur inconnu' }}</p>
                                                    <p><strong>Type :</strong>
                                                        <span
                                                            class="badge {{ $mvt->montant_debit > 0 ? 'bg-danger' : 'bg-success' }}">
                                                            {{ $mvt->montant_debit > 0 ? 'Débit' : 'Crédit' }}
                                                        </span>
                                                    </p>
                                                    <p><strong>Montant :</strong>
                                                        <span class="fw-bold">
                                                            {{ number_format($mvt->montant_debit > 0 ? $mvt->montant_debit : $mvt->montant_credit, 0, ',', ' ') }}
                                                            FCFA
                                                        </span>
                                                    </p>
                                                    <p><strong>Date :</strong>
                                                        {{ $mvt->date_mouvement->format('d/m/Y H:i') }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="card border-0 shadow-sm bg-light">
                                                <div class="card-body">
                                                    <h6 class="fw-bold text-danger mb-3">
                                                        <i class="fas fa-ban me-2"></i> Détails de l'annulation
                                                    </h6>

                                                    <div class="alert alert-danger d-flex align-items-start">
                                                        <i class="fas fa-info-circle fs-4 me-3"></i>
                                                        <div>
                                                            <p class="mb-1"><strong>Motif :</strong></p>
                                                            <p class="mb-0 fw-semibold">{{ $mvt->motif_annulation }}
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <p><strong>Annulé par :</strong>
                                                        {{ $mvt->annulateur->username ?? 'Utilisateur inconnu' }}</p>
                                                    <p><strong>Date annulation :</strong>
                                                        {{ \Carbon\Carbon::parse($mvt->date_annulation)->format('d/m/Y H:i') }}

                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4">Aucun mouvement trouvé pour cette caisse.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <!-- ======================================================= -->
        <!--   VERSION POUR PETITS ÉCRANS (MOBILE) - LISTE DE CARTES -->
        <!--   Visible uniquement sur les écrans en dessous de 'lg'  -->
        <!-- ======================================================= -->
        <div class="d-lg-none">
            @forelse ($mouvements as $mvt)
                @php $isDebit = $mvt->montant_debit > 0; @endphp
                
                {{-- Chaque mouvement est une carte --}}
                <div class="card mb-2 shadow-sm mouvement-card-mobile {{ $mvt->est_annule ? 'mouvement-annule' : '' }}">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between">
                            {{-- Section de gauche : Description --}}
                            <div class="me-3">
                                <h6 class="fw-bold mb-1">{{ $mvt->motifStandard->libelle_motif ?? $mvt->libelle_personnalise }}</h6>
                                <p class="text-muted fs-14 mb-0">{{ $mvt->observations ?? 'Aucune observation' }}</p>
                            </div>

                            {{-- Section de droite : Montant --}}
                            <div class="text-end text-nowrap">
                                <h5 class="fw-bold mb-0 {{ $isDebit ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($isDebit ? $mvt->montant_debit : $mvt->montant_credit, 0, ',', ' ') }}
                                </h5>
                                <span class="badge {{ $isDebit ? 'bg-danger-light text-danger' : 'bg-success-light text-success' }}">
                                    {{ $isDebit ? 'Débit' : 'Crédit' }}
                                </span>
                            </div>
                        </div>

                        {{-- Ligne de séparation --}}
                        <hr class="my-2">

                        {{-- Section du bas : Date, Actions et Statut --}}
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">{{ $mvt->date_mouvement->format('d/m/y H:i') }}</small>
                            <div>
                                @if ($mvt->est_annule)
                                    <span class="badge bg-danger me-2">ANNULÉ</span>
                                    {{-- Bouton pour voir le motif d'annulation --}}
                                    <button class="btn btn-sm btn-outline-info py-0 px-2" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMotifAnnulation{{ $mvt->id }}">
                                        <i class="fas fa-eye"></i>
                                    </button>

                                @endif
                               @if (Auth::user()->super_admin === 1)
                                                             <span class="badge rounded-pill bg-transparent border border-primary text-primary fs-12">{{ Str::limit($t->caisse->societe->nom_societe, 12, '...')}}</span>
                                                         @endif
                                {{-- Le bouton d'impression est caché sur mobile pour plus de clarté --}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Les Offcanvas restent les mêmes et fonctionneront aussi sur mobile --}}
                @include('components.content_application.create_annulermouvement_offcanvas', ['mvt' => $mvt])
              

            @empty
                <div class="text-center py-4">
                    <p>Aucun mouvement trouvé pour cette caisse.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

{{-- On peut ajouter un peu de style pour les cartes mobiles --}}
<style>
    .mouvement-card-mobile {
        border-left: 5px solid;
        border-color: #086721; /* Vert par défaut (Crédit) */
    }
    .mouvement-card-mobile.debit {
        border-color: #ff0000; /* Rouge pour le Débit */
    }
    .mouvement-card-mobile.mouvement-annule {
        opacity: 0.6;
        background-color: #f8f9fa;
    }
    .mouvement-card-mobile.mouvement-annule h6 {
        text-decoration: line-through;
    }
    /* Classes de badge personnalisées pour le mobile */
    .bg-danger-light { background-color: rgba(255, 0, 0, 0.1); }
    .bg-success-light { background-color: rgba(8, 103, 33, 0.1); }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        document.querySelectorAll(".caisse-card").forEach(card => {
            card.addEventListener("click", function(e) {
                e.preventDefault(); // Empêche tout comportement par défaut
                e.stopPropagation(); // Empêche la propagation qui pourrait causer un reload

                const caisseId = this.dataset.caisseId;
                const container = document.getElementById("mouvements-container");

                // Supprime la classe active sur les autres cartes
                document.querySelectorAll(".caisse-card").forEach(c => c.classList.remove(
                    "active"));
                this.classList.add("active");

                // Loader
                container.innerHTML = `
                <div class="text-center py-3">
                    <i class="fas fa-spinner fa-spin fs-3 text-primary"></i><br>
                    <small>Chargement des mouvements...</small>
                </div>`;

                // Chargement AJAX
                fetch(`/caisses/${caisseId}/mouvements`, {
                        cache: "no-store"
                    })
                    .then(res => res.text())
                    .then(html => {
                        container.innerHTML = html;
                    })
                    .catch(() => {
                        container.innerHTML = `<p class="text-danger text-center py-3">
                        Erreur lors du chargement des mouvements.
                    </p>`;
                    });
            });
        });
    });
</script>
