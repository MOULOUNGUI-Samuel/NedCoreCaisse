<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User; // Assurez-vous d'importer le modèle User

class UserController extends Controller
{
    public function __construct()
    {
        $cleApiFournie = request()->header('X-API-KEY');
        $cleApiValide = env('Nedcore_API_KEY');
        if (!$cleApiFournie || $cleApiFournie !== $cleApiValide) {
            abort(401, 'Accès non autorisé.');
        }
    }

    /**
     * API n°1: Lister les inscriptions.
     * La réponse ne contient que les données, pas de token CSRF.
     */
    public function UserInfo()
    {
        $utilisateurs = User::select('id', 'code_entreprise', 'matricule', 'nom', 'prenom', 'email', 'role', 'created_at', 'updated_at')
            ->latest()
            ->paginate(25);

        return response()->json($utilisateurs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
