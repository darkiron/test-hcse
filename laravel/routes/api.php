<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OfferController as ApiOfferController;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\Api\AuthController;

// Appliquer un rate limiting global aux routes API (sécurité de base)
Route::middleware(['throttle:60,1'])->group(function () {
    // Profil authentifié (retour JSON 401 si non connecté)
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->middleware(['auth:sanctum']);

    // Auth (Sanctum SPA): session + cookies + rate limiting spécifique
    Route::post('/login', [AuthController::class, 'login'])->middleware(['web', 'throttle:6,1']);
    Route::post('/logout', [AuthController::class, 'logout'])->middleware(['web', 'auth:sanctum']);

    // (Aucun endpoint public: tous les accès données passent par auth:sanctum ci-dessous)

    // Endpoints protégés (authentification requise) — groupe API stateful Sanctum
    // IMPORTANT: ne pas ajouter 'web' ici pour éviter la redirection vers une route 'login' inexistante
    Route::middleware(['auth:sanctum'])->group(function () {
        // Lecture sécurisée
        Route::get('/offers', [ApiOfferController::class, 'index']);
        Route::get('/offers/{offer}/products', [ApiProductController::class, 'index']);

        // Création d'une offre
        Route::post('/offers', [ApiOfferController::class, 'store']);

        // Produits d'une offre (CRUD)
        Route::post('/offers/{offer}/products', [ApiProductController::class, 'store']);
        Route::put('/offers/{offer}/products/{product}', [ApiProductController::class, 'update']);
        Route::delete('/offers/{offer}/products/{product}', [ApiProductController::class, 'destroy']);
    });
});
