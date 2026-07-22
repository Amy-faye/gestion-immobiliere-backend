<?php

namespace App\Http\Controllers;

use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $user = $request->user();

        $query = Maintenance::with(['reclamation.bien', 'reclamation.locataire']);

        if ($user->role === 'proprietaire') {
            $query->whereHas('reclamation.bien', function ($q) use ($user) {
                $q->where('proprietaire_id', $user->id);
            });
        }

        $maintenances = $query->orderBy('date_intervention', 'desc')->paginate($perPage);

        return response()->json($maintenances);
    }

    public function store(Request $request)
    {
        if (!in_array($request->user()->role, ['administrateur', 'gestionnaire'])) {
            abort(403, 'Action non autorisée.');
        }
        $validated = $request->validate([
            'description_travaux' => 'required|string',
            'cout' => 'required|numeric',
            'date_intervention' => 'required|date',
            'statut' => 'nullable|string',
            'reclamation_id' => 'required|exists:reclamations,id',
        ]);

        $maintenance = Maintenance::create($validated);
        return response()->json($maintenance, 201);
    }

    public function show($id)
    {
        $maintenance = Maintenance::with(['reclamation.bien', 'reclamation.locataire'])->findOrFail($id);
        return response()->json($maintenance);
    }

    public function update(Request $request, $id)
    {
        if (!in_array($request->user()->role, ['administrateur', 'gestionnaire'])) {
            abort(403, 'Action non autorisée.');
        }
        $maintenance = Maintenance::findOrFail($id);

        $validated = $request->validate([
            'description_travaux' => 'sometimes|required|string',
            'cout' => 'sometimes|required|numeric',
            'date_intervention' => 'sometimes|required|date',
            'statut' => 'nullable|string',
            'reclamation_id' => 'sometimes|required|exists:reclamations,id',
        ]);

        $maintenance->update($validated);
        return response()->json($maintenance);
    }

    public function destroy(Request $request, $id)
    {
        if (!in_array($request->user()->role, ['administrateur', 'gestionnaire'])) {
            abort(403, 'Action non autorisée.');
        }
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->delete();
        return response()->json(['message' => 'Maintenance supprimée']);
    }
}
