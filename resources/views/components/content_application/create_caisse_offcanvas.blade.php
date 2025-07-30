<div class="offcanvas offcanvas-start" tabindex="-1" id="myOffcanvas" aria-labelledby="offcanvasLabel">
    <div class="offcanvas-header">
        <h4 class="offcanvas-title text-primary" id="offcanvasLabel"><i class="fas fa-plus-circle me-2"></i>
            Création d'une caisse</h4>
        <button type="button" class="btn-close fs-28 text-primary" data-bs-dismiss="offcanvas"
            aria-label="Fermer"></button>
    </div>
    <div class="offcanvas-body">
        <div class="card p-3">
            <form method="POST" action="{{ route('caisses.store') }}" id="form-creation-caisse" class="form">
                @csrf
                <div class="text-end mb-2">
                    <button type="submit" class="btn btn-light rounded-pill w-50 shadow">
                        <i class="las la-save fs-18 me-2"></i>
                        <span class="d-none d-sm-inline">Créer la caisse</span>
                    </button>
                </div>
                <div class="mb-3">
                    <label for="libelle_caisse" class="form-label fs-15">Libellé de la caisse</label>
                    <input class="form-control shadow" type="text" id="libelle_caisse"
                        value="{{ old('libelle_caisse') }}" name="libelle_caisse" placeholder="Libellé de la caisse"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label fs-15">Limiter le solde de la caisse</label>
                    <div class="me-3 d-flex">
                        <input type="radio" class="btn-check" name="limiter_solde" id="limiter_solde_non"
                            autocomplete="off" value="non"
                            {{ old('limiter_solde', 'non') == 'non' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary rounded-pill w-50 btn-sm me-3" for="limiter_solde_non">
                            <i class="las la-circle fs-18 me-2"></i>Non (Illimité)
                        </label>

                        <input type="radio" class="btn-check" name="limiter_solde" id="limiter_solde_oui"
                            autocomplete="off" value="oui" {{ old('limiter_solde') == 'oui' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary rounded-pill w-50 btn-sm" for="limiter_solde_oui">
                            <i class="las la-circle fs-18 me-2"></i>Oui
                        </label>
                    </div>
                </div>

                <div class="mb-3" id="seuil_maximum_div"
                    style="{{ old('limiter_solde') == 'oui' ? '' : 'display:none;' }}">
                    <label for="seuil_maximum" class="form-label fs-15">Seuil maximum du compte</label>
                    <input class="form-control shadow" type="text" id="seuil_encaissement" placeholder="0,00"
                        name="seuil_maximum" value="{{ old('seuil_maximum') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label fs-15">Gestionnaire du compte</label>
                    <input type="radio" class="btn-check" id="gestionnaire_compte" autocomplete="off"
                        name="gestionnaire_compte" >
                    <label class="btn btn-outline-primary rounded-pill w-100 btn-sm" for="gestionnaire_compte">
                        <i class="las la-users fs-18 me-2"></i> Sélectionner le gestionnaire du compte
                    </label>
                </div>

                <div id="gestionnaire_list" class="card p-2" style="display: none;">
                    <input type="text" class="form-control shadow mb-2"
                        id="gestionnaire_compte_recherche" placeholder="Rechercher un gestionnaire">
                    <div id="gestionnaire_items_container" class="ms-0 px-3" style="max-height:300px;" data-simplebar>
                        <!-- item -->
                        @if (count($users) > 0)
                            @foreach ($users as $user)
                                <a href="#" class="dropdown-item py-3 gestionnaire-item" data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }} {{ $user->username }}">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0 ">
                                            <img src="{{ asset('assets/images/user.jpg') }}" alt=""
                                                class="thumb-md rounded-circle">
                                        </div>
                                        <div class="flex-grow-1 ms-2 text-truncate">
                                            <h6 class="my-0 fw-normal text-dark fs-13 gestionnaire-name">
                                                {{ $user->name }} {{ $user->username }}</h6>
                                            <small class="text-muted mb-0">{{ $user->email }}</small>
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
                    <label for="description_caisse" class="form-label fs-15">Description de la caisse</label>
                    <textarea class="form-control shadow" id="description_caisse" name="description_caisse" rows="3"
                        placeholder="Description de la caisse"></textarea>
                </div>
                <input type="hidden" name="user_id" id="selected_gestionnaire_id">

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
