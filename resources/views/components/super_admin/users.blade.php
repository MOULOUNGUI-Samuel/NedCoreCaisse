@extends('layouts.app')

@section('title3', 'Gestion des caisses')
@section('title2', 'Caisses')
@section('title', 'Liste des caisses')

@section('content')
    <div class="page-wrapper">

        <!-- Page Content-->
        <div class="page-content">
            <div class="row">
                @foreach ($users as $user)
                    <div class="col-md-4 col-lg-4">
                        <div class="border-dashed border-theme-color rounded">
                            <div class="card bg-light ">
                                <div class="card-body border-dashed-bottom border-theme-color">
                                    {{-- Bouton d'association --}}
                                    <button type="button"
                                    data-bs-toggle="offcanvas"
                                                      data-bs-target="#myOffcanvasUser{{ $user->id }}"
                                                      aria-controls="myOffcanvasUser{{ $user->id }}"
                                        class="btn btn-sm btn-outline-primary px-2 d-inline-flex align-items-center float-end">
                                        <i class="fs-14 me-1 fas fa-link"></i> 
                                        Associer à une société
                                    </button>

                                    {{-- Avatar + Nom --}}
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <img src="{{ $user->photo ? asset('storage/' . $user->photo) : asset('assets/images/user.jpg') }}" alt=""
                                                class="rounded-circle thumb-xxl mx-auto d-inline-block">
                                        </div>
                                        <div class="flex-grow-1 ms-2 text-truncate">
                                            <h5 class="m-0 fw-bold">
                                                {{ Str::limit($user->name , 12, '...') }}</h5>
                                            <p class="text-muted mb-0">{{ Str::limit( $user->username, 12, '...') }}</p>
                                        </div>
                                    </div>

                                    {{-- Infos utilisateur --}}
                                    <div class="row mt-3 align-items-center">
                                        <div class="col-12">
                                            <div class="text-muted mb-2 d-flex align-items-center">
                                                <i class="fs-20 me-2 fas fa-envelope"></i>
                                                <span class="text-body fw-semibold">Email :</span>
                                                <a href="#"
                                                    class="text-primary text-decoration-underline ms-1">{{ $user->email }}</a>
                                            </div>

                                            <div class="text-body d-flex align-items-center">
                                                 <i class="fs-20 me-2 fas fa-building text-muted"></i>
                                                <span class="text-body fw-semibold">Société d'origine:</span>
                                                <span class="ms-1">{{ $user->societe->nom_societe }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div><!--end card-body-->
                        </div><!--end card-->
                    </div><!--end div-->
                    @include('components.super_admin._associer_entreprise',['user'=>$user,'societesParUtilisateur'=>$societesParUtilisateur])
                    @endforeach
            </div> <!--end col-->
        </div><!--end row-->

        @include('layouts.lateralContent')

        <!--end Endbar-->
        <div class="endbar-overlay d-print-none"></div>


        @include('layouts.footer')

        <!--end footer-->
    </div>
    <!-- end page content -->
    </div>


@endsection
