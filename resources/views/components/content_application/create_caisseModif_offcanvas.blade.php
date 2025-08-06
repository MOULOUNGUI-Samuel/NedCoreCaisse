<div class="offcanvas offcanvas-start" tabindex="-1" id="myOffcanvasEdit" aria-labelledby="offcanvasLabelEdit">
    <div class="offcanvas-header">
        <h4 class="offcanvas-title text-primary" id="offcanvasLabelEdit">
            <i class="fas fa-edit me-2"></i>
            Modification de la caisse
        </h4>
        <button type="button" class="btn-close text-reset fs-22 border border-dark" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="card p-3">
            <form method="POST" action="{{ route('caisses.update', $caisse->id) }}" class="monFormulaire"
                data-form-id="formEdit">
                @csrf
                @method('PUT') {{-- Ou @method('PATCH') --}}

                <div class="text-end mb-2">
                    <button type="button" class="monBouton btn btn-light rounded-pill w-50 shadow"
                        data-button-for="formEdit" data-loader-target="modifier">
                        <i class="las la-save fs-18 me-2"></i>
                        <span class="d-none d-sm-inline">Modifier la caisse</span>
                    </button>
                    <button type="button" id="modifier" class="btn btn-light rounded-pill w-50 shadow"
                        style="display: none;" disabled>
                        <i class="fas fa-spinner fa-spin me-2"></i>Traitement...
                    </button>
                </div>
                <div class="messageErreur alert alert-danger" style="display: none;"></div>

                <div class="mb-3">
                    <label for="ibelle_caisse_edit" class="form-label fs-15">Libellé de la caisse</label>
                    <input class="form-control shadow" type="text" id="ibelle_caisse_edit"
                        value="{{ old('libelle_caisse', $caisse->libelle_caisse) }}" name="libelle_caisse"
                        placeholder="Libellé de la caisse" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fs-15">Limiter le solde de la caisse</label>
                    <div class="me-3 d-flex">
                        <input type="radio" class="btn-check" name="limiter_solde" id="limiter_solde_non_edit" autocomplete="off"
                            value="non" {{ old('limiter_solde', $caisse->seuil_maximum) >0  ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary rounded-pill w-50 btn-sm me-3" for="limiter_solde_non_edit">
                            <i class="las la-circle fs-18 me-2"></i>Non (Illimité)
                        </label>
                
                        <input type="radio" class="btn-check" name="limiter_solde" id="limiter_solde_oui_edit" autocomplete="off"
                            value="oui" {{ old('limiter_solde', $caisse->seuil_maximum) >0 ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary rounded-pill w-50 btn-sm" for="limiter_solde_oui_edit">
                            <i class="las la-circle fs-18 me-2"></i>Oui
                        </label>
                    </div>
                </div>
                
                <div class="mb-3" id="seuil_maximum_div_edit"
                    style="{{ old('limiter_solde', $caisse->seuil_maximum) >0  ? '' : 'display:none;' }}">
                    <label for="seuil_encaissement_edit" class="form-label fs-15">Seuil maximum du compte</label>
                    <input class="form-control shadow separateur-nombre" type="text" id="seuil_encaissement_edit" placeholder="0,00"
                        name="seuil_maximum" value="{{ old('seuil_maximum', $caisse->seuil_maximum) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fs-15" for="">Gestionnaire du compte</label>
                    <br>
                    <input type="radio" class="btn-check" id="gestionnaire_compte_edit" autocomplete="off"
                        name="gestionnaire_compte">
                    <label class="btn btn-outline-primary rounded-pill w-100 btn-sm" for="gestionnaire_compte_edit">
                        <i class="las la-users fs-18 me-2"></i> Sélectionner le gestionnaire du compte
                    </label>
                </div>

                <div id="gestionnaire_list_edit" class="card p-2" style="display: none;">
                    <input type="text" class="form-control shadow mb-2" id="gestionnaire_compte_recherche_edit"
                        placeholder="Rechercher un gestionnaire">
                    <div id="gestionnaire_items_container_edit" class="ms-0 px-3" style="max-height:300px;"
                        data-simplebar>
                        <!-- item -->
                        @if (count($users) > 0)
                            @foreach ($users as $user)
                                <a href="#" class="dropdown-item py-3 gestionnaire-item-edit"
                                    data-id="{{ $user->user->id }}" data-name="{{ $user->user->name }} {{ $user->user->username }}"
                                    @if ($caisse->user_id == $user->user->id) data-selected="true" @endif>
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 ">
                                            <img src="{{ asset('assets/images/user.jpg') }}" alt=""
                                                class="thumb-md rounded-circle">
                                        </div>
                                        <div class="flex-grow-1 ms-2 text-truncate">
                                            <h6 class="my-0 fw-normal text-dark fs-13 gestionnaire-name">
                                                {{ $user->user->name }} {{ $user->user->username }}</h6>
                                            <small class="text-muted mb-0">{{ $user->user->email }}</small>
                                        </div>
                                        <i class="fas fa-check text-success ms-auto" style="display: none;"></i>
                                    </div>
                                </a>
                            @endforeach
                        @else
                            <div class="text-center text-danger py-3">
                                Aucun gestionnaire trouvé,veuillez ajouter des utilisateurs dans la section
                                <a href="#" class="text-primary">Utilisateurs</a>.
                            </div>
                        @endif
                        <!-- item -->
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description_caisse_edit" class="form-label fs-15">Description de la caisse</label>
                    <textarea class="form-control shadow" id="description_caisse_edit" name="description_caisse" rows="3"
                        placeholder="Description de la caisse">{{ old('description_caisse', $caisse->description_caisse) }}</textarea>
                </div>
                <input type="hidden" name="user_id" id="selected_gestionnaire_id_edit"
                    value="{{ old('user_id', $caisse->user_id) }}" required>
            </form><!--end form-->
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const radioGestionnaireEdit = document.getElementById('gestionnaire_compte_edit');
        const radioGestionnaireLabelEdit = document.querySelector('label[for="gestionnaire_compte_edit"]');
        const blocListeEdit = document.getElementById('gestionnaire_list_edit');
        const searchInputEdit = document.getElementById('gestionnaire_compte_recherche_edit');
        const gestionnaireItemsEdit = document.querySelectorAll('.gestionnaire-item-edit');
        const selectedGestionnaireInputEdit = document.getElementById('selected_gestionnaire_id_edit');
        const formEdit = document.querySelector('.monFormulaire[data-form-id="formEdit"]');
        const submitButtonEdit = document.querySelector('.monBouton[data-button-for="formEdit"]');
        const messageErreurEdit = formEdit.querySelector('.messageErreur');
        const initialGestionnaireEdit = document.querySelector(
            '.gestionnaire-item-edit[data-selected="true"]'); // Gestionnaire initialement sélectionné

        // Afficher le gestionnaire initialement selectionné
        if (initialGestionnaireEdit) {
            // Remove selection from all items
            gestionnaireItemsEdit.forEach(i => {
                i.classList.remove('bg-light');
                i.querySelector('.fa-check').style.display = 'none';
            });

            // Add selection to the clicked item
            initialGestionnaireEdit.classList.add('bg-light');
            initialGestionnaireEdit.querySelector('.fa-check').style.display = 'inline';

            // Update hidden input and button label
            const selectedId = initialGestionnaireEdit.dataset.id;
            const selectedName = initialGestionnaireEdit.dataset.name;
            selectedGestionnaireInputEdit.value = selectedId;
            radioGestionnaireLabelEdit.innerHTML =
                `<i class="las la-user-check fs-18 me-2"></i>${selectedName}`;

            // Hide the list after selection
            radioGestionnaireEdit.checked = false;
            blocListeEdit.style.display = 'none';
        }

        // Toggle visibility of the list
        radioGestionnaireEdit.addEventListener('change', () => {
            blocListeEdit.style.display = radioGestionnaireEdit.checked ? 'block' : 'none';
        });

        // Handle search/filter
        searchInputEdit.addEventListener('input', function() {
            const filter = this.value.toLowerCase();
            gestionnaireItemsEdit.forEach(item => {
                const name = item.dataset.name.toLowerCase();
                item.style.display = name.includes(filter) ? '' : 'none';
            });
        });

        // Handle selection
        gestionnaireItemsEdit.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                // Remove selection from all items
                gestionnaireItemsEdit.forEach(i => {
                    i.classList.remove('bg-light');
                    i.querySelector('.fa-check').style.display = 'none';
                });

                // Add selection to the clicked item
                this.classList.add('bg-light');
                this.querySelector('.fa-check').style.display = 'inline';

                // Update hidden input and button label
                const selectedId = this.dataset.id;
                const selectedName = this.dataset.name;
                selectedGestionnaireInputEdit.value = selectedId;
                radioGestionnaireLabelEdit.innerHTML =
                    `<i class="las la-user-check fs-18 me-2"></i>${selectedName}`;

                // Hide the list after selection
                radioGestionnaireEdit.checked = false;
                blocListeEdit.style.display = 'none';
            });
        });

        // Interception de la soumission du formulaire
        submitButtonEdit.addEventListener('click', function(e) {
            // Validation du formulaire
            if (!formEdit.checkValidity() || selectedGestionnaireInputEdit.value === "") {
                e.preventDefault(); // Empêche la soumission du formulaire

                // Afficher les erreurs Bootstrap
                formEdit.classList.add('was-validated');

                // Afficher un message d'erreur spécifique si aucun gestionnaire n'est sélectionné
                if (selectedGestionnaireInputEdit.value === "") {
                    messageErreurEdit.textContent = "Veuillez sélectionner un gestionnaire de compte.";
                    messageErreurEdit.style.display = 'block';
                } else {
                    // Réinitialiser le message d'erreur si un gestionnaire est sélectionné mais d'autres erreurs sont présentes
                    messageErreurEdit.style.display = 'none';
                }

                return; // Stop la fonction
            }

            // Si la validation passe et qu'un gestionnaire est sélectionné
            submitButtonEdit.type = 'submit'; // Change le type du bouton pour soumettre le formulaire
            //form.submit(); // Soumettre le formulaire (optionnel, car changer le type suffit)
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        const limiterSoldeRadiosEdit = document.querySelectorAll(
        'input[name="limiter_solde"]'); // Garde le même nom car le name est le même
        const seuilMaximumDivEdit = document.getElementById('seuil_maximum_div_edit');

        function toggleSeuilMaximumEdit() {
            if (document.getElementById('limiter_solde_oui_edit').checked) {
                seuilMaximumDivEdit.style.display = 'block';
            } else {
                seuilMaximumDivEdit.style.display = 'none';
            }
        }

        limiterSoldeRadiosEdit.forEach(radio => {
            radio.addEventListener('change', toggleSeuilMaximumEdit);
        });

        // Initial check
        toggleSeuilMaximumEdit();
    });
</script>
