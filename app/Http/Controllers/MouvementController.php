<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Mouvement;
use App\Models\Caisse;
use App\Models\MotifStandard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MouvementController extends Controller
{
    //
    public function store(Request $request)
    {
        // 🔹 1. Validation des données
        // Assurez-vous que les règles de validation correspondent à votre modèle et à vos besoins
        $request->validate(
            [
                'caisse_id' => 'required|exists:caisses,id',
                'motif_standard_id' => 'required|exists:motifs_standards,id',
                'date_mouvement' => 'required|date_format:d/m/Y',
                'montant_operation' => 'required',
                'type_mouvement' => 'required|in:debit,credit', // on précise si c'est une sortie ou entrée
                'mode_reglement' => 'nullable|string|max:100',
                'reference_externe' => 'nullable|string|max:255',
                'observations' => 'nullable|string',
            ],
            [
                'caisse_id.required' => 'La caisse est obligatoire.',
                'motif_standard_id.required' => 'Le motif standard est obligatoire.',
                'date_mouvement.required' => 'La date du mouvement est obligatoire.',
                'montant_operation.required' => 'Le montant de l\'opération est obligatoire.',
                'type_mouvement.required' => 'Le type de mouvement (débit/crédit) est obligatoire.',
            ]
        );

        // 🔹 2. Récupération de la caisse et vérification du solde
        $caisse = Caisse::findOrFail($request->caisse_id);
        $soldeAvant = $caisse->seuil_encaissement; // ⚠️ Assurez-vous que la table `caisses` a bien une colonne `solde`
        $montantOperation = isset($request->montant_operation)
            ? intval(str_replace([' ', "\u{00A0}"], '', htmlspecialchars($request->montant_operation)))
            : null;

        if ($request->type_mouvement === 'debit' && $montantOperation > $soldeAvant) {
            return back()->with('error', 'Le montant est supérieur au solde disponible dans la caisse.');
        }
        // 🔹 3. Calcul du solde après mouvement
        $soldeApres = $request->type_mouvement === 'debit'
            ? $soldeAvant - $montantOperation
            : $soldeAvant + $montantOperation;

        // 🔹 4. Génération d’un numéro unique de mouvement
        $lastMouvement = Mouvement::orderByDesc('created_at')->first();

        if ($lastMouvement) {
            // 🔹 Extraire la partie numérique après le dernier tiret
            $parts = explode('-', $lastMouvement->num_mouvement);
            $lastNumber = intval(end($parts));
        } else {
            $lastNumber = 0;
        }

        $numMouvement = Auth::user()->code_entreprise . '-' . date('Y') . '-' . ($lastNumber + 1);
        // 🔹 5. Création du mouvement
        $mouvement = Mouvement::create([
            'id' => Str::uuid(),
            'num_mouvement' => $numMouvement,
            'caisse_id' => $caisse->id,
            'motif_standard_id' => $request->motif_standard_id,
            'operateur_id' => Auth::id(),

            'date_mouvement' => \DateTime::createFromFormat('d/m/Y', $request->date_mouvement)->modify('+1 hour')->format('Y-m-d H:i:s'),
            'libelle_personnalise' => $request->libelle_personnalise ?? null,

            'montant_debit' => $request->type_mouvement === 'debit' ? $montantOperation : 0,
            'montant_credit' => $request->type_mouvement === 'credit' ? $montantOperation : 0,

            'solde_avant_mouvement' => $soldeAvant,
            'solde_apres_mouvement' => $soldeApres,

            'mode_reglement' => $request->mode_reglement,
            'reference_externe' => $request->reference_externe,
            'observations' => $request->observations,
        ]);

        // 🔹 6. Mise à jour du solde de la caisse
        $caisse->update([
            'seuil_encaissement' => $soldeApres
        ]);

        return redirect()->back()->with('success', 'Opération passée avec succès.');
    }

    public function storeTransfert(Request $request)
    {
        $request->validate(
            [
                'caisse_source_id' => 'required|exists:caisses,id|different:caisse_destination_id',
                'caisse_destination_id' => 'required|exists:caisses,id',
                'date_mouvement' => 'required|date_format:d/m/Y',
                'montant_operation' => 'required',
                'observations' => 'nullable|string'
            ],
            [
                'caisse_source_id.required' => 'La caisse source est obligatoire.',
                'caisse_destination_id.required' => 'La caisse de destination est obligatoire.',
                'caisse_source_id.different' => 'La caisse source et la caisse de destination doivent être différentes.',
                'montant_operation.required' => 'Le montant du transfert est obligatoire.',
                'date_mouvement.required' => 'La date du mouvement est obligatoire.',
            ]
        );

        // ✅ Formatage du montant
        $montantOperation = intval(str_replace([' ', "\u{00A0}"], '', $request->montant_operation));

        DB::transaction(function () use ($request, $montantOperation) {

            // 🔹 1. Récupérer les caisses
            $caisseSource = Caisse::findOrFail($request->caisse_source_id);
            $caisseDest   = Caisse::findOrFail($request->caisse_destination_id);

            // 🔹 2. Vérifier le solde
            if ($montantOperation > $caisseSource->seuil_encaissement) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'montant_operation' => 'Le montant dépasse le solde disponible dans la caisse source.'
                ]);
            }

            // 🔹 3. Générer un numéro unique de transfert
            $lastMouvement = Mouvement::orderByDesc('created_at')->first();

            if ($lastMouvement) {
                // 🔹 Extraire la partie numérique après le dernier tiret
                $parts = explode('-', $lastMouvement->num_mouvement);
                $lastNumber = intval(end($parts));
            } else {
                $lastNumber = 0;
            }

            $numMouvement = Auth::user()->code_entreprise . '-' . date('Y') . '-' . ($lastNumber + 1);

            // 🔹 4. Mouvement DEBIT (caisse source)
            Mouvement::create([
                'id' => Str::uuid(),
                'num_mouvement' => $numMouvement,
                'caisse_id' => $caisseSource->id,
                'operateur_id' => Auth::id(),

                'date_mouvement' => \DateTime::createFromFormat('d/m/Y', $request->date_mouvement)->modify('+1 hour')->format('Y-m-d H:i:s'),
                'libelle_personnalise' => 'Transfert vers ' . $caisseDest->libelle_caisse,

                'montant_debit' => $montantOperation,
                'montant_credit' => 0,
                'solde_avant_mouvement' => $caisseSource->seuil_encaissement,
                'solde_apres_mouvement' => $caisseSource->seuil_encaissement - $montantOperation,

                'observations' => $request->observations,
            ]);

            // 🔹 5. Mise à jour solde caisse source
            $caisseSource->update([
                'seuil_encaissement' => $caisseSource->seuil_encaissement - $montantOperation
            ]);

            // 🔹 6. Mouvement CREDIT (caisse destination)
            Mouvement::create([
                'id' => Str::uuid(),
                'num_mouvement' => $numMouvement, // même numéro → permet de lier les 2 mouvements
                'caisse_id' => $caisseDest->id,
                'operateur_id' => Auth::id(),

                'date_mouvement' => \DateTime::createFromFormat('d/m/Y', $request->date_mouvement)->modify('+1 hour')->format('Y-m-d H:i:s'),
                'libelle_personnalise' => 'Transfert depuis ' . $caisseSource->libelle_caisse,

                'montant_debit' => 0,
                'montant_credit' => $montantOperation,
                'solde_avant_mouvement' => $caisseDest->seuil_encaissement,
                'solde_apres_mouvement' => $caisseDest->seuil_encaissement + $montantOperation,

                'observations' => $request->observations,
            ]);

            // 🔹 7. Mise à jour solde caisse destination
            $caisseDest->update([
                'seuil_encaissement' => $caisseDest->seuil_encaissement + $montantOperation
            ]);
        });

        return redirect()->back()->with('success', 'Transfert de fonds effectué avec succès.');
    }
    /**
     * Annuler un mouvement et son transfert associé.
     */
    public function getAssocies($num)
    {
        $mouvements = Mouvement::with(['caisse', 'operateur', 'motifStandard'])
            ->where('num_mouvement', $num)
            ->get();

        return response()->json($mouvements);
    }

    public function annulerParNumero(Request $request, $num_mouvement)
    {
        $request->validate(
            [
                'motif_annulation' => 'required|string|max:255',
            ],
            [
                'motif_annulation.required' => 'Le motif d\'annulation est obligatoire.',
                'motif_annulation.string' => 'Le motif d\'annulation doit être une chaîne de caractères.',
                'motif_annulation.max' => 'Le motif d\'annulation ne peut pas dépasser 255 caractères.',
            ]
        );

        DB::transaction(function () use ($request, $num_mouvement) {

            $mouvementsLies = Mouvement::where('num_mouvement', $num_mouvement)->get();

            if ($mouvementsLies->isEmpty()) {
                return back()->with('error', 'Aucun mouvement trouvé pour ce numéro.');
            }

            if ($mouvementsLies->every(fn($m) => $m->est_annule)) {
                return back()->with('error', 'Ces mouvements ont déjà été annulés.');
            }

            foreach ($mouvementsLies as $mvt) {
                if ($mvt->est_annule) continue;

                $caisse = $mvt->caisse;

                $nouveauSolde = $mvt->montant_debit > 0
                    ? $caisse->seuil_encaissement + $mvt->montant_debit
                    : $caisse->seuil_encaissement - $mvt->montant_credit;

                $mvt->update([
                    'est_annule' => true,
                    'date_annulation' => now(),
                    'motif_annulation' => $request->motif_annulation,
                    'annulateur_id' => Auth::id(),
                ]);

                $caisse->update([
                    'seuil_encaissement' => $nouveauSolde
                ]);
            }
        });

        return redirect()->back()->with('success', "Les Opérations liées au transfert [ $num_mouvement ] ont été annulées avec succès.");
    }
}
