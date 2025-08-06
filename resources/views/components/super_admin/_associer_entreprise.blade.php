<div class="offcanvas offcanvas-start" tabindex="-1" id="myOffcanvasUser{{ $user->id }}"
    aria-labelledby="offcanvasLabel">

    <div class="offcanvas-header ">
        <h4 class="offcanvas-title text-primary" id="offcanvasLabel">
            <i class="me-1 fas fa-link"></i>  Associer à une societé
        </h4>
        <button type="button" class="btn-close text-reset fs-22 border border-dark" data-bs-dismiss="offcanvas"
            aria-label="Close"></button>
    </div>
    <div class="text-left">
        <strong>Societé d'origine :
            {{ $user->societe->nom_societe }}</strong>
    </div>
    <div class="offcanvas-body">
        <div class="p-3" style="overflow-y: auto;">
            <div class="row row-cols-3 g-2">
                @foreach ($societesParUtilisateur[$user->id] as $societe)
                    <div class="col text-center card-hover-zoom">
                        <a href="#" class="text-decoration-none text-dark d-block" data-bs-toggle="modal"
                            data-bs-target="#societeConfirmModal" data-societe-id="{{ $societe->id }}"
                            data-user-id="{{ $user->id }}" data-societe-nom="{{ $societe->nom_societe }}"
                            data-societe-logo="{{ asset('storage/' . $societe->logo) }}">
                            <div class="d-flex align-items-center justify-content-center mx-auto mb-2 shadow"
                                style="width: 80px;height: 70px;">
                                <img src="{{ asset('storage/' . $societe->logo) }}" alt="{{ $societe->nom_societe }}"
                                    class="img-fluid rounded"
                                    style="width: 80px;height: 70px; object-fit: contain; border-radius: 20px;">
                            </div>
                            <small class="fw-medium d-block text-truncate"
                                title="{{ $societe->nom_societe }}">{{ $societe->nom_societe }}</small>
                        </a>
                    </div>
                @endforeach
                <style>
                    .card-hover-zoom {
                        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    }

                    .card-hover-zoom:hover {
                        transform: scale(1.15);
                        z-index: 2;
                    }
                </style>
            </div>
        </div>
    </div>
    <!-- Modal de confirmation -->

</div>
