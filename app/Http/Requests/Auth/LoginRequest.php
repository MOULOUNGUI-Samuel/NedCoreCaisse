<?php

namespace App\Http\Requests\Auth;

use App\Models\Societe;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use SoapClient;
use SoapFault;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'code_entreprise' => ['required', 'string'], // Accepts either email or username
            'email' => ['required', 'string'], // Accepts either email or username
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $identifiant = $this->input('email');
        $code_entreprise = $this->input('code_entreprise');
        $password = $this->input('password');

        // Vérification de l'existence des champs requis
        if (empty($identifiant)) {
            throw ValidationException::withMessages([
                'email' => 'Le champ identifiant est requis. Veuillez fournir votre email, numéro de téléphone ou nom d’utilisateur.',
            ]);
        }

        if (empty($password)) {
            throw ValidationException::withMessages([
                'password' => 'Le champ mot de passe est requis. Veuillez fournir votre mot de passe.',
            ]);
        }
        if (empty($code_entreprise)) {
            throw ValidationException::withMessages([
                'code_entreprise' => 'Le champ code entreprise est requis. Veuillez fournir le code de votre entreprise.',
            ]);
        }


        // On cherche l'utilisateur dans la base de données locale
        $user = User::where('identifiant', $identifiant)
            ->where('code_entreprise', $code_entreprise)
            ->first();

        if (! $user || ! Hash::check($password, $user->password)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'identifiant' => 'Informations de connexion incorrectes. Merci de vérifier vos informations',
            ]);
            throw ValidationException::withMessages([
                'password' => 'Informations de connexion incorrectes. Merci de vérifier vos informations',
            ]);
        }
        session()->put('societe_nom', $user->societe->nom_societe);
        session()->put('societe_logo', $user->societe->logo);
        session()->put('societe_id', $user->societe->id);
        // Si l'authentification externe réussit, on connecte l'utilisateur local.
        Auth::login($user, $this->boolean('remember'));

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Vérifie les identifiants de l'utilisateur via le service SOAP externe.
     *
     * @param string $codesociete
     * @param string $login
     * @param string $mdp
     * @return bool
     */
    private function verifierLogin(string $codesociete, string $login, string $mdp): bool
    {
        try {
            // L'URL du service WSDL
            $serviceUrl = "http://45.155.249.99/WS_YODIGEST_ERP_WEB/awws/WS_YODIGEST_ERP.awws?wsdl";

            // Options pour le client SOAP, notamment pour éviter la mise en cache du WSDL en production
            $options = [
                'cache_wsdl' => WSDL_CACHE_NONE,
                'trace' => 1, // Permet le débogage en cas d'erreur
            ];

            $service = new SoapClient($serviceUrl, $options);

            // Appel de la méthode du service web avec les 3 arguments
            $resultat = $service->Mobile_verifie_login($codesociete, $login, $mdp);

            // Le service retourne `true` en cas de succès
            return $resultat === true;
        } catch (SoapFault $e) {
            // En cas d'erreur avec le service SOAP (ex: service indisponible),
            // on peut logger l'erreur et on retourne false pour sécuriser l'accès.
            // Log::error('Erreur SOAP lors de la connexion: ' . $e->getMessage());
            return false;
        }
    }
    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')) . '|' . $this->ip());
    }
}
