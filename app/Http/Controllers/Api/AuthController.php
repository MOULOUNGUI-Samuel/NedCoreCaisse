<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Societe;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function authenticate($id)
    {


        $response = Http::withHeaders([
            'X-API-KEY' => '3e10a57a18cc9fc669babbd9adc21b7bdf2b970effe7dce38b8e040e1d08824b',
            'accept' => 'application/json',
        ])->get('https://nedcore.net/api/users/051a1a80-6e6e-4b99-abff-308faf6781e4');

        if ($response->successful()) {
            $data = $response->json();
            $entreprise = $data['entreprise'];
            $userData = $data['user'];

            // ✅ 1️⃣ Mettre à jour ou créer la société
            $societe = Societe::updateOrCreate(
                ['code_societe' => $entreprise['code_societe']],
                [
                    'nom_societe' => $entreprise['nom_societe'],
                    'logo' => $entreprise['logo'],
                    'email' => $entreprise['email'] ?? null,
                    'telephone' => $entreprise['telephone'] ?? null,
                    'statut' => $entreprise['statut'],
                    'adresse' => $entreprise['adresse'] ?? null,
                ]
            );

            // ✅ 2️⃣ Mettre à jour ou créer l'utilisateur et réécraser le mot de passe à chaque fois
            $user = User::updateOrCreate(
                ['nedcore_user_id' => $userData['id']],
                [
                    'name' => $userData['name'],
                    'nedcore_user_id' => $userData['id'],
                    'societe_id' => $societe->id,
                    'code_entreprise' => $entreprise['code_societe'],
                    'photo' => $userData['photo'],
                    'role' => $userData['role']['nom'] ?? null,
                    'username' => $userData['username'],
                    'email' => $userData['email'],
                    'identifiant' => $userData['identifiant'],
                    'google_id' => $userData['google_id'] ?? null,
                    'facebook_id' => $userData['facebook_id'] ?? null,
                    'password' => Hash::make($userData['password']), // ✅ Réécraser le mot de passe à chaque connexion
                ]
            );
            // ✅ 3️⃣ Authentifier l'utilisateur
            Auth::login($user);

            return redirect()->route('dashboard');
        } else {
            return response()->json(['error' => 'Erreur lors de la récupération des données'], 500);
        }
    }
}
