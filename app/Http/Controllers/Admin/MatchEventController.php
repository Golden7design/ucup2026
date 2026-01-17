<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MatchEvent;
use Illuminate\Http\Request;

class MatchEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Cette méthode est exclue par la route, mais nous la gardons pour référence
        $events = MatchEvent::with(['match', 'player', 'team'])->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.match-events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.match-events.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'match_id' => 'required|exists:matches,id',
            'team_id' => 'required|exists:teams,id',
            'player_id' => 'nullable|exists:players,id',
            'event_type' => 'required|string|in:goal,yellow_card,red_card,substitution_in',
            'minute' => 'required|integer|min:1',
            'assist_player_id' => 'nullable|exists:players,id',
            'out_player_id' => 'nullable|exists:players,id',
        ]);

        $event = MatchEvent::create($validated);
        
        return redirect()->back()->with('success', 'Événement créé avec succès!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MatchEvent $matchEvent)
    {
        return view('admin.match-events.show', compact('matchEvent'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MatchEvent $matchEvent)
    {
        return view('admin.match-events.edit', compact('matchEvent'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MatchEvent $matchEvent)
    {
        $validated = $request->validate([
            'match_id' => 'required|exists:matches,id',
            'team_id' => 'required|exists:teams,id',
            'player_id' => 'nullable|exists:players,id',
            'event_type' => 'required|string|in:goal,yellow_card,red_card,substitution_in',
            'minute' => 'required|integer|min:1',
            'assist_player_id' => 'nullable|exists:players,id',
            'out_player_id' => 'nullable|exists:players,id',
        ]);

        $matchEvent->update($validated);
        
        return redirect()->back()->with('success', 'Événement mis à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MatchEvent $matchEvent)
    {
        $matchEvent->delete();
        
        return redirect()->back()->with('success', 'Événement supprimé avec succès!');
    }
}