<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Assurez-vous d'importer le modèle User
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Carbon\Carbon; // Pour manipuler les dates

class CaisseController extends Controller
{
    //
     /**
     * Affiche la liste des caisses et les mouvements de la première caisse.
     */
    public function index()
    {
        $user = Auth::user();
        $caisses = getListeCaissesAvecSoapClient($user->code_entreprise, $user->username);

        // Gérer le cas d'une erreur API ou si aucune caisse n'est trouvée
        if (isset($caisses['Erreur SOAP']) || empty($caisses)) {
            return view('components.caisses.liste_caisse', ['caisses' => []]);
        }
        
        $date_debut = Carbon::now()->startOfMonth()->format('Ymd');
        $date_fin = Carbon::now()->endOfMonth()->format('Ymd');

        // *** NOUVELLE LOGIQUE : Enrichir chaque caisse avec ses propres stats ***
        $caisses = array_map(function ($caisse) use ($date_debut, $date_fin) {
            
            // 1. Récupérer les mouvements pour CETTE caisse
            $mouvements = getMouvementsCaisse($caisse->idcaisse, $date_debut, $date_fin);
            
            // 2. Calculer les totaux pour CETTE caisse
            $collectionMouvements = collect($mouvements);
            $caisse->totalVersements = $collectionMouvements->sum('montant_credit');
            $caisse->totalRetraits = $collectionMouvements->sum('montant_debit');

            // 3. Calculer les pourcentages pour la barre de progression
            $totalMouvements = $caisse->totalVersements + $caisse->totalRetraits;
            $caisse->pourcentVersements = ($totalMouvements > 0) ? ($caisse->totalVersements * 100) / $totalMouvements : 0;
            $caisse->pourcentRetraits = ($totalMouvements > 0) ? ($caisse->totalRetraits * 100) / $totalMouvements : 0;
            
            return $caisse;

        }, $caisses);

        // Pour l'affichage initial, on charge les mouvements de la première caisse
        $activeCaisse = Arr::first($caisses);
        $initialMouvements = getMouvementsCaisse($activeCaisse->idcaisse, $date_debut, $date_fin);

        return view('components.caisses.liste_caisse', [
            'caisses' => $caisses,
            'activeCaisse' => $activeCaisse,
            'mouvements' => $initialMouvements,
        ]);
    }
    
    /**
     * Retourne le HTML de la table des mouvements pour une caisse donnée (pour AJAX).
     */
    public function getMouvementsHtml(Request $request, $id_caisse)
    {
        $user = Auth::user();
        
        // On récupère les informations de la caisse demandée (juste pour le titre)
        $allCaisses = getListeCaissesAvecSoapClient($user->code_entreprise, $user->username);
        $activeCaisse = collect($allCaisses)->firstWhere('idcaisse', $id_caisse);

        // On récupère les mouvements
        $date_debut = Carbon::now()->startOfMonth()->format('Ymd');
        $date_fin = Carbon::now()->endOfMonth()->format('Ymd');
        $mouvements = getMouvementsCaisse($id_caisse, $date_debut, $date_fin);

        // On retourne la vue partielle, qui ne contient que le tableau HTML
        return view('components.caisses._mouvements_table', [
            'activeCaisse' => $activeCaisse,
            'mouvements' => $mouvements
        ]);
    }

    // public function __construct()
    // {
    //     $cleApiFournie = request()->header('X-API-KEY');
    //     $cleApiValide = env('Nedcore_API_KEY');
    //     if (!$cleApiFournie || $cleApiFournie !== $cleApiValide) {
    //         abort(401, 'Accès non autorisé.');
    //     }
    // }

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
}
