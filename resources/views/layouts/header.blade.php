<header class="header-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8 col-sm-6 d-flex align-items-center header-left p-0">
                <span class="header-toggle ">
                    <i class="ph ph-squares-four"></i>
                </span>
                <div class="header-searchbar w-100">
                    <form action="#" class="mx-sm-3 app-form app-icon-form ">
                        <div class="position-relative">
                            <input aria-label="Search" class="form-control" placeholder="Rechercher une tontine, un membre..." type="search">
                            <i class="ti ti-search text-dark"></i>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-4 col-sm-6 d-flex align-items-center justify-content-end header-right p-0">
                <ul class="d-flex align-items-center">
                    <li class="header-language">
                        <div class="flex-shrink-0 dropdown" id="lang_selector">
                            <a aria-expanded="false" class="d-block head-icon ps-0" data-bs-toggle="dropdown" href="#">
                                <div class="lang-flag lang-fr">
                                    <span class="flag rounded-circle overflow-hidden">
                                        <i class="flag-icon flag-icon-fra flag-icon-squared"></i>
                                    </span>
                                </div>
                            </a>
                            <ul class="dropdown-menu language-dropdown header-card border-0">
                                <li class="lang lang-fr selected dropdown-item p-2" title="FR">
                                    <span class="d-flex align-items-center">
                                        <i class="flag-icon flag-icon-fra flag-icon-squared rounded-circle f-s-20"></i>
                                        <span class="ps-2">Français</span>
                                    </span>
                                </li>
                                <li class="lang lang-en dropdown-item p-2" data-bs-placement="top" data-bs-toggle="tooltip" title="EN">
                                    <span class="d-flex align-items-center">
                                        <i class="flag-icon flag-icon-gbr flag-icon-squared rounded-circle f-s-20"></i>
                                        <span class="ps-2">English</span>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="header-apps">
                        <a aria-controls="appscanvasRights" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2" data-bs-target="#appscanvasRights" data-bs-toggle="offcanvas" href="#" role="button" title="Actions Rapides">
                            <i class="ph ph-lightning"></i>
                        </a>
                        <div aria-labelledby="appscanvasRightsLabel" class="offcanvas offcanvas-end header-apps-canvas" id="appscanvasRights" tabindex="-1">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="appscanvasRightsLabel">Actions Rapides</h5>
                                <button aria-label="Close" class="btn-close" data-bs-dismiss="offcanvas" type="button"></button>
                            </div>
                            <div class="offcanvas-body app-scroll">
                                <div class="row row-cols-3 g-2">
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-primary w-100 rounded-3 py-3 px-2" href="#">
                                            <span><i class="ph-light ph-plus-circle f-s-30"></i></span>
                                            <p class="mb-0 f-w-500 text-dark">Créer Tontine</p>
                                        </a>
                                    </div>
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-success w-100 rounded-3 py-3 px-2" href="#">
                                            <span><i class="ph-light ph-arrow-circle-up f-s-30"></i></span>
                                            <p class="mb-0 f-w-500 text-dark">Déposer Argent</p>
                                        </a>
                                    </div>
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-info w-100 rounded-3 py-3 px-2" href="#">
                                            <span><i class="ph-light ph-user-plus f-s-30"></i></span>
                                            <p class="mb-0 f-w-500 text-dark">Inviter Membre</p>
                                        </a>
                                    </div>
                                    <div class="d-flex-center text-center">
                                        <a class="text-light-danger w-100 rounded-3 py-3 px-2" href="#">
                                            <span><i class="ph-light ph-arrow-circle-down f-s-30"></i></span>
                                            <p class="mb-0 f-w-500 text-dark">Retirer Argent</p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="header-dark">
                        <div class="sun-logo head-icon bg-light-dark rounded-circle f-s-22 p-2">
                            <i class="ph ph-moon-stars"></i>
                        </div>
                        <div class="moon-logo head-icon bg-light-dark rounded-circle f-s-22 p-2">
                            <i class="ph ph-sun-dim"></i>
                        </div>
                    </li>
                    <li class="header-notification">
                        <a aria-controls="notificationcanvasRight" class="d-block head-icon position-relative bg-light-dark rounded-circle f-s-22 p-2" data-bs-target="#notificationcanvasRight" data-bs-toggle="offcanvas" href="#" role="button">
                            <i class="ph ph-bell"></i>
                            <span class="position-absolute translate-middle p-1 bg-primary border border-light rounded-circle"></span>
                        </a>
                        <div aria-labelledby="notificationcanvasRightLabel" class="offcanvas offcanvas-end header-notification-canvas" id="notificationcanvasRight" tabindex="-1">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="notificationcanvasRightLabel">Notifications</h5>
                                <button aria-label="Close" class="btn-close" data-bs-dismiss="offcanvas" type="button"></button>
                            </div>
                            <div class="offcanvas-body app-scroll p-0">
                                <div class="head-container">
                                    <div class="notification-message head-box">
                                        <div class="message-content-box flex-grow-1 pe-2">
                                            <a class="f-s-15 text-dark mb-0" href="#"><span class="f-w-500 text-primary">Moussa Diop</span> a payé sa cotisation pour la tontine <span class="f-w-500">"Projet Voiture"</span>.</a>
                                        </div>
                                        <div class="text-end"><span class="badge text-light-primary"> 5 min</span></div>
                                    </div>
                                    <div class="notification-message head-box">
                                        <div class="message-content-box flex-grow-1 pe-2">
                                            <a class="f-s-15 text-dark mb-0" href="#">Rappel : Votre tour de recevoir la caisse de la tontine <span class="f-w-500">"Tontine des Commerçantes"</span> est la semaine prochaine.</a>
                                        </div>
                                        <div class="text-end"><span class="badge text-light-primary"> 2 heures</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>