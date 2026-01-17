<?php
// Fichier : app/Http/Controllers/Admin/GalleryController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GalleryController extends Controller
{
    /**
     * Affiche la liste des éléments de la galerie (Index Admin).
     */
    public function index()
    {
        $items = GalleryItem::orderBy('sort_order', 'asc')->get();
        return view('admin.gallery.index', compact('items'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        return view('admin.gallery.create');
    }

    /**
     * Enregistre un nouvel élément de la galerie.
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'media_file' => 'required|file|mimes:jpeg,png,jpg,gif,mp4,mov,avi|max:50000', // 50MB max
            'sort_order' => 'nullable|integer|min:0',
        ]);

        // 2. Détermination du type de média
        $file = $request->file('media_file');
        $extension = $file->getClientOriginalExtension();
        $mediaType = in_array($extension, ['mp4', 'mov', 'avi']) ? 'video' : 'image';
        
        // 3. Stockage du fichier (dans le répertoire 'public/galleries')
        // Assurez-vous que le lien symbolique est créé : php artisan storage:link
        $filePath = $file->store('galleries', 'public'); 

        // 4. Enregistrement dans la base de données
        GalleryItem::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'file_path' => $filePath,
            'media_type' => $mediaType,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()->route('admin.gallery.index')->with('success', 'Média ajouté à la galerie.');
    }
    
    /**
     * Supprime un élément de la galerie.
     */
    public function destroy(GalleryItem $item)
    {
        // 1. Suppression du fichier physique
        Storage::disk('public')->delete($item->file_path);

        // 2. Suppression de l'entrée dans la BDD
        $item->delete();

        return redirect()->route('admin.gallery.index')->with('success', 'Média supprimé.');
    }
}