<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use App\Models\University;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::with('university')->withCount('players')->paginate(20);
        return view('admin.teams.index', compact('teams'));
    }

    public function create()
    {
        $universities = University::all();
        return view('admin.teams.create', compact('universities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'name' => 'required|string|max:255',
            'coach' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'year' => 'required|integer|min:2020|max:2100',
        ]);

        Team::create($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Équipe créée avec succès!');
    }

    public function show(Team $team)
    {
        $team->load(['university', 'players', 'standing']);
        return view('admin.teams.show', compact('team'));
    }

    public function edit(Team $team)
    {
        $universities = University::all();
        return view('admin.teams.edit', compact('team', 'universities'));
    }

    public function update(Request $request, Team $team)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'name' => 'required|string|max:255',
            'coach' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'year' => 'required|integer|min:2020|max:2100',
        ]);

        $team->update($validated);

        return redirect()->route('admin.teams.index')
            ->with('success', 'Équipe mise à jour avec succès!');
    }

    public function destroy(Team $team)
    {
        $team->delete();

        return redirect()->route('admin.teams.index')
            ->with('success', 'Équipe supprimée avec succès!');
    }
}