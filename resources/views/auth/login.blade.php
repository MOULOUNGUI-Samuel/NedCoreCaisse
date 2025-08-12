<!DOCTYPE html>
<html lang="en" dir="ltr" data-startbar="dark" data-bs-theme="light">


<!-- Mirrored from mannatthemes.com/materialy/default/auth-login.html by HTTrack Website Copier/3.x [XR&CO'2014], Fri, 04 Jul 2025 11:11:15 GMT -->

<head>


    <meta charset="utf-8" />
    <title>GestCaissePro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">
    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

</head>


<div class="container-xxl">
    <div class="row vh-100 d-flex justify-content-center">
        <div class="col-12 align-self-center">
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4 mx-auto">
                        <div class="card">
                            <div class="card-body p-0 bg-black auth-header-box rounded-top">
                                <div class="text-center p-3">
                                    <a href="index.html" class="logo logo-admin">
                                        <img src="assets/images/caisse.png" height="50" alt="logo"
                                            class="auth-logo">
                                    </a>
                                    <h4 class="mt-3 mb-1 fw-semibold text-white fs-18">Gerez vos finances avec
                                        YodiCaisse</h4>
                                    <p class="text-muted fw-medium mb-0">Connectez-vous pour continuer vers
                                        YodiCaisse.</p>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                @if ($errors->any())
                                    <div class="alert border-danger text-danger alert-dismissible fade show mb-0 mt-2"
                                        role="alert">
                                        <div
                                            class="d-inline-flex justify-content-center align-items-center thumb-xxs bg-danger rounded-circle mx-auto me-1">
                                            <i class="fas fa-xmark align-self-center mb-0 text-white "></i>
                                        </div>
                                        <strong>Erreur !</strong> {{ $errors->first() }} !
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif
                                <form method="POST" {{ route('login') }} class="app-form needs-validation my-4"
                                    novalidate onsubmit="return validateForm(event)">
                                    @csrf
                                    <div class="form-group mb-2">
                                        <label class="form-label" for="code_entreprise">Code entreprise</label>
                                        <input type="text" class="form-control shadow p-2" id="code_entreprise"
                                            name="code_entreprise" value="{{ old('code_entreprise') }}"
                                            placeholder="Code entreprise" style="font-size: 15px" required>
                                    </div><!--end form-group-->
                                    <div class="form-group mb-2">
                                        <label class="form-label" for="identifiant">Identifiant</label>
                                        <input type="text" class="form-control shadow p-2" id="identifiant"
                                            name="email" value="{{ old('email') }}" placeholder="Identifiant"
                                            style="font-size: 15px" required>
                                    </div><!--end form-group-->

                                    <div class="form-group">
                                        <label class="form-label" for="userpassword">Mot de passe</label>
                                        <input type="password" class="form-control shadow p-2" name="password"
                                            id="userpassword" placeholder="Mot de passe" style="font-size: 15px" required>
                                    </div><!--end form-group-->

                                    <div class="form-group row mt-3">
                                        <div class="col-sm-6">
                                            <div class="form-check form-switch form-switch-primary">
                                                <input class="form-check-input" type="checkbox"
                                                    id="customSwitchprimary">
                                                <label class="form-check-label" for="customSwitchprimary">Se souvenir de
                                                    moi</label>
                                            </div>
                                        </div><!--end col-->
                                        <div class="col-sm-6 text-end">
                                            <a href="#" class="text-muted font-13"><i class="dripicons-lock"></i>
                                                Mot de passe oublié ?</a>
                                        </div><!--end col-->
                                    </div><!--end form-group-->

                                    <div class="form-group mb-0 row">
                                        <div class="col-12">
                                            <div class="d-grid mt-3">
                                                <button class="btn btn-primary" type="submit"
                                                    data-loader-target="connexion">Se connecter <i
                                                        class="fas fa-sign-in-alt ms-1"></i></button>
                                                <!-- Bouton de chargement (caché au départ) -->
                                                <button type="button" id="connexion" class="btn btn-outline-primary"
                                                    style="display: none;" disabled>
                                                    <i class="fas fa-spinner fa-spin me-2"></i>Connexion en cours...
                                                </button>
                                            </div>
                                        </div><!--end col-->
                                    </div> <!--end form-group-->
                                </form><!--end form-->
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end card-body-->
        </div><!--end col-->
    </div><!--end row-->
</div><!-- container -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Cible tous les boutons ayant l'attribut data-loader-target
        document.querySelectorAll('[data-loader-target]').forEach(function(btn) { // Simplification du sélecteur
            btn.addEventListener('click', function(event) {
                const targetId = btn.getAttribute('data-loader-target');
                const loaderBtn = document.getElementById(targetId);

                if (btn.type === 'submit') {
                    const form = btn.closest('form');
                    if (form && !form.checkValidity()) {
                        // Si le formulaire n'est pas valide, empêche l'action par défaut
                        event.preventDefault();
                        event.stopPropagation();
                        form.classList.add('was-validated'); // Ajoute la classe Bootstrap pour afficher les erreurs
                        return;
                    }
                }

                if (loaderBtn) {
                    btn.style.display = 'none';
                    loaderBtn.style.display = 'inline-block';
                }
            });
        });
    });
</script>
<!-- Mirrored from phplaravel-1384472-5380003.cloudwaysapps.com/sign_up by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 12 Jun 2025 02:03:15 GMT -->

</html>
