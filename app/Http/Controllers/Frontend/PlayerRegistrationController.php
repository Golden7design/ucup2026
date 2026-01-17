<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class PlayerRegistrationController extends Controller
{
    const REGISTRATION_DEADLINE = '2026-03-31 23:59:59';

    protected function isRegistrationOpen()
    {
        return Carbon::now()->lessThanOrEqualTo(Carbon::parse(self::REGISTRATION_DEADLINE));
    }

    public function create()
    {
        if (!$this->isRegistrationOpen()) {
            return view('frontend.registration_closed');
        }
        
        $teams = Team::all();
        $posts = ['Gardien', 'Défenseur', 'Milieu', 'Attaquant'];

        return view('frontend.player_register', compact('teams', 'posts'));
    }

    public function store(Request $request)
    {
        if (!$this->isRegistrationOpen()) {
            return redirect()->route('player.register.create')->with('error', 'La période d\'inscription des joueurs est terminée.');
        }
        
        $validatedData = $request->validate([
            'team_id' => ['required', 'exists:teams,id'],
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'jersey_number' => [
                'required',
                'integer',
                'min:1',
                'max:99',
                Rule::unique('players')->where(function ($query) use ($request) {
                    return $query->where('team_id', $request->team_id);
                }),
            ],
            'position' => ['required', 'string', Rule::in(['Gardien', 'Défenseur', 'Milieu', 'Attaquant'])],
            'date_of_birth' => ['nullable', 'date'],
            'height' => ['nullable', 'integer', 'min:100', 'max:250'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('players/photos', 'public');
            $validatedData['photo_url'] = $path;
        }

        Player::create([
            'team_id' => $validatedData['team_id'],
            'last_name' => $validatedData['last_name'],
            'first_name' => $validatedData['first_name'],
            'jersey_number' => $validatedData['jersey_number'],
            'position' => $validatedData['position'],
            'date_of_birth' => $validatedData['date_of_birth'] ?? null,
            'height' => $validatedData['height'] ?? null,
            'photo_url' => $validatedData['photo_url'] ?? null,
        ]);

        return redirect()->route('player.register.create')->with('success', 'Votre inscription a été enregistrée avec succès ! Merci de votre participation.');
    }
}