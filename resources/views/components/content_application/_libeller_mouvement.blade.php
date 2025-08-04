  @php
      $infos = \App\Helpers\DateHelper::dossier_info();
  @endphp
  <div class="modal fade bd-example-modal-xl" id="exampleModalFullscreen" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title " id="exampleModalFullscreenLabel">Gestion des libellés de mouvements</h4>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-md-4">
                          <div class="card">
                              <div class="card-body">
                                  <h5 class="card-title">Tapez une rechercher</h5>
                                  <form>
                                      <div class="mb-3">
                                          <input type="text" class="form-control shadow" id="searchLibelle"
                                              placeholder="Entrez les mots-clés...">
                                      </div>
                                      <div class="mb-3">
                                          <label for="searchType" class="form-label">Type de mouvement</label>
                                          <select class="form-select" id="searchType">
                                              <option value="">Tous</option>
                                              <option value="debit">Débit</option>
                                              <option value="credit">Crédit</option>
                                          </select>
                                      </div>
                                  </form>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-8">
                          <div class="row">
                              @foreach ($infos['categorieLibelles'] as $categorieLibelle)
                                  <div class="col-md-6">
                                      <div class="card">
                                          <div class="card-body">
                                              <div class="position-absolute  end-0 me-3">
                                                  <button type="button" class="btn btn-light rounded-pill shadow"
                                                      data-bs-toggle="offcanvas" data-bs-target="#myOffcanvasM"
                                                      aria-controls="myOffcanvasM">
                                                      <i class="fas fa-plus-circle me-2"></i>Libellé
                                                  </button>
                                              </div>
                                              <a href="#" data-bs-toggle="offcanvas"
                                            data-bs-target="#myOffcanvas{{ $categorieLibelle['categorieMotif']->id }}">
                                                  <div class="flex-grow-1 ms-2 text-truncate">
                                                      <h5 class="fw-bold mb-1 fs-15">
                                                          {{ $categorieLibelle['categorieMotif']->nom_categorie }}
                                                      </h5>
                                                      <p class="text-dark mb-0 fs-13 fw-semibold"><span
                                                              class="text-muted">type
                                                              :</span>{{ $categorieLibelle['categorieMotif']->type_operation === 'Entrée' ? 'Débit' : 'Crédit' }}
                                                      </p>
                                                  </div><!--end media-body-->
                                              </a>
                                          </div><!--end card-body-->
                                      </div><!--end card-->
                                  </div>
                                  <div class="offcanvas offcanvas-start" tabindex="-1"
                                      id="myOffcanvas{{ $categorieLibelle['categorieMotif']->id }}"
                                      aria-labelledby="offcanvasLabel">

                                      <div class="offcanvas-header bg-dark">
                                          <h4 class="offcanvas-title text-white" id="offcanvasLabel">
                                              <i class="fas fa-exchange-alt me-2"></i> Liste des libellés
                                              pour {{ $categorieLibelle['categorieMotif']->nom_categorie }}
                                          </h4>
                                          <button type="button" class="btn-close text-reset fs-22 border border-dark"
                                              data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                      </div>

                                      <div class="offcanvas-body">
                                          @foreach ($categorieLibelle['libelle'] as $libelle)
                                              <div class="card">
                                                  <div class="card-body">
                                                      <div class="position-absolute  end-0 me-3">
                                                          <button type="button"
                                                              class="btn btn-light rounded-pill shadow"
                                                              data-bs-toggle="offcanvas" data-bs-target="#myOffcanvasM"
                                                              aria-controls="myOffcanvasM">
                                                              <i class="fas fa-plus-circle me-2"></i>Libellé
                                                          </button>
                                                      </div>
                                                      <div class="flex-grow-1 ms-2 text-truncate">
                                                          <h5 class="fw-bold mb-1 fs-15">
                                                              {{ $libelle->libelle_motif }}
                                                          </h5>
                                                      </div><!--end media-body-->
                                                  </div><!--end card-body-->
                                              </div><!--end card-->
                                          @endforeach

                                      </div>
                                  </div>
                              @endforeach

                          </div>
                      </div>
                  </div>

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              </div>
          </div>
          <script>
              let currentPage = 1;
              let nextPage = null;

              document.getElementById('searchLibelle').addEventListener('input', lancerNouvelleRecherche);
              document.getElementById('searchType').addEventListener('change', lancerNouvelleRecherche);

              function lancerNouvelleRecherche() {
                  currentPage = 1;
                  document.querySelector('.col-md-8 .row').innerHTML = '';
                  rechercher();
              }

              function rechercher() {
                  const keyword = document.getElementById('searchLibelle').value;
                  const type = document.getElementById('searchType').value;

                  fetch(`/recherche-libelles?search=${encodeURIComponent(keyword)}&type=${type}&page=${currentPage}`)
                      .then(res => res.json())
                      .then(res => {
                          afficherResultats(res.data);
                          nextPage = res.nextPage;
                          gererBoutonVoirPlus();
                      });
              }

              function afficherResultats(data) {
                  const container = document.querySelector('.col-md-8 .row');
                  data.forEach(categorie => {
                      if (categorie.motifs.length === 0) return;
                      const card = document.createElement('div');
                      card.className = 'col-md-6 mb-3';
                      card.innerHTML = `
                <div class="card">
                    <div class="card-body">
                        <h5 class="fw-bold fs-15">${categorie.nom}</h5>
                        <p class="text-muted mb-2"><strong>Type :</strong> ${categorie.type}</p>
                        <ul>${categorie.motifs.map(m => `<li>${m.libelle}</li>`).join('')}</ul>
                    </div>
                </div>`;
                      container.appendChild(card);
                  });
              }

              function gererBoutonVoirPlus() {
                  let bouton = document.getElementById('btnVoirPlus');
                  if (!bouton) {
                      bouton = document.createElement('button');
                      bouton.id = 'btnVoirPlus';
                      bouton.className = 'btn btn-outline-primary mt-3';
                      bouton.textContent = 'Voir plus';
                      bouton.onclick = function() {
                          if (nextPage) {
                              currentPage = nextPage;
                              rechercher();
                          }
                      };
                      document.querySelector('.col-md-8 .row').after(bouton);
                  }
                  bouton.style.display = nextPage ? 'block' : 'none';
              }

              // Chargement initial
              rechercher();
          </script>

      </div>
  </div>
