<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

try {
    // Tester si nous pouvons accéder aux routes
    $router = $app->make('router');
    $routes = $router->getRoutes();
    
    echo "✅ Routes accessibles - Nombre total de routes: " . count($routes) . "\n";
    
    // Chercher notre route live-update
    foreach ($routes as $route) {
        if (strpos($route->uri(), 'live-update') !== false) {
            echo "✅ Route live-update trouvée: " . $route->uri() . "\n";
            echo "   Méthode: " . implode('|', $route->methods()) . "\n";
            echo "   Action: " . $route->getActionName() . "\n";
        }
    }
    
    // Tester spécifiquement notre route de test
    $testRouteFound = false;
    foreach ($routes as $route) {
        if ($route->uri() === 'test-api') {
            $testRouteFound = true;
            echo "✅ Route de test trouvée: /test-api\n";
            break;
        }
    }
    
    if (!$testRouteFound) {
        echo "❌ Route de test non trouvée\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur lors de l'accès aux routes: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}