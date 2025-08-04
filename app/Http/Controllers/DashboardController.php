<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Mouvement;
use App\Models\Caisse;
use App\Models\Societe;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();
        $societe_id = session('societe_id');
        // ➤ Raccourcis pour les requêtes
        if ($user->role == 'Admin') {

            $sum = fn($column, $date) =>
            Mouvement::whereHas('caisse', fn($q) => $q->where('societe_id', $societe_id))
                ->where('caisse_id', $user->caisse_id)
                ->whereDate('date_mouvement', $date)
                ->sum($column);

            $count = fn($date) =>
            Mouvement::whereHas('caisse', fn($q) => $q->where('societe_id', $societe_id))
                ->where('caisse_id', $user->caisse_id)
                ->whereDate('date_mouvement', $date)
                ->count();
        } else {
            $sum = fn($column, $date) =>
            Mouvement::whereHas('caisse', fn($q) => $q->where('societe_id', $societe_id))
            ->where('operateur_id', $user->id)
                ->whereDate('date_mouvement', $date)
                ->sum($column);

            $count = fn($date) =>
            Mouvement::whereHas('caisse', fn($q) => $q->where('societe_id', $societe_id))
            ->where('operateur_id', $user->id)
                ->whereDate('date_mouvement', $date)
                ->count();
        }

        // ✅ Encaissements
        $encToday = $sum('montant_credit', $today);
        $encYesterday = $sum('montant_credit', $yesterday);
        $encPercent = $encYesterday > 0 ? round((($encToday - $encYesterday) / $encYesterday) * 100, 1) : 100;

        // ✅ Décaissements
        $decToday = $sum('montant_debit', $today);
        $decYesterday = $sum('montant_debit', $yesterday);
        $decPercent = $decYesterday > 0 ? round((($decToday - $decYesterday) / $decYesterday) * 100, 1) : 100;

        // ✅ Solde net
        $soldeToday = $encToday - $decToday;
        $soldeYesterday = $encYesterday - $decYesterday;
        $soldePercent = $soldeYesterday != 0 ? round((($soldeToday - $soldeYesterday) / abs($soldeYesterday)) * 100, 1) : 100;

        // ✅ Nombre d’opérations
        $opsToday = $count($today);
        $opsYesterday = $count($yesterday);
        $opsPercent = $opsYesterday > 0 ? round((($opsToday - $opsYesterday) / $opsYesterday) * 100, 1) : 100;

        // ✅ Transactions du jour (les dernières 10)
        $transactions = Mouvement::with(['operateur', 'motifStandard'])
        ->whereHas('caisse', fn($q) => $q->where('societe_id', $societe_id))
            ->where('operateur_id', $user->id)
            ->whereDate('date_mouvement', $today)
            ->latest()
            ->take(10)
            ->get();

        // ✅ Liste des caisses de l'utilisateur
        $caisses = Caisse::where('societe_id', $user->societe_id)->get();

        return view('components.content_application.dashboard', compact(
            'encToday',
            'encPercent',
            'decToday',
            'decPercent',
            'soldeToday',
            'soldePercent',
            'opsToday',
            'opsPercent',
            'transactions',
            'caisses'
        ));
    }

    public function change_societe($societe_id)
    {
        // Vérifier si la société existe
        $societe = Societe::find($societe_id);
        if (!$societe) {
            return redirect()->back()->withErrors(['message' => 'Société non trouvée']);
        }
        $currentRouteName = url()->previous();
        // Convertir l'URL en requête
        $request = Request::create($currentRouteName, 'GET');

        // Trouver la route correspondante
        $route = Route::getRoutes()->match($request);

        // Retourner le nom de la route
        $routeName = $route->getName();

        // Mettre à jour les sessions avec les informations de la société sélectionnée
        session()->forget(['societe_nom', 'societe_logo', 'societe_id']);
        session()->put('societe_nom', $societe->nom_societe);
        session()->put('societe_logo', $societe->logo);
        session()->put('societe_id', $societe->id);

        if($routeName == 'operations') {
            return redirect()->route('caisse.index');
        }else{
            return redirect()->route($routeName);
        }
    }
}
