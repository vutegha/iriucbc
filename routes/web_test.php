<?php
use Illuminate\Support\Facades\Route;

// Test simple
Route::get('/', function () {
    return 'Test OK - Routes fonctionnent';
});

Route::get('/test-db', function () {
    try {
        $count = DB::table('actualites')->count();
        return "Database OK - Actualités: $count";
    } catch (Exception $e) {
        return "Erreur DB: " . $e->getMessage();
    }
});
