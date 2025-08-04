<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Assurez-vous d'importer le modèle User
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Arr;
use Carbon\Carbon; // Pour manipuler les dates
use App\Models\Caisse; // Assurez-vous d'importer le modèle Caisse
use App\Models\CategorieMotif;
use Illuminate\Support\Facades\Validator;
use App\Models\MotifStandard; // Assurez-vous d'importer le modèle MotifStandard
use App\Models\Mouvement;

class CaisseController extends Controller
{
    //
    /**
     * Affiche la liste des caisses et les mouvements de la première caisse.
     */
    public function indexExterne()
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

        return view('components.caisses_externe.liste_caisse', [
            'caisses' => $caisses,
            'activeCaisse' => $activeCaisse,
            'mouvements' => $initialMouvements,
        ]);
    }
    public function index()
    {
        $societe_id = session('societe_id');

        $users = User::where('societe_id', $societe_id)->get();

        if (Auth::user()->role == 'Admin') {
            $caisses = Caisse::with('user')
                ->where('societe_id', $societe_id)
                ->where('est_supprime', false)
                ->get()
                ->map(function ($caisse) {

                    // 🔹 Compter le nombre de versements et retraits
                    $versements = $caisse->mouvements()
                        ->where('montant_credit', '>', 0)
                        ->where('est_annule', 0)
                        ->sum('montant_credit');

                    $retraits   = $caisse->mouvements()
                        ->where('montant_debit', '>', 0)
                        ->where('est_annule', 0)
                        ->sum('montant_debit');

                    $caisse->versements = $versements;
                    $caisse->retraits   = $retraits;

                    // 🔹 Calculer les pourcentages de la barre de progression
                    $totalOps = $versements + $retraits;

                    $caisse->pourcentVersements = $totalOps > 0 ? ($versements * 100) / $totalOps : 0;
                    $caisse->pourcentRetraits   = $totalOps > 0 ? ($retraits * 100) / $totalOps : 0;

                    return $caisse;
                });
        } else {
            $caisses = Caisse::with('user')
                ->where('societe_id', $societe_id)
                ->where('user_id', Auth::id())
                ->where('est_supprime', false)
                ->get()
                ->map(function ($caisse) {

                    // 🔹 Compter le nombre de versements et retraits
                    $versements = $caisse->mouvements()
                        ->where('montant_credit', '>', 0)
                        ->where('est_annule', 0)
                        ->sum('montant_credit');

                    $retraits   = $caisse->mouvements()
                        ->where('montant_debit', '>', 0)
                        ->where('est_annule', 0)
                        ->sum('montant_debit');

                    $caisse->versements = $versements;
                    $caisse->retraits   = $retraits;

                    // 🔹 Calculer les pourcentages de la barre de progression
                    $totalOps = $versements + $retraits;

                    $caisse->pourcentVersements = $totalOps > 0 ? ($versements * 100) / $totalOps : 0;
                    $caisse->pourcentRetraits   = $totalOps > 0 ? ($retraits * 100) / $totalOps : 0;

                    return $caisse;
                });
        }


        // ✅ Sélectionner la première caisse par défaut
        $activeCaisse = $caisses->first();

        // ✅ Charger les mouvements de la première caisse
        $mouvements = $activeCaisse
            ? Mouvement::with(['operateur', 'motifStandard', 'annulateur'])
            ->where('caisse_id', $activeCaisse->id)
            ->orderByDesc('date_mouvement')
            ->take(10)
            ->get()
            : collect();


            // Récupération des catégories actives
        $categorieMotifs = CategorieMotif::where('est_actif', true)
            ->where('societe_id', $societe_id)
            ->get();

        $libelles = [];
        foreach ($categorieMotifs as $categorie) {
            $libelles[] = [
                'categorieMotif' => $categorie,
                'libelle' => MotifStandard::where('est_actif', true)->get()
            ];
        }

        return view('components.content_application.liste_caisse', compact(
            'users',
            'caisses',
            'categorieMotifs',
            'activeCaisse',
            'mouvements',
            'libelles'
        ));
    }



    public function operations($id)
    {
        $societe_id = session('societe_id');

        if (Auth::user()->role == 'Admin') {
            $caisses = Caisse::with('user')
                ->where('societe_id', $societe_id)
                ->where('est_supprime', false)
                ->get();
        } else {
            $caisses = Caisse::with('user')
                ->where('societe_id', $societe_id)
                ->where('user_id', Auth::id())
                ->where('est_supprime', false)
                ->get();
        }
        $users = User::where('societe_id', $societe_id)
            ->get();
        $caisse = Caisse::with('user')->where('id', $id)
            ->first();
        $autreCaisses = Caisse::with('user')
            ->where('id', '!=', $id)
            ->get();
        $categorieMotifsEntrer = CategorieMotif::with('motifsStandards')
            ->where('societe_id', $societe_id)
            ->where('type_operation', 'Entrée')
            ->get();
        $categorieMotifsSorties = CategorieMotif::with('motifsStandards')
            ->where('societe_id', $societe_id)
            ->where('type_operation', 'Sortie')
            ->get();

        $encaissementsJour = Mouvement::whereDate('date_mouvement', today())
            ->where('caisse_id', $id)
            ->sum('montant_credit');

        $decaissementsJour = Mouvement::whereDate('date_mouvement', today())
            ->where('caisse_id', $id)
            ->sum('montant_debit');

        $operationsPassees = Mouvement::where('caisse_id', $id)->count();
        $operationsAnnulees = Mouvement::where('est_annule', true)
            ->where('caisse_id', $id)
            ->count();

        $mouvementsRecents = Mouvement::with(['operateur', 'motifStandard'])
            ->where('caisse_id', $id)
            ->orderBy('date_mouvement', 'desc')
            ->get();

        return view(
            'components.content_application.create_operations',
            compact(
                'users',
                'caisse',
                'categorieMotifsEntrer',
                'categorieMotifsSorties',
                'autreCaisses',
                'encaissementsJour',
                'decaissementsJour',
                'operationsPassees',
                'operationsAnnulees',
                'mouvementsRecents',
                'caisses'
            )
        );
    }
    public function getMotifs($id)
    {
        $motifs = MotifStandard::where('categorie_motif_id', $id)->get();

        return response()->json($motifs);
    }

    public function store(Request $request)
    {
        $societe_id = session('societe_id');
        $validator = Validator::make(
            $request->all(), // Tu avais oublié de passer les données à valider !
            [
                'libelle_caisse' => 'required|string|max:255',
                'user_id' => 'required|exists:users,id',
                'seuil_maximum' => 'nullable|min:0',
                'description_caisse' => 'nullable|string',
            ],
            [
                'libelle_caisse.required' => 'Le libellé de la caisse est obligatoire.',
                'user_id.required' => 'Le gestionnaire de la caisse est obligatoire.',
                'user_id.exists' => 'Le gestionnaire sélectionné n\'existe pas.',
                'seuil_maximum.min' => 'Le seuil maximum ne peut pas être négatif.',
                'description_caisse.string' => 'La description de la caisse doit être une chaîne de caractères.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // ✅ Récupération des données validées
        $data = $validator->validated();

        Caisse::create([
            'libelle_caisse' => $data['libelle_caisse'],
            'user_id' => $data['user_id'],
            'societe_id' => $societe_id,
            'seuil_maximum' => $request->input('limiter_solde') === 'oui'
                ? intval(str_replace([' ', "\u{00A0}"], '', htmlspecialchars($data['seuil_maximum'] ))) ?? 0.00
                : 0.00,
            'decouvert_autorise' => 0,
            'est_supprime' => false,
            'description_caisse' => $data['description_caisse'] ?? null,
        ]);

        return redirect()->back()->with('success', 'Caisse créée avec succès.');
    }
    public function update(Request $request, $id)
    {
        $societe_id = session('societe_id');
        // 1️⃣ On récupère la caisse à modifier
        $caisse = Caisse::findOrFail($id);

        // 2️⃣ Validation des données envoyées
        $data = $request->validate([
            'libelle_caisse' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'seuil_maximum' => 'nullable|min:0',
            'description_caisse' => 'nullable|string',
            'limiter_solde' => 'nullable|in:oui,non',
        ], [
            'libelle_caisse.required' => 'Le libellé de la caisse est obligatoire.',
            'user_id.required' => 'Le gestionnaire de la caisse est obligatoire.',
            'seuil_maximum.min' => 'Le seuil maximum ne peut pas être négatif.',
        ]);

        // 3️⃣ Mise à jour de la caisse
        $caisse->update([
            'libelle_caisse' => $data['libelle_caisse'],
            'user_id' => $data['user_id'],
            'societe_id' => $societe_id, // Si ça ne change jamais, tu peux même le laisser tel quel
            'seuil_maximum' => $request->input('limiter_solde') === 'oui'
                ? intval(str_replace([' ', "\u{00A0}"], '', htmlspecialchars($data['seuil_maximum'] ))) ?? 0.00
                : 0.00,
            'description_caisse' => $data['description_caisse'] ?? null,
        ]);

        // 4️⃣ Redirection avec message de succès
        return redirect()->back()->with('success', 'Caisse modifiée avec succès.');
    }



    /**
     * Retourne le HTML de la table des mouvements pour une caisse donnée (pour AJAX).
     */
    public function getMouvementsHtmlExterne(Request $request, $id_caisse)
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
        return view('components.caisses_externe._mouvements_table', [
            'activeCaisse' => $activeCaisse,
            'mouvements' => $mouvements
        ]);
    }
    public function getMouvementsHtml($id_caisse)
    {
        $mouvements = Mouvement::with(['operateur', 'motifStandard'])
            ->where('caisse_id', $id_caisse)
            ->latest()
            ->take(10)
            ->get();

        return view('components.content_application._mouvements_table', compact('mouvements'));
    }


    public function storecategorie(Request $request)
    {
        $societe_id = session('societe_id');
        $validated = $request->validate(
            [
                'nom_categorie' => 'required|string|max:255',
                'type_operation' => 'required|string',
                'motifs.*.libelle_motif' => 'nullable|string|max:255'
            ],
            [
                'nom_categorie.required' => 'Le nom de la catégorie est obligatoire.',
                'type_operation.required' => 'Le type d\'opération est obligatoire.',
                'motifs.*.libelle_motif.required' => 'Le libellé du motif est obligatoire.',
                'motifs.*.libelle_motif.max' => 'Le libellé du motif ne peut pas dépasser 255 caractères.'
            ]
        );

        // 1️⃣ Création de la catégorie
        $categorie = CategorieMotif::create([
            'nom_categorie' => $validated['nom_categorie'],
            'societe_id' => $societe_id,
            'type_operation' => $validated['type_operation'], // Ou autre valeur selon ton besoin
        ]);

        // 2️⃣ Création des motifs liés
        if (isset($validated['motifs'])) {
            foreach ($validated['motifs'] as $motif) {
                $categorie->motifsStandards()->create([
                    'libelle_motif' => $motif['libelle_motif'],
                    'est_special_autre' => false,
                    'est_actif' => true,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Catégorie et motifs créés avec succès !');
    }
}
