<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $code_societe = $user->code_entreprise;
        $username = $user->username;
        
        // 1. Définir la date du jour pour les appels API
        $today = Carbon::today()->format('Ymd');

        // 2. Récupérer la liste de toutes les caisses
        $caisses = getListeCaissesAvecSoapClient($code_societe, $username);

        // Gérer une éventuelle erreur de l'API des caisses
        if (isset($caisses['Erreur SOAP'])) {
            return view('dashboard', ['error' => 'Impossible de charger les données des caisses.']);
        }
        if (empty($caisses)) {
            return view('dashboard', ['caisses' => []]);
        }

        // 3. Initialiser les compteurs et une collection pour tous les mouvements du jour
        $allMouvements = collect();
        
        // 4. Boucler sur chaque caisse pour récupérer ses mouvements du jour
        foreach ($caisses as $caisse) {
            $mouvementsDuJour = getMouvementsCaisse($caisse->idcaisse, $today, $today);
            if (!isset($mouvementsDuJour['Erreur SOAP'])) {
                $allMouvements = $allMouvements->merge($mouvementsDuJour);
            }
        }

        // 5. Calculer les totaux à partir de tous les mouvements collectés
        $totalEncaissements = $allMouvements->sum('montant_credit');
        $totalDecaissements = $allMouvements->sum('montant_debit');
        $soldeNet = $totalEncaissements - $totalDecaissements;
        $totalOperationsValue = $totalEncaissements + $totalDecaissements; // Valeur totale des opérations

        // 6. Préparer le paquet de données pour la vue
        $data = [
            'totalEncaissements' => $totalEncaissements,
            'totalDecaissements' => $totalDecaissements,
            'soldeNet' => $soldeNet,
            'totalOperationsValue' => $totalOperationsValue,
            'allMouvements' => $allMouvements->sortByDesc('datemvtcaisse'), // Trier par date pour l'affichage
            'caisses' => $caisses,
        ];
        
        return view('dashboard', $data);
    }
}