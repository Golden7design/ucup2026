<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\University;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UniversityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $universities = University::withCount('teams')->paginate(10);
        return view('admin.universities.index', compact('universities'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.universities.create');
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) // Utilise la Request standard (pas de Form Request)
    {
        // Validation CORRECTE pour les Universités
        $request->validate([
            'name' => 'required|string|max:255|unique:universities,name',
            'short_name' => 'required|string|max:10|unique:universities,short_name',
            'colors' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('logo');
        
        $data['user_id'] = Auth::id(); 

        // Gère l'upload du logo
        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos/universities', 'public');
        }

        University::create($data);

        return redirect()->route('admin.universities.index')->with('success', 'Université créée avec succès.');
    }

    // ... (edit, update, destroy) ...
    public function edit(University $university)
    {
        return view('admin.universities.edit', compact('university'));
    }

    public function update(Request $request, University $university)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:universities,name,' . $university->id,
            'short_name' => 'required|string|max:10|unique:universities,short_name,' . $university->id,
            'colors' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->except('logo');

        if ($request->hasFile('logo')) {
            if ($university->logo) {
                Storage::disk('public')->delete($university->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos/universities', 'public');
        }

        $university->update($data);

        return redirect()->route('admin.universities.index')->with('success', 'Université mise à jour avec succès.');
    }

    public function destroy(University $university)
    {
        if ($university->logo) {
            Storage::disk('public')->delete($university->logo);
        }
        
        $university->delete();

        return redirect()->route('admin.universities.index')->with('success', 'Université supprimée avec succès.');
    }
}