<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // NÉCESSAIRE POUR LA SUPPRESSION DE FICHIERS
use Illuminate\Support\Str;
use Carbon\Carbon;

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
        $request->validate([
            'file' => 'required|file|mimes:csv,txt',
        ]);

        $path = $request->file('file')->getRealPath();
        $handle = fopen($path, 'r');

        if (!$handle) {
            return back()->withErrors(['Impossible de lire le fichier CSV.']);
        }

        $firstLine = fgets($handle);
        if ($firstLine === false) {
            fclose($handle);
            return back()->withErrors(['Le fichier CSV est vide.']);
        }

        $delimiter = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';
        rewind($handle);

        $rawHeader = fgetcsv($handle, 0, $delimiter);
        if (!$rawHeader || count(array_filter($rawHeader)) === 0) {
            fclose($handle);
            return back()->withErrors(['Le fichier CSV ne contient pas d\'en-têtes.']);
        }

        $normalizeHeader = function ($value) {
            $value = preg_replace('/^\xEF\xBB\xBF/', '', (string) $value);
            $key = Str::of($value)
                ->lower()
                ->ascii()
                ->replace([' ', '-', '.', '/', '\\', '(', ')', '[', ']'], '_')
                ->replace('__', '_')
                ->trim('_')
                ->toString();

            $map = [
                'team_id' => 'team_id',
                'id_equipe' => 'team_id',
                'equipe_id' => 'team_id',
                'team' => 'team_name',
                'team_name' => 'team_name',
                'equipe' => 'team_name',
                'nom_equipe' => 'team_name',
                'university' => 'university_short_name',
                'universite' => 'university_short_name',
                'universite_sigle' => 'university_short_name',
                'sigle_universite' => 'university_short_name',
                'university_short' => 'university_short_name',
                'university_short_name' => 'university_short_name',
                'first_name' => 'first_name',
                'prenom' => 'first_name',
                'prenoms' => 'first_name',
                'last_name' => 'last_name',
                'nom' => 'last_name',
                'nom_famille' => 'last_name',
                'jersey_number' => 'jersey_number',
                'numero' => 'jersey_number',
                'numero_maillot' => 'jersey_number',
                'maillot' => 'jersey_number',
                'position' => 'position',
                'poste' => 'position',
                'birth_date' => 'birth_date',
                'date_naissance' => 'birth_date',
                'naissance' => 'birth_date',
                'height' => 'height',
                'taille' => 'height',
                'nationality' => 'nationality',
                'nationalite' => 'nationality',
            ];

            return $map[$key] ?? $key;
        };

        $headers = array_map($normalizeHeader, $rawHeader);

        if (!in_array('team_id', $headers, true) && !in_array('team_name', $headers, true)) {
            fclose($handle);
            return back()->withErrors(['Le CSV doit contenir la colonne team_id ou team_name.']);
        }

        $required = ['first_name', 'last_name', 'position', 'jersey_number'];
        $missing = array_diff($required, $headers);
        if ($missing) {
            fclose($handle);
            return back()->withErrors(['Colonnes manquantes: ' . implode(', ', $missing)]);
        }

        $teams = Team::with('university:id,short_name')->get();
        $teamsById = $teams->keyBy('id');
        $teamsByName = $teams->groupBy(function ($team) {
            return Str::lower($team->name);
        });
        $teamsByNameAndUni = $teams->groupBy(function ($team) {
            $uni = $team->university ? Str::lower($team->university->short_name) : '';
            return Str::lower($team->name) . '|' . $uni;
        });

        $line = 1;
        $created = 0;
        $skipped = 0;
        $errors = [];
        $seenJerseys = [];

        while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
            $line++;

            if ($row === [null] || count(array_filter($row, fn($v) => trim((string) $v) !== '')) === 0) {
                continue;
            }

            $row = array_pad($row, count($headers), null);
            $data = array_combine($headers, $row);

            $teamId = isset($data['team_id']) ? trim((string) $data['team_id']) : '';
            $teamName = isset($data['team_name']) ? trim((string) $data['team_name']) : '';
            $uniShort = isset($data['university_short_name']) ? trim((string) $data['university_short_name']) : '';

            $team = null;
            if ($teamId !== '') {
                $teamId = (int) $teamId;
                $team = $teamsById->get($teamId);
                if (!$team) {
                    $errors[] = "Ligne {$line}: team_id {$teamId} introuvable.";
                    continue;
                }
            } elseif ($teamName !== '') {
                $key = Str::lower($teamName);
                if ($uniShort !== '') {
                    $keyUni = $key . '|' . Str::lower($uniShort);
                    $candidates = $teamsByNameAndUni->get($keyUni) ?? collect();
                } else {
                    $candidates = $teamsByName->get($key) ?? collect();
                }
                if ($candidates->count() === 1) {
                    $team = $candidates->first();
                } elseif ($candidates->count() > 1) {
                    $errors[] = "Ligne {$line}: plusieurs équipes portent le nom \"{$teamName}\". Ajoutez university_short_name.";
                    continue;
                } else {
                    $errors[] = "Ligne {$line}: équipe \"{$teamName}\" introuvable.";
                    continue;
                }
            } else {
                $errors[] = "Ligne {$line}: team_id ou team_name requis.";
                continue;
            }

            $firstName = trim((string) ($data['first_name'] ?? ''));
            $lastName = trim((string) ($data['last_name'] ?? ''));
            $positionRaw = trim((string) ($data['position'] ?? ''));
            $jerseyRaw = trim((string) ($data['jersey_number'] ?? ''));

            if ($firstName === '' || $lastName === '' || $positionRaw === '' || $jerseyRaw === '') {
                $errors[] = "Ligne {$line}: first_name, last_name, position et jersey_number sont requis.";
                continue;
            }

            if (!is_numeric($jerseyRaw)) {
                $errors[] = "Ligne {$line}: jersey_number doit être un nombre.";
                continue;
            }

            $jersey = (int) $jerseyRaw;
            if ($jersey < 1 || $jersey > 99) {
                $errors[] = "Ligne {$line}: jersey_number doit être entre 1 et 99.";
                continue;
            }

            $posKey = Str::of($positionRaw)->lower()->ascii()->toString();
            $positionMap = [
                'gardien' => 'Gardien',
                'gardien_de_but' => 'Gardien',
                'gk' => 'Gardien',
                'goalkeeper' => 'Gardien',
                'defenseur' => 'Défenseur',
                'defender' => 'Défenseur',
                'df' => 'Défenseur',
                'milieu' => 'Milieu',
                'midfielder' => 'Milieu',
                'mf' => 'Milieu',
                'attaquant' => 'Attaquant',
                'forward' => 'Attaquant',
                'fw' => 'Attaquant',
            ];

            $position = $positionMap[$posKey] ?? null;
            if (!$position) {
                $errors[] = "Ligne {$line}: position invalide ({$positionRaw}). Valeurs: Gardien, Défenseur, Milieu, Attaquant.";
                continue;
            }

            $teamKey = $team->id;
            $seenJerseys[$teamKey] ??= [];
            if (isset($seenJerseys[$teamKey][$jersey])) {
                $errors[] = "Ligne {$line}: jersey_number {$jersey} dupliqué dans le CSV pour cette équipe.";
                continue;
            }
            $seenJerseys[$teamKey][$jersey] = true;

            if (Player::where('team_id', $team->id)->where('jersey_number', $jersey)->exists()) {
                $skipped++;
                continue;
            }

            $birthDate = null;
            if (!empty($data['birth_date'])) {
                try {
                    $birthDate = Carbon::parse($data['birth_date'])->format('Y-m-d');
                } catch (\Exception $e) {
                    $errors[] = "Ligne {$line}: birth_date invalide.";
                    continue;
                }
            }

            $height = null;
            if (!empty($data['height'])) {
                if (!is_numeric($data['height'])) {
                    $errors[] = "Ligne {$line}: height doit être numérique.";
                    continue;
                }
                $height = (int) $data['height'];
            }

            $nationality = !empty($data['nationality']) ? trim((string) $data['nationality']) : null;

            Player::create([
                'team_id' => $team->id,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'jersey_number' => $jersey,
                'position' => $position,
                'birth_date' => $birthDate,
                'height' => $height,
                'nationality' => $nationality ?: 'DRC',
            ]);

            $created++;
        }

        fclose($handle);

        $message = "Import terminé: {$created} joueur(s) créés";
        if ($skipped > 0) {
            $message .= ", {$skipped} ignoré(s) (maillot déjà utilisé)";
        }
        $message .= '.';

        if ($errors) {
            return back()->with('success', $message)->withErrors($errors);
        }

        return back()->with('success', $message);
    }
}
