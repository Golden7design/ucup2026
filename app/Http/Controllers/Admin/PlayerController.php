<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // NÉCESSAIRE POUR LA SUPPRESSION DE FICHIERS

class PlayerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) // 🚨 Injection de Request
    {
        // 1. Récupérer toutes les équipes pour le filtre dans la vue
        $teams = Team::with('university')->orderBy('name')->get(); 
        
        $query = Player::with(['team.university']);
        
        // 2. FILTRE PAR ÉQUIPE
        if ($request->filled('team_id')) {
            $query->where('team_id', $request->team_id);
        }

        // 3. RECHERCHE PAR NOM
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', '%' . $search . '%')
                  ->orWhere('last_name', 'like', '%' . $search . '%')
                  ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ['%' . $search . '%']);
            });
        }
        
        // Tri et pagination
        $players = $query->orderBy('last_name', 'asc')
                         ->paginate(20)
                         ->withQueryString(); // Garde les filtres actifs lors de la pagination

        // 4. Passer $teams à la vue
        return view('admin.players.index', compact('players', 'teams')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $teams = Team::with('university')->orderBy('name')->get();
        return view('admin.players.create', compact('teams'));
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {
        // Validation des données du joueur
        $validatedData = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // Règle unique pour le numéro de maillot : doit être unique pour cette équipe (team_id)
            'jersey_number' => 'nullable|integer|min:1|max:99|unique:players,jersey_number,NULL,id,team_id,' . $request->team_id, 
            'position' => 'required|in:Gardien,Défenseur,Milieu,Attaquant',
            'birth_date' => 'nullable|date', 
            'height' => 'nullable|integer|min:100|max:250',
            // 📸 Règle de validation pour la photo 📸
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        // 🚨 GESTION DE L'UPLOAD DE LA PHOTO 🚨
        if ($request->hasFile('photo')) {
            // Enregistre le fichier dans storage/app/public/players/
            $path = $request->file('photo')->store('players', 'public'); 
            // Ajoute le chemin d'accès à l'array pour l'enregistrement en base de données
            $validatedData['photo_path'] = $path; 
        }

        Player::create($validatedData); // Utilise les données validées, y compris photo_path

        return redirect()->route('admin.players.index')->with('success', 'Joueur ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Player $player)
    {
        // non utilisé dans l'admin (la vue frontend l'utilise)
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Player $player)
    {
        $teams = Team::with('university')->orderBy('name')->get();
        return view('admin.players.edit', compact('player', 'teams'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Player $player)
    {
        // 🚨 Correction : Assurer que l'unicité du maillot ignore le joueur actuel
        $validatedData = $request->validate([
            'team_id' => 'required|exists:teams,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            // Règle unique : Ignorer le joueur actuel ($player->id)
            'jersey_number' => 'nullable|integer|min:1|max:99|unique:players,jersey_number,' . $player->id . ',id,team_id,' . $request->team_id, 
            'position' => 'required|in:Gardien,Défenseur,Milieu,Attaquant',
            'birth_date' => 'nullable|date', 
            'height' => 'nullable|integer|min:100|max:250',
            // 📸 Règle de validation pour la photo (le fichier n'est pas requis ici) 📸
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
        ]);

        // 🚨 GESTION DE LA MISE À JOUR DE LA PHOTO 🚨
        if ($request->hasFile('photo')) {
            
            // 1. Supprimer l'ancienne photo si elle existe
            if ($player->photo_path) {
                Storage::disk('public')->delete($player->photo_path);
            }
            
            // 2. Enregistrer la nouvelle photo
            $path = $request->file('photo')->store('players', 'public');
            $validatedData['photo_path'] = $path;
        }

        $player->update($validatedData);
        
        return redirect()->route('admin.players.index')->with('success', 'Joueur mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Player $player)
    {
        // 🚨 GESTION DE LA SUPPRESSION DE LA PHOTO 🚨
        if ($player->photo_path) {
            Storage::disk('public')->delete($player->photo_path);
        }
        
        $player->delete();
        return redirect()->route('admin.players.index')->with('success', 'Joueur supprimé avec succès.');
    }

    /**
     * Handle bulk import of players from a file.
     */
    public function bulkImport(Request $request)
    {
        // Logique d'importation en masse à implémenter plus tard
        return back()->with('success', 'Fonction d\'importation en masse à implémenter.');
    }
}