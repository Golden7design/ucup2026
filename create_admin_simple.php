<?php

// Connexion directe à la base de données SQLite
try {
    $db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si l'utilisateur admin existe déjà
    $stmt = $db->prepare("SELECT * FROM users WHERE email = 'admin@example.com'");
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        echo "⚠️  Un utilisateur admin existe déjà avec cet email.\n";
    } else {
        // Créer un nouvel utilisateur admin
        $password = password_hash('password', PASSWORD_BCRYPT);
        $stmt = $db->prepare("INSERT INTO users (name, email, password, is_admin, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))");
        $stmt->execute(['Admin', 'admin@example.com', $password, 1]);
        
        echo "✅ Utilisateur admin créé avec succès!\n";
        echo "Email: admin@example.com\n";
        echo "Mot de passe: password\n";
        echo "Statut admin: Oui\n";
    }
    
    // Lister tous les utilisateurs pour vérification
    echo "\n📋 Liste de tous les utilisateurs:\n";
    $users = $db->query("SELECT id, name, email, is_admin FROM users")->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($users as $user) {
        echo "ID: " . $user['id'] . "\n";
        echo "Nom: " . $user['name'] . "\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Admin: " . ($user['is_admin'] ? 'Oui' : 'Non') . "\n";
        echo "----------------------------\n";
    }
    
} catch (PDOException $e) {
    echo "❌ Erreur de base de données: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "❌ Erreur générale: " . $e->getMessage() . "\n";
}