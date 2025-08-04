<?php

namespace App\Helpers;

use App\Models\ApprobaCaisse;
use App\Models\ApprobaCaution;
use App\Models\ApprobaEvenement;
use App\Models\RegimeAssurance;
use App\Models\Pays;
use App\Models\Ville;
use App\Models\Beneficiaire;
use App\Models\Entreprise;
use App\Models\LienDoc;
use App\Models\Module;
use App\Models\Permission;
use App\Models\PermissionUser;
use App\Models\Societe;
use Carbon\Carbon;
use DateTime;
use Exception;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Illuminate\Support\Facades\Auth;
use App\Models\CategorieMotif;
use App\Models\MotifStandard;

class DateHelper
{
    private static function extraireCodeTemps($datetime)
    {
        // Extract day, hour, minute, and second as a unique code
        return $datetime->format('dHis');
    }
    public static function convertirDateFormat($date)
    {
        // Convertir la date du format 'Y-m-d' en objet DateTime
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);

        if ($dateObj) {
            // Reformater la date en 'd/m/Y'
            return $dateObj->format('d/m/Y');
        } else {
            // Si la date n'est pas valide, retourner null ou un message d'erreur
            return null; // Ou 'Format de date invalide'
        }
    }
    public static function dossier_info()
    {
        $societes = Societe::where('statut', 1)->get();
        $societe_id = session('societe_id');
        $categorieMotifs = CategorieMotif::where('est_actif', true)
            ->where('societe_id', $societe_id)
            ->get();

        $categorieLibelles = [];
        foreach ($categorieMotifs as $categorie) {
            $categorieLibelles[] = [
                'categorieMotif' => $categorie,
                'libelle' => MotifStandard::where('est_actif', true)->get()
            ];
        }
        return [
            'societes' => $societes,
            'categorieLibelles' => $categorieLibelles,
        ];
    }
    public static function convertirDateEnTexte($date)
    {
        // Convertir la date du format 'd/m/Y' en objet DateTime
        $dateObj = DateTime::createFromFormat('d/m/Y', $date);

        if ($dateObj) {
            // Traduction manuelle des jours et des mois
            $jours = ['Sunday' => 'dimanche', 'Monday' => 'lundi', 'Tuesday' => 'mardi', 'Wednesday' => 'mercredi', 'Thursday' => 'jeudi', 'Friday' => 'vendredi', 'Saturday' => 'samedi'];
            $mois = ['January' => 'janvier', 'February' => 'février', 'March' => 'mars', 'April' => 'avril', 'May' => 'mai', 'June' => 'juin', 'July' => 'juillet', 'August' => 'août', 'September' => 'septembre', 'October' => 'octobre', 'November' => 'novembre', 'December' => 'décembre'];

            // Format de la date en anglais
            $jourEn = $dateObj->format('l');
            $moisEn = $dateObj->format('F');
            $jour = $jours[$jourEn] ?? $jourEn;
            $mois = $mois[$moisEn] ?? $moisEn;

            // Formatter la date en 'lundi 12 février 2024'
            return $jour . ' ' . $dateObj->format('d') . ' ' . $mois . ' ' . $dateObj->format('Y');
        } else {
            // Si la date n'est pas valide, retourner null ou un message d'erreur
            return null;
        }
    }
    public static function formatNumber($number)
    {
        return number_format($number, 0, '.', ' '); // Formate le nombre avec des espaces comme séparateurs de milliers
    }
}
