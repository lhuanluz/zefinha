<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeckController;
use App\Http\Controllers\TournamentController;
use App\Http\Controllers\DashboardController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

Route::get('/fetch-deck-list/{commanderName}', [DashboardController::class, 'fetchDeckList']);

Route::resource('tournaments', TournamentController::class)->except([
    'edit', 'update'
]);
Route::post('/tournaments/{tournament}/updateRoundPoints', [TournamentController::class, 'updateRoundPoints'])->name('tournaments.updateRoundPoints');
Route::delete('/tournaments/{tournament}', [TournamentController::class, 'destroy'])->name('tournaments.destroy');
Route::post('/tournaments/{tournament}/join', [TournamentController::class, 'join'])->name('tournaments.join');
Route::get('/tournaments/{tournament}/selectDeck', [TournamentController::class, 'selectDeck'])->name('tournaments.selectDeck');
Route::delete('/tournaments/{tournament}/removeDeck/{deck}', [TournamentController::class, 'removeDeck'])->name('tournaments.removeDeck');
Route::post('/tournaments/{tournament}/start', [TournamentController::class, 'start'])->name('tournaments.start');


Route::get('/decks/create', [DeckController::class, 'create'])->name('decks.create');
Route::get('/decks', [DeckController::class, 'index'])->name('decks.index');
Route::get('/decks/{deck}', [DeckController::class, 'show'])->name('decks.show');
Route::post('/decks', [DeckController::class, 'store'])->name('decks.store');
Route::delete('/decks/{deck}', [DeckController::class, 'destroy'])->name('decks.destroy');








