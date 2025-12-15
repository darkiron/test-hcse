<?php

use Illuminate\Support\Facades\Route;

// Full API mode: aucune page HTML servie par Laravel.
// L’UI est servie en statique par Nginx sous /ui/.

Route::fallback(function () {
    return response()->json([
        'message' => 'Not Found. Use the JSON API under /api or the static UI under /ui/.',
    ], 404);
});
