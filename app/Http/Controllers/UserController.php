<?php

namespace App\Http\Controllers;

use App\Models\Societe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('societes')->get();
        $societesParUtilisateur = [];

        foreach ($users as $user) {
            $societesNonAssociees = Societe::whereDoesntHave('user', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })->get();

            // On stocke les sociétés manquantes par user ID
            $societesParUtilisateur[$user->id] = $societesNonAssociees;
        }

        return view('components.super_admin.users', compact('users', 'societesParUtilisateur'));
    }

    public function associer(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'societe_id' => 'required|exists:societes,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $societeId = $request->societe_id;

        // Vérifie si déjà lié
        $existe = $user->societes()->where('societe_id', $societeId)->exists();

        if ($existe) {
            return back()->with('error', 'Cet utilisateur est déjà associé à cette société.');
        }

        // Associer via la table pivot avec les données
        $user->societes()->attach($societeId, [
            'id' => (string) Str::uuid(),
            'user_id' => $user->id,
            'societe_id' => $societeId,
            'role' => 'Employé',
            'est_actif' => true,
            'associe_le' => now(),
        ]);

        return back()->with('success', 'Utilisateur associé avec succès à la société.');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
