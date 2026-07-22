<?php

namespace App\Http\Controllers;

use App\Models\BienImmobilier;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
class BienImmobilierController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $user = $request->user();

        $query = BienImmobilier::with(['proprietaire', 'gestionnaire']);

        if ($user->role === 'proprietaire') {
            $query->where('proprietaire_id', $user->id);
        }

        $biens = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($biens);
    }

    public function store(Request $request)
    {
        if (!in_array($request->user()->role, ['administrateur', 'gestionnaire'])) {
            abort(403, 'Action non autorisée.');
        }

        $validated = $request->validate([
            'type' => 'required|string',
            'adresse' => 'required|string',
            'description' => 'required|string',
            'loyer_mensuel' => 'required|numeric',
            'statut' => 'nullable|string',
            'photo' => 'nullable|image|max:4096',
            'proprietaire_id' => ['required', Rule::exists('users', 'id')->where('role', 'proprietaire')],
            'gestionnaire_id' => ['required', Rule::exists('users', 'id')->where('role', 'gestionnaire')],
            ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('biens', 'public');
        }

        $bien = BienImmobilier::create($validated);
        return response()->json($bien, 201);
    }

    public function show($id)
    {
        $bien = BienImmobilier::with(['proprietaire', 'gestionnaire', 'contrats'])->findOrFail($id);
        return response()->json($bien);
    }

    public function update(Request $request, $id)
    {
        if (!in_array($request->user()->role, ['administrateur', 'gestionnaire'])) {
            abort(403, 'Action non autorisée.');
        }

        $bien = BienImmobilier::findOrFail($id);

        $validated = $request->validate([
            'type' => 'sometimes|required|string',
            'adresse' => 'sometimes|required|string',
            'description' => 'sometimes|required|string',
            'loyer_mensuel' => 'sometimes|required|numeric',
            'statut' => 'nullable|string',
            'photo' => 'nullable|image|max:4096',
            'proprietaire_id' => 'sometimes|required|exists:users,id',
            'gestionnaire_id' => 'sometimes|required|exists:users,id',
        ]);

        if ($request->hasFile('photo')) {
            if ($bien->photo) {
                \Storage::disk('public')->delete($bien->photo);
            }
            $validated['photo'] = $request->file('photo')->store('biens', 'public');
        }

        $bien->update($validated);
        return response()->json($bien);
    }

    public function destroy(Request $request, $id)
    {
        if (!in_array($request->user()->role, ['administrateur', 'gestionnaire'])) {
            abort(403, 'Action non autorisée.');
        }
        $bien = BienImmobilier::findOrFail($id);
        $bien->update(['statut' => 'archivé']);
        return response()->json(['message' => 'Bien archivé avec succès']);
    }
}

