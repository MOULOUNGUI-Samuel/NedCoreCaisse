<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyMiddleware
{
    /**
     * Gère une requête entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Définir le nom de l'en-tête (header) que nous attendons
        $apiKeyHeaderName = 'X-API-KEY';

        // 2. Récupérer la clé envoyée dans la requête
        $apiKeyFromRequest = $request->header($apiKeyHeaderName);

        // 3. Récupérer la clé valide depuis notre configuration sécurisée (.env -> config/app.php)
        $validApiKey = config('app.api_key');

        // 4. Vérifier que la clé valide est bien configurée dans notre application
        if (!$validApiKey) {
            // Erreur pour le développeur : la clé n'est pas configurée côté serveur
            return response()->json(['message' => 'Erreur de configuration serveur.'], 500);
        }

        // 5. Comparer la clé de la requête avec la clé valide
        //    Utiliser hash_equals est une bonne pratique pour éviter les "timing attacks"
        if (!hash_equals($validApiKey, (string) $apiKeyFromRequest)) {
            // Si les clés ne correspondent pas, on rejette la requête avec une erreur 401
            return response()->json(['message' => 'Unauthorized. Clé d\'API invalide ou manquante.'], 401);
        }

        // 6. Si tout est correct, on laisse la requête continuer son chemin
        return $next($request);
    }
}