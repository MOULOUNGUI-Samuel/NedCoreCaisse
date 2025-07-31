<div class="offcanvas offcanvas-start" tabindex="-1" id="myOffcanvasT" aria-labelledby="offcanvasLabel">
    <div class="offcanvas-header bg-danger">
        <h4 class="offcanvas-title text-white" id="offcanvasLabel"><i class="fas fa-exchange-alt me-2"></i>
            Transfert de fonds</h4>
        <button type="button" class="btn-close fs-28 text-white" data-bs-dismiss="offcanvas"
            aria-label="Fermer"></button>
    </div>
    <div class="offcanvas-body">
        <div class="card p-3">
            <form method="POST" action="{{ route('transfert_mouvements.store') }}" id="form-creation-caisse"
                class="form needs-validation" novalidate>
                @csrf
                <div class="text-end mb-2">
                    <button type="submit" id="btn_envoyer2" class="btn btn-light rounded-pill w-50 shadow text-danger">
                        <i class="las la-exchange-alt fs-18 me-2"></i>
                        <span class="d-none d-sm-inline">Transferer</span>
                    </button>
                </div>
                <div class="mb-3">
                    <label for="date_mouvement" class="form-label fs-18">Date de l'opération</label>
                    <input class="form-control shadow" type="text" id="date_mouvement" name="date_mouvement"
                        value="{{ now()->format('d/m/Y') }}" readonly>
                </div>
                <div class="mb-3">
                    <label for="montant_operation" class="form-label fs-18">Montant de transfert</label>
                    <input class="form-control shadow separateur-nombre" type="text" id="montant_operation2"
                        value="{{ old('montant_operation') }}" name="montant_operation"
                        placeholder="Montant de transfert" required>
                </div>
                <!-- Montant disponible dans la caisse -->
                <input type="hidden" id="montant_caisse2" value="{{ $caisse->seuil_encaissement }}">
                <input type="hidden" id="caisse_source_id" name="caisse_source_id" value="{{ $caisse->id }}">
                <!-- Message d'erreur -->
                <div id="alert_montant2" class="mb-3 alert alert-danger d-none">
                    ⚠️ Le montant saisi dépasse le montant disponible dans la caisse
                    ({{ number_format($caisse->seuil_encaissement, 0, ',', ' ') }} FCFA).
                </div>
                <div class="mb-3">
                    <label class="form-label fs-18">Choix de la caisse</label>
                    <input type="radio" class="btn-check" id="gestionnaire_compte" autocomplete="off"
                        name="gestionnaire_compte">
                    <label class="btn btn-outline-dark rounded-pill w-100 btn-sm" for="gestionnaire_compte">
                        <i class="fas fa-cash-register  fs-18 me-2"></i> <span class="fs-18">Sélectionner une
                            caisse</span>
                    </label>
                </div>
                <div id="gestionnaire_list" style="display: none;">
                    <input type="text" class="form-control shadow mb-2" id="gestionnaire_compte_recherche"
                        placeholder="Rechercher une caisse...">
                    <div id="gestionnaire_items_container" class="ms-0 px-3" style="max-height:300px;" data-simplebar>

                        @forelse ($autreCaisses as $caisse)
                            <div class="card gestionnaire-item" data-id="{{ $caisse->id }}"
                                data-name="{{ $caisse->libelle_caisse }}" style="cursor: pointer;">
                                <div class="d-flex border-bottom justify-content-between align-items-center p-3">
                                    <div class="d-flex">
                                        <div
                                            class="bg-body d-flex justify-content-center align-items-center thumb-md align-self-center rounded-circle">
                                            <i class="fas fa-cash-register fs-22"></i>
                                        </div>
                                        <div class="flex-grow-1 text-truncate align-self-center ms-2">
                                            <p class="text-dark mb-0 fw-semibold gestionnaire-name">
                                                {{ $caisse->libelle_caisse }}</p>
                                            </p>
                                            <p class="mb-0 text-truncate fs-13 text-muted">
                                                {{ $caisse->user->name }} {{ $caisse->user->username }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <p class="mb-0 text-truncate fs-18 text-muted d-inline-block fw-medium">
                                            {{ number_format($caisse->seuil_encaissement, 0, ',', ' ') }} XAF</p>
                                        <i class="fas fa-check text-success ms-auto fs-22" style="display: none;"></i>
                                    </div>
                                </div>
                            </div>
                            {{-- @endif --}}
                        @empty
                            <p class="p-3">Aucune caisse trouvée.</p>
                        @endforelse
                        <!-- item -->
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description_caisse" class="form-label fs-18">Description de la caisse</label>
                    <textarea class="form-control shadow" id="description_caisse" name="description_caisse" rows="3"
                        placeholder="Description de la caisse"></textarea>
                </div>
                <input type="hidden" name="caisse_destination_id" id="selected_gestionnaire_id">

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
                                    `<i class="fas fa-check text-success fs-18 me-2"></i><span class="fs-18">${selectedName}</span>`;

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
                const inputMontant = document.getElementById("montant_operation2");
                const montantCaisse = parseFloat(document.getElementById("montant_caisse2").value);
                const alertBox = document.getElementById("alert_montant2");
                const btnEnvoyer = document.getElementById("btn_envoyer2");

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
