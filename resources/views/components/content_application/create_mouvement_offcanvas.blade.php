<div class="offcanvas offcanvas-start" tabindex="-1" id="myOffcanvasmouvement{{ $caisse->id }}"
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
                    <p class="mb-1">Libellé caisse : {{ $caisse->libelle_caisse }}</p>
                    <h5 class="mb-3"><strong>Solde caisse : <span style="color: #B22222;">
                                {{ number_format($caisse->seuil_encaissement, 0, ',', ' ') }} </span></strong></h5>
                </div>

                <hr>

                <!-- ✅ Formulaire de création de mouvement -->
                <form id="form-creation-mouvement" class="form">
                    <!-- Date de l'opération -->
                    <div class="mb-3">
                        <label for="date_operation" class="form-label fs-15">Date de l'opération</label>
                        <input class="form-control shadow" type="text" id="date_operation" name="date_operation"
                            value="{{ now()->format('d/m/Y H:i') }}">
                    </div>

                    <!-- Montant -->
                    <div class="mb-3">
                        <label for="montant_operation" class="form-label fs-15">Montant de l'opération</label>
                        <input class="form-control shadow" type="text" id="montant_operation"
                            name="montant_operation" value="0,00">
                    </div>

                    <!-- ✅ Sélection de la catégorie -->
                    <div class="mb-3">
                        <label for="categorie_motif_radio" class="mb-2">
                            Veuillez sélectionner une catégorie pour l'opération
                        </label>
                        <br>
                        <input type="radio" class="btn-check" id="categorie_motif_radio" autocomplete="off"
                            name="categorie_motif_radio">
                        <label class="btn btn-outline-primary rounded-pill w-100 btn-sm" for="categorie_motif_radio">
                            <i class="las la-wallet fs-18 me-2"></i> Sélectionner la catégorie
                        </label>
                    </div>

                    <!-- Liste des catégories -->
                    <div id="liste-categories" class="card p-2" style="display: none;">
                        <input type="text" class="form-control shadow mb-2" id="categorie_search"
                            placeholder="Rechercher une catégorie">

                        <div id="categorie_container" class="ms-0 px-3" style="max-height:300px;" data-simplebar>
                            @foreach ($categorieMotifs as $categorieMotif)
                                <a href="#" class="dropdown-item py-3 categorie-item"
                                    data-id="{{ $categorieMotif->id }}"
                                    data-name="{{ $categorieMotif->nom_categorie }}">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0"><i class="fas fa-wallet fs-18"></i></div>
                                        <div class="flex-grow-1 ms-2 text-truncate">
                                            <h6 class="my-0 fw-normal text-dark fs-13">
                                                {{ $categorieMotif->nom_categorie }}</h6>
                                            <small class="text-muted mb-0">{{ $categorieMotif->type_operation }}</small>
                                        </div>
                                        <i class="fas fa-check text-success ms-auto" style="display: none;"></i>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- ✅ Bloc des motifs -->
                    <div id="bloc-libelle" class="mt-3" style="display:none;">
                        <label class="form-label fs-15 mb-2">Libellé d'encaissement</label>
                        <br>
                        <button type="button" id="btn-choisir-libelle" class="btn btn-outline-success w-100">
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

                    <!-- ✅ Champs cachés pour envoyer au back -->
                    <input type="hidden" name="categorie_motif_id" id="selected_categorie_id">
                    <input type="hidden" name="motif_id" id="selected_motif_id">

                </form>

            </div>
        </div>
    </div>
</div><!--end offcanvas-->
<div class="modal fade" id="exampleModalPrimary" tabindex="-1" role="dialog" aria-labelledby="exampleModalPrimary1"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h6 class="modal-title m-0 text-white" id="exampleModalPrimary1">Type de mouvement</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                        <button class="btn btn-sm btn-light border" data-bs-toggle="tooltip"
                                            data-bs-title="Modifier">
                                            <i class="fas fa-edit text-secondary fs-18"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light border ms-1" data-bs-toggle="tooltip"
                                            data-bs-title="Supprimer">
                                            <i class="fas fa-times-circle text-danger fs-18"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Autres Lignes -->
                                <tr>
                                    <td><span class="badge-op debit">Débit</span></td>
                                    <td>frais de gestion</td>
                                    <td class="d-flex justify-content-end">
                                        <button class="btn btn-sm btn-light border" data-bs-toggle="tooltip"
                                            data-bs-title="Modifier">
                                            <i class="fas fa-edit text-secondary fs-18"></i>
                                        </button>
                                        <button class="btn btn-sm btn-light border ms-1" data-bs-toggle="tooltip"
                                            data-bs-title="Supprimer">
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
                    href="#collapseExample" aria-expanded="true" aria-controls="collapseExample">Annuler</button>
                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="collapse"
                    href="#collapseExample" aria-expanded="true" aria-controls="collapseExample"><i
                        class="fas fa-plus-circle me-2"></i> Nouveau</button>
            </div><!--end modal-footer-->

        </div><!--end modal-content-->
    </div><!--end modal-dialog-->
</div><!--end modal-->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const radioCategorie = document.getElementById('categorie_motif_radio');
    const blocListe = document.getElementById('liste-categories');
    const searchInput = document.getElementById('categorie_search');
    const categorieItems = document.querySelectorAll('.categorie-item');
    const radioLabel = document.querySelector('label[for="categorie_motif_radio"]');

    const blocLibelle = document.getElementById('bloc-libelle');
    const btnChoisirLibelle = document.getElementById('btn-choisir-libelle');
    const motifsList = document.getElementById('motifs-list');
    const motifsItems = document.getElementById('motifs-items');
    const searchMotif = document.getElementById('search-motif');

    const hiddenCategorie = document.getElementById('selected_categorie_id');
    const hiddenMotif = document.getElementById('selected_motif_id');
    const loader = document.getElementById('loader');

    let selectedCategorieId = null;

    // ✅ Afficher / masquer la liste des catégories
    radioCategorie.addEventListener('change', () => {
        blocListe.style.display = radioCategorie.checked ? 'block' : 'none';
    });

    // ✅ Recherche dans la liste des catégories
    searchInput.addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        categorieItems.forEach(item => {
            const name = item.dataset.name.toLowerCase();
            item.style.display = name.includes(filter) ? '' : 'none';
        });
    });

    // ✅ Sélection d'une catégorie
    categorieItems.forEach(item => {
        item.addEventListener('click', function (e) {
            e.preventDefault();

            categorieItems.forEach(i => {
                i.classList.remove('bg-light');
                i.querySelector('.fa-check').style.display = 'none';
            });

            this.classList.add('bg-light');
            this.querySelector('.fa-check').style.display = 'inline';

            selectedCategorieId = this.dataset.id;
            const selectedName = this.dataset.name;

            hiddenCategorie.value = selectedCategorieId;
            radioLabel.innerHTML = `<i class="las la-check-circle fs-18 me-2"></i>${selectedName}`;

            radioCategorie.checked = false;
            blocListe.style.display = 'none';

            // ✅ Réinitialiser les motifs
            motifsItems.innerHTML = "";
            hiddenMotif.value = "";
            motifsList.style.display = "none";
            btnChoisirLibelle.innerHTML = `<i class="las la-plus-circle me-2"></i> Choisir un libellé`;

            blocLibelle.style.display = 'block';
        });
    });

    // ✅ Charger les motifs via AJAX
    btnChoisirLibelle.addEventListener('click', function () {
        if (!selectedCategorieId) return;

        loader.style.display = 'block';
        motifsList.style.display = 'none';

        fetch(`/categorie/${selectedCategorieId}/motifs`)
            .then(res => res.json())
            .then(data => {
                motifsItems.innerHTML = '';

                if (data.length === 0) {
                    motifsItems.innerHTML = `<li class="list-group-item text-danger">Aucun motif trouvé</li>`;
                } else {
                    data.forEach(motif => {
                        const li = document.createElement('li');
                        li.className = "list-group-item d-flex justify-content-between align-items-center motif-item";
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

                loader.style.display = 'none';
                motifsList.style.display = 'block';
            })
            .catch(() => {
                loader.style.display = 'none';
                motifsItems.innerHTML = `<li class="list-group-item text-danger">Erreur de chargement</li>`;
                motifsList.style.display = 'block';
            });
    });

    // ✅ Recherche dans les motifs après chargement
    searchMotif.addEventListener('input', function () {
        const filter = this.value.toLowerCase();
        Array.from(motifsItems.children).forEach(li => {
            const libelle = li.dataset.libelle || "";
            li.style.display = libelle.includes(filter) ? '' : 'none';
        });
    });

    // ✅ Sélection d’un motif
    motifsItems.addEventListener('click', function (e) {
        if (e.target.classList.contains('select-motif')) {
            e.preventDefault();

            const selectedId = e.target.dataset.id;
            const selectedName = e.target.parentElement.firstChild.textContent.trim();

            hiddenMotif.value = selectedId;
            btnChoisirLibelle.innerHTML = `<i class="las la-check me-2"></i> ${selectedName}`;
            motifsList.style.display = 'none';
        }
    });
});
</script>

