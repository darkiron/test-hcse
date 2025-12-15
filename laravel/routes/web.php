<?php

use Illuminate\Support\Facades\Route;

// Full API mode: aucune page HTML servie par Laravel.
// La SPA (frontend/dist) est servie par le reverse‑proxy (Caddy) à la racine.

Route::fallback(function () {
    return response()->json([
        'message' => 'Not Found. Use the JSON API under /api.',
    ], 404);
});
