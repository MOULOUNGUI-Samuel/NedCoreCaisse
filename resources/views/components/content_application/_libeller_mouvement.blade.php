  @php
      $infos = \App\Helpers\DateHelper::dossier_info();
  @endphp

  <div class="modal fade bd-example-modal-xl" id="exampleModalFullscreen" tabindex="-1" role="dialog"
      aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h4 class="modal-title " id="exampleModalFullscreenLabel">Gestion des catégories et libellés de
                      mouvements</h4>
                  <button type="button" class="btn-close fs-22 border border-dark text-white" data-bs-dismiss="modal"
                      aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="row">
                      <div class="col-md-8">
                          <label for="">Recherche...</label>
                          <input type="text" id="searchInput" class="form-control mb-3 shadow"
                              placeholder="Rechercher une catégorie...">

                      </div>
                      <div class="col">
                          <label class="mt-1">Filtre</label>
                          <select class="form-select shadow" id="filterType">
                              <option value="">-- Tous les types --</option>
                              <option value="Débit">Débit</option>
                              <option value="Crédit">Crédit</option>
                          </select>
                      </div>
                  </div>
                  <div id="noResultsMessage" class="alert alert-warning d-none">
                      Aucun résultat trouvé.
                  </div>
                  <div class="row">
                      @foreach ($infos['categorieLibelles'] as $categorieLibelle)
                          <div class="col-md-4">
                              <div id="cardsContainer">
                                  <div class="card">
                                      <div class="card-body">
                                          <div class="position-absolute  end-0 me-3">
                                              <button type="button" class="btn btn-light rounded-pill shadow"
                                                  data-bs-toggle="offcanvas"
                                                  data-bs-target="#myOffcanvasAjoute{{ $categorieLibelle['categorieMotif']->id }}"
                                                  aria-controls="myOffcanvasAjoute{{ $categorieLibelle['categorieMotif']->id }}">
                                                  <i class="fas fa-plus-circle me-2"></i>Libellé
                                              </button>
                                          </div>
                                          <a href="#" data-bs-toggle="offcanvas"
                                              data-bs-target="#myOffcanvas{{ $categorieLibelle['categorieMotif']->id }}">
                                              <div class="flex-grow-1 ms-2 text-truncate ">
                                                  <h5 class="fw-bold mb-1 fs-15">
                                                      {{ $categorieLibelle['categorieMotif']->nom_categorie }}
                                                  </h5>
                                                  <p class="text-dark mb-0 fs-13 fw-semibold"><span
                                                          class="text-muted">Type :
                                                      </span>{{ $categorieLibelle['categorieMotif']->type_operation === 'Entrée' ? 'Débit' : 'Crédit' }}
                                                  </p>
                                              </div><!--end media-body-->
                                          </a>
                                      </div><!--end card-body-->
                                  </div><!--end card-->
                              </div>
                          </div>

                          <div class="offcanvas offcanvas-start" tabindex="-1"
                              id="myOffcanvasAjoute{{ $categorieLibelle['categorieMotif']->id }}"
                              aria-labelledby="offcanvasLabel">
                              <div class="offcanvas-header">
                                  <h4 class="offcanvas-title text-dark" id="offcanvasLabel">
                                      <i class="fas fa-plus-circle me-2"></i> Ajout des libellés
                                  </h4>
                                  <button type="button" class="btn-close text-reset fs-22 border border-dark"
                                      data-bs-dismiss="offcanvas" aria-label="Close"></button>
                              </div>
                              <div class="text-left">
                                  <strong>Catégorie :
                                      {{ $categorieLibelle['categorieMotif']->nom_categorie }}</strong>
                              </div>
                              <div class="offcanvas-body">
                                  <form action="{{ route('ajout.libelle') }}" method="POST"
                                      id="form-creation-categorie-{{ $categorieLibelle['categorieMotif']->id }}"
                                      class="form">
                                      @csrf

                                      {{-- CHAMP CACHÉ : C'est la clé pour lier les motifs à la bonne catégorie --}}
                                      <input type="hidden" name="categorie_id"
                                          value="{{ $categorieLibelle['categorieMotif']->id }}">

                                      <div class="text-end mb-2">
                                          <button type="submit" class="btn btn-light rounded-pill w-50 shadow">
                                              <i class="las la-save fs-18 me-2"></i>
                                              <span class="d-none d-sm-inline">Sauvegarder</span>
                                          </button>

                                      </div>
                                      <h5 class="mb-3">Libellé(s) de mouvements(s)</h5>
                                      <div id="motifs-container-{{ $categorieLibelle['categorieMotif']->id }}">
                                          <div class="motif-item d-flex mb-2">
                                              <input type="text" name="motifs[0][libelle_motif]"
                                                  class="form-control shadow me-2" placeholder="Libellé de mouvements"
                                                  required>
                                              <button type="button" class="btn btn-danger btn-remove-motif">X</button>
                                          </div>
                                      </div>

                                      <button type="button" class="btn btn-outline-primary mt-2"
                                          id="add-motif-btn-{{ $categorieLibelle['categorieMotif']->id }}">
                                          <i class="las la-plus-circle me-1"></i> Ajouter un libellé
                                      </button>
                                  </form>

                              </div>
                          </div>
                          {{-- Note : J'ai rendu les ID des conteneurs uniques pour éviter les conflits si vous avez plusieurs formulaires sur la même page. --}}
                          <script>
                              document.addEventListener("DOMContentLoaded", function() {
                                  // Cibler les éléments spécifiques à CE formulaire
                                  const categorieId = "{{ $categorieLibelle['categorieMotif']->id }}";
                                  const container = document.getElementById(`motifs-container-${categorieId}`);
                                  const addBtn = document.getElementById(`add-motif-btn-${categorieId}`);
                                  let motifIndex = container.getElementsByClassName('motif-item').length; // Commence à l'index correct

                                  addBtn.addEventListener("click", function() {
                                      const div = document.createElement("div");
                                      div.classList.add("motif-item", "d-flex", "mb-2");

                                      div.innerHTML = `
                                                    <input type="text" name="motifs[${motifIndex}][libelle_motif]" class="form-control shadow me-2" placeholder="Libellé de mouvements" required>
                                                    <button type="button" class="btn btn-danger btn-remove-motif">X</button>
                                                `;

                                      container.appendChild(div);
                                      motifIndex++;
                                  });

                                  container.addEventListener("click", function(e) {
                                      if (e.target && e.target.classList.contains("btn-remove-motif")) {
                                          e.target.closest(".motif-item").remove();
                                      }
                                  });
                              });
                          </script>
                          <div class="offcanvas offcanvas-start" tabindex="-1"
                              id="myOffcanvas{{ $categorieLibelle['categorieMotif']->id }}"
                              aria-labelledby="offcanvasLabel">

                              <div class="offcanvas-header">
                                  <h4 class="offcanvas-title text-dark" id="offcanvasLabel">
                                      <i class="fas fa-list me-2"></i> Libellés de mouvements
                                  </h4>
                                  <button type="button" class="btn-close text-reset fs-22 border border-dark"
                                      data-bs-dismiss="offcanvas" aria-label="Close"></button>
                              </div>
                              <div class="text-left">
                                  <strong>Catégorie :
                                      {{ $categorieLibelle['categorieMotif']->nom_categorie }}</strong>
                              </div>
                              <div class="offcanvas-body">
                                  {{-- Ce conteneur recevra les alertes générées par JavaScript --}}
                                  <div id="alert-container" class="mb-3"></div>
                                  <div id="libelles-container">
                                      @foreach ($categorieLibelle['libelle'] as $libelle)
                                          {{-- Chaque formulaire a un ID unique et une classe commune --}}
                                          <form class="form-edit-libelle" id="form-edit-libelle-{{ $libelle->id }}">
                                              <div class="card-body d-flex align-items-center mb-3">
                                                  <div class="flex-grow-1 me-2 text-truncate">
                                                      {{-- L'input a une classe pour être facilement ciblé --}}
                                                      <input type="text" class="form-control shadow libelle-input"
                                                          name="libelle_motif" value="{{ $libelle->libelle_motif }}">
                                                  </div>
                                                  <div class="">
                                                      {{-- 
                                                                    - Le bouton est désactivé par défaut.
                                                                    - Il a une classe pour le clic.
                                                                    - Il stocke l'ID du libellé dans un data attribute.
                                                                --}}
                                                      <button type="button"
                                                          class="btn btn-light rounded-pill shadow btn-update-libelle"
                                                          data-libelle-id="{{ $libelle->id }}" disabled>
                                                          <i class="fas fa-check fs-18 text-success"></i>
                                                      </button>
                                                  </div>
                                              </div>
                                          </form>
                                      @endforeach
                                  </div>

                              </div>
                          </div>
                      @endforeach

                  </div>
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
  <script>
      document.addEventListener('DOMContentLoaded', function() {
          const container = document.getElementById('libelles-container');

          // ===================================================================
          // NOUVELLE FONCTION HELPER POUR AFFICHER LES ALERTES
          // ===================================================================
          function showAlert(message, type = 'success') {
              const alertContainer = document.getElementById('alert-container');
              // Vider le conteneur pour ne pas empiler les alertes
              alertContainer.innerHTML = '';

              const isSuccess = type === 'success';

              // Définir les classes et l'icône en fonction du type de message
              const alertClass = isSuccess ? 'border-success text-success' : 'border-danger text-danger';
              const bgClass = isSuccess ? 'bg-success' : 'bg-danger';
              const iconClass = isSuccess ? 'fas fa-check' : 'fas fa-exclamation-triangle';
              const title = isSuccess ? 'Succès !' : 'Erreur !';

              // Créer l'élément d'alerte
              const alertDiv = document.createElement('div');
              alertDiv.className = `alert ${alertClass} alert-dismissible fade show rounded-pill mt-2`;
              alertDiv.setAttribute('role', 'alert');

              // Construire le HTML interne de l'alerte
              alertDiv.innerHTML = `
            <div class="d-inline-flex justify-content-center align-items-center thumb-xxs ${bgClass} rounded-circle mx-auto me-1">
                <i class="${iconClass} align-self-center mb-0 text-white"></i>
            </div>
            <strong>${title}</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;

              // Ajouter l'alerte à la page
              alertContainer.appendChild(alertDiv);

              // Optionnel : faire disparaître l'alerte après 5 secondes
              setTimeout(() => {
                  alertDiv.classList.remove('show');
                  // Attendre la fin de la transition de fondu pour supprimer l'élément
                  setTimeout(() => {
                      alertDiv.remove();
                  }, 150);
              }, 5000);
          }


          // ===================================================================
          // VOTRE LOGIQUE DE CLIC EXISTANTE, MAINTENANT MODIFIÉE
          // ===================================================================
          container.addEventListener('click', async function(e) {
              const updateBtn = e.target.closest('.btn-update-libelle');
              if (!updateBtn || updateBtn.disabled) return;

              // ... (votre code pour récupérer libelleId, form, inputField, etc. est ici)
              const libelleId = updateBtn.dataset.libelleId;
              const form = updateBtn.closest('.form-edit-libelle');
              const inputField = form.querySelector('.libelle-input');
              const newLibelleValue = inputField.value;
              const icon = updateBtn.querySelector('i');
              const originalIconClass = icon.className;

              updateBtn.disabled = true;
              icon.className = 'fas fa-spinner fa-spin fs-18';

              try {
                  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute(
                      'content');
                  const url = `/libelles/${libelleId}/update`;

                  const response = await fetch(url, {
                      method: 'PATCH',
                      // ... (vos headers et body)
                      headers: {
                          'Content-Type': 'application/json',
                          'X-CSRF-TOKEN': csrfToken,
                          'Accept': 'application/json'
                      },
                      body: JSON.stringify({
                          libelle_motif: newLibelleValue
                      })
                  });

                  const result = await response.json();

                  if (!response.ok) {
                      let errorMessage = result.error || (result.errors ? Object.values(result
                          .errors)[0][0] : 'Une erreur est survenue.');
                      throw new Error(errorMessage);
                  }

                  // SUCCÈS : On appelle notre nouvelle fonction au lieu de alert()
                  showAlert(result.success, 'success');
                  // Le bouton reste désactivé, c'est le comportement attendu après une sauvegarde réussie

              } catch (error) {
                  // ERREUR : On appelle aussi notre nouvelle fonction, mais avec le type 'danger'
                  showAlert(error.message, 'danger');
                  updateBtn.disabled = false; // On réactive le bouton pour permettre la correction

              } finally {
                  icon.className = originalIconClass;
              }
          });

          // ... (votre logique pour l'événement 'input' reste la même) ...
          container.addEventListener('input', function(e) {
              if (e.target && e.target.classList.contains('libelle-input')) {
                  const form = e.target.closest('.form-edit-libelle');
                  const updateBtn = form.querySelector('.btn-update-libelle');
                  updateBtn.disabled = false;
              }
          });
      });
  </script>
  <script>
      document.addEventListener("DOMContentLoaded", function() {
          const searchInput = document.getElementById("searchInput");
          const filterType = document.getElementById("filterType");
          const cards = document.querySelectorAll("#cardsContainer .card");
          const noResultsMessage = document.getElementById("noResultsMessage");

          function filterCards() {
              const query = searchInput.value.toLowerCase().trim();
              const selectedType = filterType.value;
              let visibleCount = 0;

              cards.forEach(function(card) {
                  const text = card.textContent.toLowerCase();
                  const typeText = card.querySelector("p").textContent.includes("Débit") ? "Débit" :
                      "Crédit";

                  const matchesText = text.includes(query);
                  const matchesType = selectedType === "" || typeText === selectedType;

                  if (matchesText && matchesType) {
                      card.style.display = "block";
                      visibleCount++;
                  } else {
                      card.style.display = "none";
                  }
              });

              noResultsMessage.classList.toggle("d-none", visibleCount > 0);
          }

          searchInput.addEventListener("keyup", filterCards);
          filterType.addEventListener("change", filterCards);
      });
  </script>
