<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\MatchController;
use App\Http\Controllers\Frontend\TeamController;
use App\Http\Controllers\Frontend\PlayerController;
use App\Http\Controllers\Frontend\StandingController;
use App\Http\Controllers\Auth\LoginController;

// Contrôleur Frontend pour la Galerie (Utilisé pour la route publique)
use App\Http\Controllers\Frontend\GalleryController as FrontendGalleryController; // <<< UTILISER L'ALIAS

// Contrôleurs Admin
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UniversityController;
use App\Http\Controllers\Admin\TeamController as AdminTeamController; // IMPORTANT : Alias pour l'Admin Team Controller
use App\Http\Controllers\Admin\PlayerController as AdminPlayerController;
use App\Http\Controllers\Admin\MatchController as AdminMatchController; // ALIAS pour le contrôleur de Match
use App\Http\Controllers\Admin\MatchEventController;
use App\Http\Controllers\Admin\LiveMatchController;
use App\Http\Controllers\Admin\StandingAdminController;
use App\Http\Controllers\Admin\GalleryController; // Conserver ce nom pour les routes Admin
use App\Http\Controllers\Admin\UserController; // Ajout du contrôleur de gestion des utilisateurs
use App\Http\Controllers\ThemeController;

use App\Services\StandingService;
use App\Models\MatchModel;


/*
|--------------------------------------------------------------------------
| Routes Frontend (Public)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// CORRECTION : Utilisation du contrôleur FrontendGalleryController (l'alias)
Route::get('/galerie', [FrontendGalleryController::class, 'index'])->name('gallery.index'); 

// Matchs
Route::prefix('matches')->name('matches.')->group(function () {
    Route::get('/', [MatchController::class, 'index'])->name('index');
    Route::get('/live', [MatchController::class, 'live'])->name('live');
    Route::get('/{match}', [MatchController::class, 'show'])->name('show');
    Route::get('/{match}/sofascore', [MatchController::class, 'showSofascore'])->name('show.sofascore');
});

// Équipes
Route::prefix('teams')->name('teams.')->group(function () {
    Route::get('/', [TeamController::class, 'index'])->name('index');
    Route::get('/{team}', [TeamController::class, 'show'])->name('show');
});

// Joueurs
Route::prefix('players')->name('players.')->group(function () {
    Route::get('/', [PlayerController::class, 'index'])->name('index');
    // Le classement des joueurs DOIT être déclaré avant Route::get('/{player}',...) pour éviter un conflit d'URI
    Route::get('/leaderboard', [PlayerController::class, 'leaderboard'])->name('leaderboard'); 
    Route::get('/{player}', [PlayerController::class, 'show'])->name('show');
});

// Classements
Route::prefix('standings')->name('standings.')->group(function () {
    Route::get('/', [StandingController::class, 'index'])->name('index');
    Route::get('/group/{group}', [StandingController::class, 'group'])->name('group');
});

/*
|--------------------------------------------------------------------------
| Routes Admin (Backend)
|--------------------------------------------------------------------------
*/

// Groupe de routes protégé par 'auth' (Connexion nécessaire)
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // 🏆 Universités
    Route::get('universities', [UniversityController::class, 'index'])->name('universities.index');
    Route::get('universities/create', [UniversityController::class, 'create'])->name('universities.create');
    Route::post('universities', [UniversityController::class, 'store'])->name('universities.store');
    Route::get('universities/{university}/edit', [UniversityController::class, 'edit'])->name('universities.edit');
    Route::put('universities/{university}', [UniversityController::class, 'update'])->name('universities.update');
    Route::delete('universities/{university}', [UniversityController::class, 'destroy'])->name('universities.destroy');

    // Équipes
    Route::resource('teams', AdminTeamController::class); 

    // Joueurs
    Route::resource('players', AdminPlayerController::class);
    Route::post('players/bulk-import', [AdminPlayerController::class, 'bulkImport'])->name('players.bulk-import');

    // Matchs (CRUD Principal)
    Route::resource('matches', AdminMatchController::class);
    
    // Actions supplémentaires pour les Matchs (Utilisation de l'ALIAS AdminMatchController)
    Route::post('matches/{match}/duplicate', [AdminMatchController::class, 'duplicate'])->name('matches.duplicate');
    
    // ⚽ Routes pour la gestion des COMPOSITIONS D'ÉQUIPE (Lineup)
    // 1. Route pour afficher le formulaire de composition d'équipe
    Route::get('matches/{match}/lineup', [AdminMatchController::class, 'lineup'])
        ->name('matches.lineup');

    // 2. Route pour soumettre et enregistrer la composition d'équipe (CORRIGÉE : Ajout du crochet ']')
    Route::post('matches/{match}/lineup', [AdminMatchController::class, 'storeLineup']) // <-- Ligne 107 (ou proche) CORRIGÉE
        ->name('matches.store_lineup'); // Nom utilisé dans le formulaire Blade
    
    // Événements de Matchs (CRUD Standard - à des fins d'édition en dehors du live si nécessaire)
    Route::resource('match-events', MatchEventController::class)->except(['index']);



    // ----------------------------------------------------------------
    // ⚽ Gestion des matchs en direct (Live)
    // ----------------------------------------------------------------
    Route::prefix('live')->name('live.')->group(function () {
        
        // Liste des matchs pour le Live Center (URI: /admin/live)
        Route::get('/', [LiveMatchController::class, 'index'])->name('index'); 
        
        // Gestion d'un match spécifique (URI: /admin/live/match/{match})
        Route::get('/match/{match}', [LiveMatchController::class, 'show'])->name('show');
        
        // ✨ CORRECTION : Route pour la mise à jour des stats agrégées (Fautes, Corners, etc.)
        Route::post('/match/{match}/update-stats', [LiveMatchController::class, 'updateStats'])->name('update_stats'); 
        
        // Mise à jour du statut (URI: /admin/live/status/{match})
        Route::post('/status/{match}', [LiveMatchController::class, 'updateStatus'])->name('update_status'); 

        // Ajout d'événement (URI: /admin/live/event/{match})
        Route::post('/event/{match}', [LiveMatchController::class, 'addEvent'])->name('add_event'); 

        // Annulation/Suppression d'événement (URI: /admin/live/event/{matchEvent})
        Route::delete('/event/{matchEvent}', [LiveMatchController::class, 'deleteEvent'])->name('delete_event'); 
        
        // Mise à jour de la Composition d'équipe Live (différente du CRUD standard)
        // URI: /admin/live/lineup/{match}/{team}
        Route::post('/lineup/{match}/{team}', [LiveMatchController::class, 'updateLineup'])->name('lineup.update');
    });

    // Statistiques
    Route::get('/statistics', [DashboardController::class, 'statistics'])->name('statistics');
    Route::get('/reports', [DashboardController::class, 'reports'])->name('reports');

    // Gestion du minuteur des matchs
    Route::prefix('match-timer')->name('match-timer.')->group(function () {
        Route::post('/{match}/start', [\App\Http\Controllers\Admin\MatchTimerController::class, 'startTimer'])->name('start');
        Route::post('/{match}/pause', [\App\Http\Controllers\Admin\MatchTimerController::class, 'pauseTimer'])->name('pause');
        Route::post('/{match}/resume', [\App\Http\Controllers\Admin\MatchTimerController::class, 'resumeTimer'])->name('resume');
        Route::post('/{match}/stop', [\App\Http\Controllers\Admin\MatchTimerController::class, 'stopTimer'])->name('stop');
        Route::post('/{match}/additional-time-first-half', [\App\Http\Controllers\Admin\MatchTimerController::class, 'addAdditionalTimeFirstHalf'])->name('additional-time-first-half');
        Route::post('/{match}/additional-time-second-half', [\App\Http\Controllers\Admin\MatchTimerController::class, 'addAdditionalTimeSecondHalf'])->name('additional-time-second-half');
        Route::post('/{match}/enable-extra-time', [\App\Http\Controllers\Admin\MatchTimerController::class, 'enableExtraTime'])->name('enable-extra-time');
        Route::post('/{match}/disable-extra-time', [\App\Http\Controllers\Admin\MatchTimerController::class, 'disableExtraTime'])->name('disable-extra-time');
        Route::post('/{match}/enable-penalty-shootout', [\App\Http\Controllers\Admin\MatchTimerController::class, 'enablePenaltyShootout'])->name('enable-penalty-shootout');
        Route::post('/{match}/disable-penalty-shootout', [\App\Http\Controllers\Admin\MatchTimerController::class, 'disablePenaltyShootout'])->name('disable-penalty-shootout');
        Route::get('/{match}/elapsed-time', [\App\Http\Controllers\Admin\MatchTimerController::class, 'getElapsedTime'])->name('elapsed-time');
    });

    // Outil de maintenance du classement
    Route::post('/standings/recalculate', [StandingAdminController::class, 'recalculate'])->name('standings.recalculate');
    
    // Gestion des utilisateurs
    Route::resource('users', UserController::class)->names([
        'index' => 'users.index',
        'create' => 'users.create',
        'store' => 'users.store',
        'show' => 'users.show',
        'edit' => 'users.edit',
        'update' => 'users.update',
        'destroy' => 'users.destroy',
    ]);

}); // FIN DU GROUPE ADMIN (Middleware auth)

/*
|--------------------------------------------------------------------------
| Routes d'authentification
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Groupe de routes protégé par 'auth' ET 'admin' (Admin seulement)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // ... vos autres routes admin
    
    // Routes pour la Galerie (Protégées) - Utilise le contrôleur Admin\GalleryController
    Route::resource('gallery', GalleryController::class)->except(['show', 'edit', 'update']);
});

/*
|--------------------------------------------------------------------------
| ROUTE DE TEST TEMPORAIRE (POUR RECALCULER TOUS LES CLASSEMENTS)
|--------------------------------------------------------------------------
*/
// Cette route est conservée ici pour le test manuel via GET si nécessaire.
Route::get('/test-classement', function (StandingService $standingService) {
    
    // --- ÉTAPE CRUCIALE : RECALCULER TOUS LES CLASSEMENTS ---
    $standingService->recalculateAllStandings();

    return "TOUT le classement a été mis à jour avec succès à partir de tous les matchs terminés. Vérifiez la page /standings.";
});

// Route API pour récupérer le statut et le temps d'un match
Route::get('/api/match/{match}/status', [MatchController::class, 'getLiveStatus'])->name('api.match.status');

// Route debug pour le timer (temporaire)
Route::get('/api/matches/{match}/timer/debug', [\App\Http\Controllers\Api\MatchTimerDebugController::class, 'debug'])->name('api.match.timer.debug');

// Route pour tester le broadcast du timer
Route::get('/api/matches/{match}/timer/test-broadcast', [\App\Http\Controllers\Api\MatchTimerDebugController::class, 'testBroadcast'])->name('api.match.timer.test-broadcast');

// Route de test pour vérifier si les routes API fonctionnent
Route::get('/test-api', function () {
    return response()->json(['message' => 'API test successful', 'status' => 'working']);
});

// Routes pour la gestion du thème
Route::prefix('theme')->name('theme.')->group(function () {
    Route::get('/', [ThemeController::class, 'show'])->name('show');
    Route::post('/', [ThemeController::class, 'store'])->name('store');
});

// Routes pour les paramètres utilisateur (Inertia)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/settings', function () {
        return inertia('Settings/Index');
    })->name('settings.index');
    
    Route::get('/settings/appearance', function () {
        return inertia('Settings/Appearance');
    })->name('settings.appearance');
});

// Routes pour les statistiques manuelles (Admin)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/matches/{match}/stats/edit', [\App\Http\Controllers\Admin\ManualStatsController::class, 'edit'])->name('admin.matches.stats.edit');
    Route::put('/matches/{match}/stats', [\App\Http\Controllers\Admin\ManualStatsController::class, 'update'])->name('admin.matches.stats.update');
    Route::get('/matches/{match}/stats', [\App\Http\Controllers\Admin\ManualStatsController::class, 'show'])->name('admin.matches.stats.show');
    
    // Route pour charger les joueurs d'une équipe
    Route::get('/matches/{match}/players/{team}', [\App\Http\Controllers\Admin\LiveMatchController::class, 'getPlayers'])->name('admin.matches.players');
});

// Formulaire d'inscription joueur (le "mini-site" temporaire)
use App\Http\Controllers\Frontend\PlayerRegistrationController;

Route::get('/inscription-joueur', [PlayerRegistrationController::class, 'create'])->name('player.register.create');
Route::post('/inscription-joueur', [PlayerRegistrationController::class, 'store'])->name('player.register.store');