<div class="offcanvas offcanvas-start" tabindex="-1" id="myOffcanvasC" aria-labelledby="offcanvasLabel">
    <div class="offcanvas-header">
        <h4 class="offcanvas-title text-primary" id="offcanvasLabel"><i class="fas fa-plus-circle me-2"></i>
            Création d'une categorie</h4>
        <button type="button" class="btn-close text-reset fs-22 border border-dark" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="card p-3">
            <form method="POST" action="{{ route('categorie.store') }}" id="form-creation-categorie" class="form">
                @csrf

                <div class="text-end mb-2">
                    <button type="submit" class="btn btn-light rounded-pill w-50 shadow">
                        <i class="las la-save fs-18 me-2"></i>
                        <span class="d-none d-sm-inline">Sauvegarder</span>
                    </button>
                </div>
                
                <div class="mb-3">
                    <label for="nom_categorie" class="form-label fs-15">Libellé de la catégorie</label>
                    <input class="form-control shadow" type="text" id="nom_categorie"
                        value="{{ old('nom_categorie') }}" name="nom_categorie" placeholder="Libellé de la catégorie"
                        required>
                </div>
                <div class="mb-3">
                    <label class="form-label fs-15">Type d'opération</label>
                    <div class="me-3 d-flex">
                        <input type="radio" class="btn-check" name="type_operation" id="Entrée"
                            autocomplete="off" value="Entrée"
                            {{ old('Entrée') == 'Entrée' ? 'checked' : '' }}>
                        <label class="btn btn-outline-primary rounded-pill w-50 btn-sm me-3" for="Entrée" required>
                            <i class="las la-circle fs-18 me-2"></i>Encaissement
                        </label>

                        <input type="radio" class="btn-check" name="type_operation" id="Sortie"
                            autocomplete="off" value="Sortie" {{ old('Sortie') == 'Sortie' ? 'checked' : '' }} required>
                        <label class="btn btn-outline-primary rounded-pill w-50 btn-sm" for="Sortie">
                            <i class="las la-circle fs-18 me-2"></i>Décaissement
                        </label>
                    </div>
                </div>
                <hr>
                <h5 class="mb-3">Libellé(s) d'encaissement</h5>
                <div id="motifs-container">
                    <div class="motif-item d-flex mb-2">
                        <input type="text" name="motifs[0][libelle_motif]" class="form-control shadow me-2"
                            placeholder="Libellé d'encaissement" required>
                        <button type="button" class="btn btn-danger remove-motif">X</button>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-primary mt-2" id="add-motif">
                    <i class="las la-plus-circle me-1"></i> Ajouter un libellé
                </button>
            </form>

        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let motifIndex = 1;
            const container = document.getElementById("motifs-container");
            const addBtn = document.getElementById("add-motif");

            addBtn.addEventListener("click", function() {
                const div = document.createElement("div");
                div.classList.add("motif-item", "d-flex", "mb-2");
                div.innerHTML = `
                <input type="text" name="motifs[${motifIndex}][libelle_motif]" class="form-control shadow me-2" placeholder="Libellé d'encaissement" required>
                <button type="button" class="btn btn-danger remove-motif">X</button>
            `;
                container.appendChild(div);
                motifIndex++;
            });

            container.addEventListener("click", function(e) {
                if (e.target.classList.contains("remove-motif")) {
                    e.target.parentElement.remove();
                }
            });
        });
    </script>

</div>
