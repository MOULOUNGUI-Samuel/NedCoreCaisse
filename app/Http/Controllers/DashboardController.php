<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Mouvement;
use App\Models\Caisse;

class DashboardController extends Controller
{
    public function index() 
{
    $user = Auth::user();
    $today = Carbon::today();
    $yesterday = Carbon::yesterday();

    // ➤ Raccourcis pour les requêtes
    $sum = fn($column, $date) =>
        Mouvement::where('operateur_id', $user->id)
            ->whereDate('date_mouvement', $date)
            ->sum($column);

    $count = fn($date) =>
        Mouvement::where('operateur_id', $user->id)
            ->whereDate('date_mouvement', $date)
            ->count();

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
        ->where('operateur_id', $user->id)
        ->whereDate('date_mouvement', $today)
        ->latest()
        ->take(10)
        ->get();

    // ✅ Liste des caisses de l'utilisateur
    $caisses = Caisse::where('societe_id', $user->societe_id)->get();

    return view('components.content_application.dashboard', compact(
        'encToday', 'encPercent',
        'decToday', 'decPercent',
        'soldeToday', 'soldePercent',
        'opsToday', 'opsPercent',
        'transactions',
        'caisses'
    ));
}

}