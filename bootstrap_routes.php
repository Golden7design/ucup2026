<?php

// Ce script est utilisé pour tester et déboguer le chargement des routes
// Il contourne le problème de chargement automatique des fournisseurs

require __DIR__.'/vendor/autoload.php';

// Créer l'application
$app = require_once __DIR__.'/bootstrap/app.php';

// Charger manuellement les fournisseurs de services
try {
    echo "🔧 Chargement manuel des fournisseurs de services...\n";
    
    $providers = require __DIR__.'/bootstrap/providers.php';
    echo "✅ " . count($providers) . " fournisseurs trouvés dans providers.php\n";
    
    foreach ($providers as $provider) {
        try {
            if (!$app->hasBeenBootstrapped()) {
                $app->bootstrapWith([
                    'Illuminate\Foundation\Bootstrap\LoadConfiguration',
                    'Illuminate\Foundation\Bootstrap\LoadEnvironmentVariables',
                    'Illuminate\Foundation\Bootstrap\HandleExceptions',
                    'Illuminate\Foundation\Bootstrap\RegisterFacades',
                    'Illuminate\Foundation\Bootstrap\RegisterProviders',
                    'Illuminate\Foundation\Bootstrap\BootProviders',
                ]);
            }
            
            if (!isset($app->bootedProviders[$provider])) {
                $app->register($provider);
                echo "✅ Fournisseur chargé: " . $provider . "\n";
            }
        } catch (Exception $e) {
            echo "⚠️  Erreur de chargement de " . $provider . ": " . $e->getMessage() . "\n";
        }
    }
    
    // Vérifier si le RouteServiceProvider est chargé
    $loadedProviders = $app->getLoadedProviders();
    if (isset($loadedProviders['Illuminate\Routing\RouteServiceProvider'])) {
        echo "✅ RouteServiceProvider est chargé\n";
    } else {
        echo "❌ RouteServiceProvider n'est pas chargé\n";
    }
    
    // Tester les routes
    $router = $app->make('router');
    $routes = $router->getRoutes();
    echo "📊 Nombre de routes chargées: " . count($routes) . "\n";
    
    // Chercher notre route live-update
    foreach ($routes as $route) {
        if (strpos($route->uri(), 'live-update') !== false) {
            echo "✅ Route live-update trouvée: " . $route->uri() . "\n";
            echo "   Méthode: " . implode('|', $route->methods()) . "\n";
            echo "   Action: " . $route->getActionName() . "\n";
        }
    }
    
    // Tester une requête simple
    try {
        $response = $app->handle(
            Illuminate\Http\Request::create('/test-api', 'GET')
        );
        echo "🎉 Requête test réussie! Statut: " . $response->status() . "\n";
        echo "   Contenu: " . $response->getContent() . "\n";
    } catch (Exception $e) {
        echo "❌ Erreur de requête test: " . $e->getMessage() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur globale: " . $e->getMessage() . "\n";
    echo "   Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}