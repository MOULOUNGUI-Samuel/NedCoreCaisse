{{-- Fichier : resources/views/components/caisses/_mouvements_table.blade.php --}}

{{-- @if ($activeCaisse) --}}
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h4 class="card-title">Mouvement récent de la caisse - xx</h4>
                </div>
            </div>
        </div>
        <div class="card-body pt-0 ">
            <div class="table-responsive">
                <table class="table mb-0 table-striped">
                    {{-- On ne met pas de <thead> car chaque ligne est très descriptive --}}
                    <tbody>
                        {{-- @forelse ($mouvements as $mvt) --}}
                            {{-- ... Votre code de la ligne <tr> reste exactement le même ... --}}
                            <tr class="align-middle bg-white" style="border-bottom: 1px solid #dee2e6;">
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('assets/images/user.jpg') }}" height="34"
                                            class="me-3 align-self-center rounded border bg-white" alt="...">
                                        <div class="flex-grow-1 text-truncate">
                                            {{-- Utilisateur qui a fait le mouvement --}}
                                            <h6 class="m-0 mb-2 fs-13">
                                                user</h6>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center">
                                    {{-- On indique à Carbon le format exact de la chaîne d'entrée --}}
                                    <span
                                        class="fw-bold text-dark">date</span>
                                </td>
                                <td>
                                    {{-- Logique pour afficher Débit ou Crédit --}}
                                    @php
                                        $isDebit = (2 ?? 0) > 0;
                                    @endphp
                                    <div
                                        style="border-left: 3px solid {{ $isDebit ? '#ff0000' : '#086721' }}; padding-left: 10px;">
                                        <span class="badge text-white mb-1"
                                            style="background-color: {{ $isDebit ? '#ff0000' : '#086721' }};">
                                            {{ $isDebit ? 'Débit' : 'Crédit' }}
                                        </span><br>
                                        <span
                                            class="text-dark fw-semibold">motif</span><br>
                                        <small class="text-muted">remarque</small>
                                    </div>
                                </td>
                                <td class="text-end fw-semibold">
                                    <span class="badge bg-transparent text-dark fs-12 mb-2">Montant
                                        débit</span><br>
                                    <span
                                        class="text-danger fw-semibold">2</span>
                                </td>
                                <td class="text-end text-muted">
                                    <span class="badge bg-transparent text-dark fs-12 mb-2">Montant
                                        crédit</span><br>
                                    <span
                                        class="text-dark fw-semibold">3</span>
                                </td>
                                {{-- L'API ne fournit pas de solde par ligne, on omet cette colonne pour l'instant --}}
                                <td class="text-end fw-bold text-dark">
                                    {{-- Colonne de solde vide ou à retirer --}}
                                </td>
                                {{-- <td class="text-end">
                                    <button class="btn btn-sm btn-light border" data-bs-toggle="tooltip"
                                        data-bs-title="Modifier"><i
                                            class="fas fa-edit text-secondary fs-18"></i></button>
                                    <button class="btn btn-sm btn-light border ms-1" data-bs-toggle="tooltip"
                                        data-bs-title="Supprimer"><i
                                            class="fas fa-times-circle text-danger fs-18"></i></button>
                                </td> --}}
                            </tr>
                        {{-- @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <p>Aucun mouvement trouvé pour cette caisse sur la période sélectionnée.</p>
                                </td>
                            </tr>
                        @endforelse --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
{{-- @endif --}}
