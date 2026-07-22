<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    private function ensureManager(Request $request): void
    {
        $role = $request->user()->role;
        if (!in_array($role, ['administrateur', 'gestionnaire'])) {
            abort(403, 'Action non autorisée.');
        }
    }

    public function index(Request $request)
    {
        $this->ensureManager($request);

        $query = User::query();

        if ($request->has('role')) {
            $query->where('role', $request->get('role'));
        }

        $users = $query->select('id', 'name', 'email', 'role', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $this->ensureManager($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => ['required', Rule::in(['administrateur', 'gestionnaire', 'locataire', 'proprietaire'])],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return response()->json($user, 201);
    }

    public function destroy(Request $request, $id)
    {
        $this->ensureManager($request);

        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé avec succès']);
    }
}
