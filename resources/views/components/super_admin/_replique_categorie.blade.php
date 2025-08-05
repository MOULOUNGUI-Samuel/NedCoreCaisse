<div class="offcanvas offcanvas-start" tabindex="-1" id="myOffcanvasSociete{{ $categorieLibelle_id }}"
    aria-labelledby="offcanvasLabel">

    <div class="offcanvas-header ">
        <h4 class="offcanvas-title text-primary" id="offcanvasLabel">
            <i class="fas fa-exchange-alt me-2"></i> Replication des données
        </h4>
        <button type="button" class="btn-close text-reset fs-22 border border-dark" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="text-left">
        <strong>Catégorie :
            {{ $categorieLibelle_nom }}</strong>
    </div>
    <div class="offcanvas-body">
        <form action="{{ route('replique.categorie') }}" method="POST"
            id="form-creation-categorie-{{ $categorieLibelle_id }}" class="form">
            @csrf
            <input type="hidden" name="categorieLibelle_id" value="{{$categorieLibelle_id}}">
            <div class="text-end mb-2">
                <button type="submit" class="btn btn-light rounded-pill w-50 shadow">
                    <i class="las la-save fs-18 me-2"></i>
                    <span class="d-none d-sm-inline">Sauvegarder</span>
                </button>

            </div>
            <div class="mb-3">
                <label for="societe" class="form-label">Societé</label>
                <select name="second_societe_id" id="societe" class="form-select shadow">
                    @foreach ($societes as $societe)
                        <option value="{{ $societe->id }}">{{ $societe->nom_societe }}</option>
                    @endforeach
                </select>
            </div>
        </form>
    </div>
</div>
