<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BienImmobilierController;
use App\Http\Controllers\ContratBailController;
use App\Http\Controllers\PaiementController;
use App\Http\Controllers\ReclamationController;
use App\Http\Controllers\MaintenanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('mot-de-passe-oublie', [AuthController::class, 'forgotPassword']);
Route::post('reinitialiser-mot-de-passe', [AuthController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);

    Route::get('users', [UserController::class, 'index']);
    Route::post('users', [UserController::class, 'store']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    Route::apiResource('biens', BienImmobilierController::class);
    Route::apiResource('contrats', ContratBailController::class);
    Route::get('contrats/{id}/pdf', [ContratBailController::class, 'downloadContrat']);
    Route::apiResource('paiements', PaiementController::class);
    Route::get('paiements/{id}/quittance', [PaiementController::class, 'downloadQuittance']);
    Route::apiResource('reclamations', ReclamationController::class);
    Route::apiResource('maintenances', MaintenanceController::class);
});
