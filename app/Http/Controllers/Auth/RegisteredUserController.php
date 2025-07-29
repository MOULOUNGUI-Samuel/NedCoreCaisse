<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
     public function store(Request $request) // <-- On retire le typage ': RedirectResponse'
    {
        // --- ÉTAPE 1: VALIDATION COMMUNE ---
        $baseRules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255'], // On garde la validation de base ici
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ];
        $request->validate($baseRules);

        $loginInput = $request->input('email');
        $loginField = '';

        // --- ÉTAPE 2: DÉTECTION DU TYPE DE LOGIN ET VALIDATION SPÉCIFIQUE ---
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $loginField = 'email';
            $request->validate(['email' => ['unique:users,email']]);
        } elseif (preg_match('/^[0-9\+\-\(\) ]+$/', $loginInput)) {
            $loginField = 'phone_number';
            // Important : On valide 'email' car c'est le nom du champ dans la requête
            $request->validate(['email' => ['unique:users,phone_number']]);
        } else {
            $loginField = 'username';
            $request->validate(['email' => ['unique:users,username']]);
        }

        // --- ÉTAPE 3: CRÉATION DE L'UTILISATEUR ---
        $user = User::create([
            'name' => $request->name,
            $loginField => $loginInput, // On utilise la variable qui contient la valeur
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // --- ÉTAPE 4: GESTION DE LA RÉPONSE (WEB vs API) ---

        // Si la requête demande une réponse JSON (cas de l'API)
        if ($request->wantsJson()) {
            // Créer un token Sanctum pour le nouvel utilisateur
            $token = $user->createToken('registration-token')->plainTextToken;

            // Renvoyer une réponse JSON avec le token
            return response()->json([
                'message' => 'Utilisateur créé et connecté avec succès.',
                'user' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ], 201); // 201 Created
        }

        // Comportement par défaut pour une requête WEB
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
