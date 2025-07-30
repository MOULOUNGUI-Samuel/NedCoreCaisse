<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Création d'un utilisateur de test si nécessaire
        // User::create([
        //     'id' => (string) Str::uuid(), // Utilisation de UUID
        //     'name' => 'Test User',
        //     'nedcore_user_id' => (string) Str::uuid(),
        //     'entreprise_id' => (string) Str::uuid(),
        //     'code_entreprise' => 'CODE',
        //     'username' => 'testuser',
        //     'email' => 'test@example.com',
        //     'identifiant' => 'testuser', // Peut être un email, numéro de téléphone ou nom d'utilisateur
        //     'google_id' => null,
        //     'facebook_id' => null,
        //     'password' => Hash::make('password'), // Mot de passe sécurisé
        // ]);

        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->route('dashboard');
    }
    // public function store(LoginRequest $request)
    // {
    //     // 1. Tenter l'authentification avec les identifiants fournis
    //     $request->authenticate();

    //     // 2. Récupérer l'utilisateur qui vient de s'authentifier
    //     $user = Auth::user();

    //     // --- PARTIE AJOUTÉE POUR GÉRER L'API ---
    //     // On vérifie si la requête demande une réponse JSON (typique d'une API)
    //     if ($request->wantsJson()) {
    //         // Supprimer les anciens tokens de l'utilisateur pour garder une seule session active (bonne pratique)
    //         $user->tokens()->delete();
            
    //         // Créer un nouveau token Sanctum
    //         $token = $user->createToken('nedcore-auth-token')->plainTextToken;

    //         // Renvoyer une réponse JSON avec le token
    //         return response()->json([
    //             'message' => 'Connexion réussie',
    //             'access_token' => $token,
    //             'token_type' => 'Bearer',
    //             'user' => $user // Vous pouvez aussi renvoyer les infos de l'utilisateur
    //         ]);
    //     }
    //     // --- FIN DE LA PARTIE AJOUTÉE ---

    //     // Comportement normal pour une requête web
    //     $request->session()->regenerate();

    //     return redirect()->intended(config('fortify.home'));
    // }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
