<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Societe;
use App\Models\SocieteUser;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function authenticate($id)
    {

        // $response = Http::withHeaders([
        //     'X-API-KEY' => '3e10a57a18cc9fc669babbd9adc21b7bdf2b970effe7dce38b8e040e1d08824b',
        //     'accept' => 'application/json',
        // ])->get('https://nedcore.net/api/users/051a1a80-6e6e-4b99-abff-308faf6781e4');

        $response = Http::withHeaders([
            'X-API-KEY' => '3e10a57a18cc9fc669babbd9adc21b7bdf2b970effe7dce38b8e040e1d08824b',
            'accept' => 'application/json',
        ])->get('https://nedcore.net/api/users/' . $id);

        if ($response->successful()) {
            $data = $response->json();
            $entreprise = $data['entreprise'];
            $userData = $data['user'];

            $logoPath = null;

            if (!empty($entreprise['logo'])) {
                $responseLogo = Http::get($entreprise['logo']);

                if ($responseLogo->successful()) {
                    $extension = pathinfo(parse_url($entreprise['logo'], PHP_URL_PATH), PATHINFO_EXTENSION);
                    $fileName = 'logos/' . uniqid() . '.' . ($extension ?: 'jpg');

                    Storage::disk('public')->put($fileName, $responseLogo->body());
                    $logoPath = $fileName;
                }
            }
            $photoPath = null;

            if (!empty($userData['photo'])) {
                $responsePhoto = Http::get($userData['photo']);

                if ($responsePhoto->successful()) {
                    $extension = pathinfo(parse_url($userData['photo'], PHP_URL_PATH), PATHINFO_EXTENSION);
                    $fileName = 'photos/' . uniqid() . '.' . ($extension ?: 'jpg');

                    Storage::disk('public')->put($fileName, $responsePhoto->body());
                    $photoPath = $fileName;
                }
            }
            // ✅ 1️⃣ Mettre à jour ou créer la société
            $societe = Societe::updateOrCreate(
                ['code_societe' => $entreprise['code_societe']],
                [
                    'nom_societe' => $entreprise['nom_societe'],
                    'logo' => $logoPath,
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
                    'photo' => $photoPath,
                    'role' => 'Employé',
                    'username' => $userData['username'],
                    'email' => $userData['email'],
                    'identifiant' => $userData['identifiant'],
                    'google_id' => $userData['google_id'] ?? null,
                    'facebook_id' => $userData['facebook_id'] ?? null,
                    'password' => $userData['password'], // ✅ Réécraser le mot de passe à chaque connexion
                ]
            );

            // Associer via la table pivot avec les données
            $data = [
                'user_id' => $user->id,
                'societe_id' => $societe->id,
            ];

            $updateData = [
                'role' => $request->role ?? 'Employé',
                'est_actif' => true,
                'associe_le' => now(),
            ];

            // Vérifie si une ligne existe déjà, et met à jour ou crée
            SocieteUser::updateOrCreate($data, array_merge($updateData, [
                'id' => Str::uuid(), // Ne sera utilisé que si create()
            ]));

            
            session()->put('societe_nom', $societe->nom_societe);
            session()->put('societe_logo', $societe->logo);
            session()->put('societe_id', $societe->id);
            // ✅ 3️⃣ Authentifier l'utilisateur
            Auth::login($user);

            return redirect()->route('dashboard');
        } else {
            return response()->json(['error' => 'Erreur lors de la récupération des données'], 500);
        }
    }
}
