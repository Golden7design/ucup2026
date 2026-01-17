<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';

try {
    // Boot l'application
    $app->boot();
    
    // Créer un nouvel utilisateur admin
    $user = new App\Models\User();
    $user->name = 'Admin';
    $user->email = 'admin@example.com';
    $user->password = bcrypt('password'); // Mot de passe: "password"
    $user->is_admin = true;
    $user->save();
    
    echo "✅ Utilisateur admin créé avec succès!\n";
    echo "Email: admin@example.com\n";
    echo "Mot de passe: password\n";
    echo "Statut admin: Oui\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de la création de l'utilisateur admin: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}