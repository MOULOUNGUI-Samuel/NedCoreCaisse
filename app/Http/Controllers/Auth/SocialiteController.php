<?php
// app/Http/Controllers/Auth/SocialiteController.php
// app/Http/Controllers/Auth/SocialiteController.php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirige l'utilisateur vers la page d'authentification du fournisseur.
     */
    public function redirect(string $provider)
    {
        // ... (votre code de redirection existant, il est correct)
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Récupère les informations de l'utilisateur et ferme la pop-up.
     */
    public function callback(string $provider)
    {
        try {
            $providerUser = Socialite::driver($provider)->user();

            $user = User::updateOrCreate(
                [
                    // Condition de recherche
                    $provider . '_id' => $providerUser->id,
                ],
                [
                    // Données à créer ou à mettre à jour
                    'name' => $providerUser->name,
                    'email' => $providerUser->email,
                    'password' => Hash::make(uniqid())
                ]
            );

            // On connecte l'utilisateur DANS LA SESSION ACTUELLE
            Auth::login($user);
            
            // =======================================================
            //                  LA CORRECTION EST ICI
            // =======================================================
            // Au lieu de rediriger, on renvoie la vue qui contient le script window.close()
            return view('auth.login', [
                'message' => 'Authentification réussie. La fenêtre va se fermer.',
            ]);

        } catch (\Exception $e) {
            // En cas d'erreur, on peut aussi fermer la fenêtre
            // et la page principale gérera l'échec (elle rechargera et restera sur 'login')
            report($e); // Log l'erreur pour le débogage
            return view('auth.login'); 
        }
    }
}