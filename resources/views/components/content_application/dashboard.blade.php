@extends('layouts.app')

@section('title', 'Mon Tableau de bord')

@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <div class="container-fluid">

                {{-- Vérifier si des données sont disponibles --}}


                {{-- PARTIE 1 : LES CARTES DE STATISTIQUES --}}
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0 bg-gradient-purple">
                            <div class="card-body">
                                <span class="fs-14 fw-semibold">Encaissements du jour</span>
                                <h4 class="my-2 fs-22 fw-semibold">50000
                                </h4>
                                {{-- Le calcul de pourcentage nécessite les données de la veille, on le laisse statique pour le moment --}}
                                <p class="text-muted fs-13"><span class="text-success"><i
                                            class="fas fa-arrow-up me-1"></i>--,--%</span> Augmentation du solde</p>
                            </div>
                            {{-- <div id="apexBar2"></div> --}}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-3">
                        <div class="card border-0 bg-gradient-info">
                            <div class="card-body">
                                <span class="fs-14 fw-semibold">Décaissements du jour</span>
                                <h4 class="text-dark my-2 fw-semibold fs-22">
                                    8</h4>
                                <p class="text-muted fs-13"><span class="text-danger"><i
                                            class="fas fa-arrow-down me-1"></i>--,--%</span> Diminution du solde</p>
                            </div>
                            {{-- <div id="apexBar4"></div> --}}
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <div class="card border-0 bg-gradient-danger">
                            <div class="card-body">
                                <span class="fs-14 fw-semibold">Solde net du jour</span>
                                <h4 class="text-dark my-2 fw-semibold fs-22">5
                                </h4>
                                <p class="text-muted fs-13"><span class="text-danger"><i
                                            class="fas fa-arrow-down me-1"></i>--,--%</span> Solde négatif</p>

                            </div>
                            {{-- <div id="apexBar3"></div> --}}
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-3">
                        <div class="card border-0 bg-gradient-warning">
                            <div class="card-body">
                                <span class="fs-14 fw-semibold">Opérations du jour</span>
                                <h4 class="text-dark my-2 fw-semibold fs-22">
                                    5</h4>
                                <p class="text-muted fs-13"><span class="text-success"><i
                                            class="fas fa-arrow-up me-1"></i>--,--%</span> Valeur totale déplacée</p>
                            </div>
                            {{-- <div id="apexBar5"></div> --}}
                        </div>
                    </div>
                </div>

                <div class="row justify-content-center">
                    {{-- PARTIE 2 : TABLEAU DES TRANSACTIONS RÉCENTES --}}
                    <div class="col-12 col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Transactions du jour</h4>
                            </div>
                            <div class="card-body pt-0">
                                <div class="table-responsive">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th>Nom</th>
                                                <th>Date</th>
                                                <th>Type</th>
                                                <th>Description</th>
                                                <th>Montant</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- PARTIE 3 : LISTE DES CAISSES IMPACTÉES --}}
                    <div class="col-12 col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-1">Vos Caisses</h4>
                                <p class="mb-0 text-truncate fs-13 text-muted fw-medium">5
                                    caisse(s)
                                    trouvée(s)</p>
                            </div>
                            <div class="card-body p-0">

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <!--Start Rightbar-->
    <!--Start Endbar-->
    @include('layouts.lateralContent')


    <!--end Endbar-->
    <div class="endbar-overlay d-print-none"></div>

    <!--end Rightbar-->
    <!--Start Footer-->

    <footer class="footer text-center text-sm-start d-print-none">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12 px-0">
                    <div class="card mb-0 rounded-bottom-0 border-0">
                        <div class="card-body">
                            <p class="text-muted mb-0">
                                ©
                                <script>
                                    document.write(new Date().getFullYear())
                                </script>
                                Materialy
                                <span class="text-muted d-none d-sm-inline-block float-end">
                                    Design with
                                    <i class="iconoir-heart-solid text-danger align-middle"></i>
                                    by Mannatthemes</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

@endsection
