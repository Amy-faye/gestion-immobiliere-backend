<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
use App\Models\User;
use Illuminate\Support\Facades\Hash;

Route::get('/setup-admin-baobab-2026', function () {
    if (User::where('email', 'gestionnaire@baobab.sn')->exists()) {
        return 'Ce compte existe déjà.';
    }

    User::create([
        'name' => 'Gestionnaire Baobab',
        'email' => 'gestionnaire@baobab.sn',
        'password' => Hash::make('motdepasse123'),
        'role' => 'gestionnaire',
    ]);

    return 'Compte gestionnaire créé avec succès !';
});
