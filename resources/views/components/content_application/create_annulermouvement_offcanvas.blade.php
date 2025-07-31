<div class="offcanvas offcanvas-top" tabindex="-1" 
     id="myOffcanvas{{ $mvt->num_mouvement }}" 
     aria-labelledby="offcanvasLabel"> 

    <div class="offcanvas-header bg-danger">
        <h4 class="offcanvas-title text-white" id="offcanvasLabel">
            <i class="fas fa-exchange-alt me-2"></i> Annulation de l'opération
        </h4>
        <button type="button" class="btn-close fs-28 text-white" data-bs-dismiss="offcanvas"></button>
    </div>

    <div class="offcanvas-body">
            <div class="spinner-border text-primary d-none" id="loader-{{ $mvt->num_mouvement }}"></div>
            <div id="mouvements-list-{{ $mvt->num_mouvement }}"></div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".btn-show-mouvements").forEach(button => {
        button.addEventListener("click", function () {
            const numMouvement = this.dataset.num;
            const loader = document.getElementById(`loader-${numMouvement}`);
            const container = document.getElementById(`mouvements-list-${numMouvement}`);

            loader.classList.remove("d-none");
            container.innerHTML = "";

            fetch(`/mouvements/${numMouvement}/associes`)
                .then(res => res.json())
                .then(data => {
                    loader.classList.add("d-none");

                    if (data.length === 0) {
                        container.innerHTML = `<p class="text-danger">Aucun mouvement associé trouvé.</p>`;
                        return;
                    }

                    let tableRows = "";

                    data.forEach(mvt => {
                        const isDebit = mvt.montant_debit > 0;
                        const montantDebit = new Intl.NumberFormat('fr-FR').format(mvt.montant_debit);
                        const montantCredit = new Intl.NumberFormat('fr-FR').format(mvt.montant_credit);
                        const soldeAvant = new Intl.NumberFormat('fr-FR').format(mvt.solde_avant_mouvement);
                        const soldeApres = new Intl.NumberFormat('fr-FR').format(mvt.solde_apres_mouvement);

                        tableRows += `
                            <tr class="align-middle bg-white" style="border-bottom: 1px solid #dee2e6;">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="/assets/images/user.jpg" height="34" class="me-3 rounded border bg-white">
                                        <div class="flex-grow-1 text-truncate">
                                            <h6 class="m-0 mb-2 fs-13">${mvt.operateur?.username ?? 'Utilisateur'}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold text-dark">${new Date(mvt.date_mouvement).toLocaleString('fr-FR')}</span>
                                </td>
                                <td>
                                    <div style="border-left: 3px solid ${isDebit ? '#ff0000' : '#086721'}; padding-left: 10px;">
                                        <span class="badge text-white mb-1" style="background-color: ${isDebit ? '#ff0000' : '#086721'};">
                                            ${isDebit ? 'Débit' : 'Crédit'}
                                        </span><br>
                                        <span class="text-dark fw-semibold">${mvt.motif_standard?.libelle_motif ?? mvt.libelle_personnalise ?? ''}</span><br>
                                        <small class="text-muted">${mvt.observations ?? ''}</small>
                                    </div>
                                </td>
                                <td class="text-end fw-semibold">
                                    <span class="badge bg-transparent text-dark fs-12 mb-2">Montant débit</span><br>
                                    <span class="text-danger fw-semibold">${montantDebit}</span>
                                </td>
                                <td class="text-end text-muted">
                                    <span class="badge bg-transparent text-dark fs-12 mb-2">Montant crédit</span><br>
                                    <span class="text-dark fw-semibold">${montantCredit}</span>
                                </td>
                                <td class="text-end fw-bold text-info">
                                    <span class="badge bg-transparent text-info fs-12 mb-2">Ancien solde</span><br>
                                    ${soldeAvant}
                                </td>
                                <td class="text-end fw-bold text-primary">
                                    <span class="badge bg-transparent text-primary fs-12 mb-2">Nouveau solde</span><br>
                                    ${soldeApres}
                                </td>
                            </tr>
                        `;
                    });

                    // ✅ Affichage final en 2 colonnes
                    container.innerHTML = `
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="mb-3">Mouvements associés à l'opération <strong>${numMouvement}</strong></h5>
                                <p class="text-muted mb-3">
                                    Voici les mouvements associés à l'opération <strong>${numMouvement}</strong>.
                                    Vous pouvez annuler ces mouvements si nécessaire.
                                </p>
                                <div class="card">
                                    <table class="table mb-0 table-striped">
                                        <tbody>
                                            ${tableRows}
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <form method="POST" action="{{ route('mouvements.annuler.numero', $mvt->num_mouvement) }}">
                                    @csrf
                                    <label class="form-label">Motif d'annulation</label>
                                    <textarea name="motif_annulation" class="form-control mb-3 shadow" rows="4" required></textarea>
                                    <button type="submit" class="btn btn-danger w-100">
                                        <i class="fas fa-times-circle me-1"></i> Annuler les mouvements
                                    </button>
                                </form>
                            </div>
                        </div>
                    `;
                })
                .catch(() => {
                    loader.classList.add("d-none");
                    container.innerHTML = `<p class="text-danger">Erreur lors du chargement.</p>`;
                });
        });
    });
});
</script>


