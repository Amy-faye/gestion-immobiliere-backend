<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class PaiementController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $user = $request->user();

        $query = Paiement::with(['contrat.bien', 'locataire']);

        if ($user->role === 'locataire') {
            $query->where('locataire_id', $user->id);
        } elseif ($user->role === 'proprietaire') {
            $query->whereHas('contrat.bien', function ($q) use ($user) {
                $q->where('proprietaire_id', $user->id);
            });
        }

        $paiements = $query->orderBy('date_paiement', 'desc')->paginate($perPage);

        return response()->json($paiements);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'montant' => 'required|numeric',
            'date_paiement' => 'required|date',
            'mode_paiement' => 'required|string',
            'statut' => 'nullable|string',
            'contrat_id' => 'required|exists:contrats_bail,id',
            'locataire_id' => 'required|exists:users,id',
        ]);

        $user = $request->user();

        if ($user->role === 'locataire') {
            // Un locataire ne peut payer que pour l'un de ses propres contrats
            $contrat = \App\Models\ContratBail::findOrFail($validated['contrat_id']);
            if ($contrat->locataire_id !== $user->id) {
                abort(403, 'Vous ne pouvez payer que votre propre loyer.');
            }
            $validated['locataire_id'] = $user->id;
        }

        $paiement = Paiement::create($validated);
        return response()->json($paiement, 201);
    }

    public function show($id)
    {
        $paiement = Paiement::with(['contrat.bien', 'locataire'])->findOrFail($id);
        return response()->json($paiement);
    }
    public function downloadQuittance(Request $request, $id)
    {
        $paiement = Paiement::with(['contrat.bien.proprietaire', 'contrat.bien.gestionnaire', 'locataire'])->findOrFail($id);
        $user = $request->user();

        if ($user->role === 'locataire' && $paiement->locataire_id !== $user->id) {
            abort(403, 'Accès non autorisé.');
        }

        $pdf = Pdf::loadView('pdf.quittance', ['paiement' => $paiement]);

        return $pdf->download('quittance-' . str_pad($paiement->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }

    public function update(Request $request, $id)
    {
        if (!in_array($request->user()->role, ['administrateur', 'gestionnaire'])) {
            abort(403, 'Action non autorisée.');
        }
        $paiement = Paiement::findOrFail($id);

        $validated = $request->validate([
            'montant' => 'sometimes|required|numeric',
            'date_paiement' => 'sometimes|required|date',
            'mode_paiement' => 'sometimes|required|string',
            'statut' => 'nullable|string',
            'contrat_id' => 'sometimes|required|exists:contrats_bail,id',
            'locataire_id' => 'sometimes|required|exists:users,id',
        ]);

        $paiement->update($validated);
        return response()->json($paiement);
    }

    public function destroy(Request $request, $id)
    {
        if (!in_array($request->user()->role, ['administrateur', 'gestionnaire'])) {
            abort(403, 'Action non autorisée.');
        }
        $paiement = Paiement::findOrFail($id);
        $paiement->delete();
        return response()->json(['message' => 'Paiement supprimé']);
    }
}
