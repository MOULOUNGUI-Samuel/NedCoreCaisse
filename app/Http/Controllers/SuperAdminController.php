<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CategorieMotif;
use App\Models\MotifStandard;

class SuperAdminController extends Controller
{
    //
    public function copierCategoriesEtLibelles(Request $request)
    {
        $fromEntrepriseId = session('societe_id');

        $toEntrepriseId = $request->input('second_societe_id');
        try {
            // Récupère toutes les catégories de l’entreprise source
            $categories = CategorieMotif::where('societe_id', $fromEntrepriseId)
                ->where('id', $request->input('categorieLibelle_id'))
                ->get();

            if ($categories->isEmpty()) {
                return redirect()->back()->with('error', 'Aucune catégorie à copier.');
            }
            foreach ($categories as $categorie) {
                // Duplique la catégorie
                $nouvelleCategorie = $categorie->replicate();
                $nouvelleCategorie->societe_id = $toEntrepriseId;
                $nouvelleCategorie->save();
                $MotifStandards = MotifStandard::where('categorie_motif_id', $categorie->id)->get();
                // Duplique chaque libellé associé
                foreach ($MotifStandards as $libelle) {
                    $nouveauLibelle = $libelle->replicate();
                    $nouveauLibelle->categorie_motif_id = $nouvelleCategorie->id;
                    $nouveauLibelle->save();
                }
            }

            return redirect()->back()->with('success', 'Catégories et libellés copiés avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
}
