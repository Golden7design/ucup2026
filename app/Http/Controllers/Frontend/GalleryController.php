<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// Importer le modèle que vous utilisez pour stocker les médias
// Nous utiliserons GalleryItem à titre d'exemple.
use App\Models\GalleryItem; 

class GalleryController extends Controller
{
    /**
     * Affiche la liste des médias publics pour le frontend.
     */
    public function index()
    {
        // 1. Récupérer les items. Assurez-vous que cette ligne correspond à votre modèle réel.
        // Option : Ajoutez ->where('is_public', true) si vous avez une colonne pour filtrer les items publics.
        
        // Nous utilisons un bloc try-catch au cas où la table n'existe pas encore
        // pour éviter une erreur fatale et afficher un message à l'utilisateur.
        try {
             $items = GalleryItem::latest()->get(); 
        } catch (\Exception $e) {
             // Si le modèle/la table n'est pas prêt(e), passez un tableau vide.
             $items = collect([]); 
        }

        // 2. Passer la variable $items à la vue en utilisant compact('items')
        return view('frontend.gallery.index', compact('items')); 
    }
}