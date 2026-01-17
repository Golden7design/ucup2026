<?php

echo "Test de base PHP...\n";

// Tester si nous pouvons charger l'autoloader
try {
    require __DIR__.'/vendor/autoload.php';
    echo "✅ Autoloader chargé avec succès\n";
} catch (Exception $e) {
    echo "❌ Erreur de chargement de l'autoloader: " . $e->getMessage() . "\n";
    exit(1);
}

// Tester si nous pouvons créer l'application
try {
    $app = require_once __DIR__.'/bootstrap/app.php';
    echo "✅ Application Laravel créée avec succès\n";
} catch (Exception $e) {
    echo "❌ Erreur de création de l'application: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}

// Tester si nous pouvons accéder au routeur
try {
    $router = $app->make('router');
    echo "✅ Routeur accessible\n";
    
    // Tester si nous pouvons accéder aux routes
    $routes = $router->getRoutes();
    echo "✅ Routes accessibles: " . count($routes) . " routes trouvées\n";
    
    // Vérifier si le RouteServiceProvider est chargé
    $providers = $app->getLoadedProviders();
    if (isset($providers['Illuminate\Routing\RouteServiceProvider'])) {
        echo "✅ RouteServiceProvider est chargé\n";
    } else {
        echo "❌ RouteServiceProvider n'est pas chargé\n";
        echo "Fournisseurs chargés: " . implode(', ', array_keys($providers)) . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erreur d'accès au routeur: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    exit(1);
}

echo "\n🎉 Tous les tests de base ont réussi !\n";