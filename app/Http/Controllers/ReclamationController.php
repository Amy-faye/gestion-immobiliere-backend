<?php

namespace App\Http\Controllers;

use App\Models\Reclamation;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $user = $request->user();

        $query = Reclamation::with(['locataire', 'bien']);

        if ($user->role === 'locataire') {
            $query->where('locataire_id', $user->id);
        } elseif ($user->role === 'proprietaire') {
            $query->whereHas('bien', function ($q) use ($user) {
                $q->where('proprietaire_id', $user->id);
            });
        }

        $reclamations = $query->orderBy('date_declaration', 'desc')->paginate($perPage);

        return response()->json($reclamations);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_incident' => 'required|string',
            'description' => 'required|string',
            'date_declaration' => 'required|date',
            'statut' => 'nullable|string',
            'locataire_id' => 'required|exists:users,id',
            'bien_id' => 'required|exists:biens_immobiliers,id',
        ]);

        // Un locataire ne peut déclarer une réclamation qu'en son propre nom
        $user = $request->user();
        if ($user->role === 'locataire') {
            $validated['locataire_id'] = $user->id;
        }

        $reclamation = Reclamation::create($validated);
        return response()->json($reclamation, 201);
    }

    public function show($id)
    {
        $reclamation = Reclamation::with(['locataire', 'bien', 'maintenance'])->findOrFail($id);
        return response()->json($reclamation);
    }

    public function update(Request $request, $id)
    {
        $reclamation = Reclamation::findOrFail($id);

        $validated = $request->validate([
            'type_incident' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'date_declaration' => 'sometimes|required|date',
            'statut' => 'nullable|string',
            'locataire_id' => 'sometimes|required|exists:users,id',
            'bien_id' => 'sometimes|required|exists:biens_immobiliers,id',
        ]);

        $reclamation->update($validated);
        return response()->json($reclamation);
    }

    public function destroy($id)
    {
        $reclamation = Reclamation::findOrFail($id);
        $reclamation->delete();
        return response()->json(['message' => 'Réclamation supprimée']);
    }
}
