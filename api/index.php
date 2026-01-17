<?php

// Point d'entrée API pour Vercel Serverless Functions

require __DIR__.'/../vendor/autoload.php';

// Charger les variables d'environnement
$app = require_once __DIR__.'/../bootstrap/app.php';

// Créer une instance de l'application Laravel
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

// Gérer la requête
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// Envoyer la réponse
$response->send();

// Terminer l'application
$kernel->terminate($request, $response);