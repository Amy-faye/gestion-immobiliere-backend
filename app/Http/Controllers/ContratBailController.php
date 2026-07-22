<?php

namespace App\Http\Controllers;

use App\Models\ContratBail;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ContratBailController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $user = $request->user();

        $query = ContratBail::with(['bien', 'locataire']);

        if ($user->role === 'locataire') {
            $query->where('locataire_id', $user->id);
        } elseif ($user->role === 'proprietaire') {
            $query->whereHas('bien', function ($q) use ($user) {
                $q->where('proprietaire_id', $user->id);
            });
        }

        $contrats = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($contrats);
    }

    public function store(Request $request)
    {
        if (!in_array($request->user()->role, ['administrateur', 'gestionnaire'])) {
            abort(403, 'Action non autorisée.');
        }
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date',
            'loyer_mensuel' => 'required|numeric',
            'caution' => 'required|numeric',
            'bien_id' => 'required|exists:biens_immobiliers,id',
            'locataire_id' => 'required|exists:users,id',
        ]);

        $contrat = ContratBail::create($request->all());
        return response()->json($contrat, 201);
    }

    public function show($id)
    {
        $contrat = ContratBail::with(['bien', 'locataire', 'paiements'])->findOrFail($id);
        return response()->json($contrat);
    }

    public function update(Request $request, $id)
    {
        if (!in_array($request->user()->role, ['administrateur', 'gestionnaire'])) {
            abort(403, 'Action non autorisée.');
        }
        $contrat = ContratBail::findOrFail($id);
        $contrat->update($request->all());
        return response()->json($contrat);
    }

    public function destroy(Request $request, $id)
    {
        if (!in_array($request->user()->role, ['administrateur', 'gestionnaire'])) {
            abort(403, 'Action non autorisée.');
        }
        $contrat = ContratBail::findOrFail($id);
        $contrat->update(['statut' => 'résilié']);
        return response()->json(['message' => 'Contrat résilié avec succès']);
    }
    public function downloadContrat(Request $request, $id)
    {
        $contrat = ContratBail::with(['bien.proprietaire', 'bien.gestionnaire', 'locataire'])->findOrFail($id);
        $user = $request->user();

        if ($user->role === 'locataire' && $contrat->locataire_id !== $user->id) {
            abort(403, 'Accès non autorisé.');
        }

        $pdf = Pdf::loadView('pdf.contrat', ['contrat' => $contrat]);

        return $pdf->download('contrat-' . str_pad($contrat->id, 6, '0', STR_PAD_LEFT) . '.pdf');
    }
}
