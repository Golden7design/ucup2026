<?php

// Test si le fichier peut être inclus sans erreur de syntaxe
try {
    require_once 'app/Http/Controllers/Frontend/MatchController.php';
    echo "Fichier MatchController.php chargé avec succès\n";
    
    // Tester si la classe peut être instanciée
    $controller = new App\Http\Controllers\Frontend\MatchController();
    echo "Classe MatchController instanciée avec succès\n";
    
    echo "Tous les tests passés !\n";
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
}