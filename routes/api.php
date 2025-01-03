<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmController;
use App\Http\Controllers\GenreController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('films', FilmController::class);
Route::get('search/films', [FilmController::class, 'search']);
Route::get('genres', [GenreController::class, 'index']);
Route::post('scrape/films', [FilmController::class, 'scrapeFilms']);
