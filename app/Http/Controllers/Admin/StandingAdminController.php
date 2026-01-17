<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\StandingService; // Importez votre service
use Illuminate\Http\Request;

class StandingAdminController extends Controller
{
    protected $standingService;

    public function __construct(StandingService $standingService)
    {
        $this->standingService = $standingService;
    }

    /**
     * Déclenche le recalcul complet du classement.
     */
    public function recalculate(Request $request)
    {
        try {
            // Appelle votre méthode existante dans le service
            $this->standingService->recalculateAllStandings();

            return redirect()->back()->with('success', 'Le classement a été recalculé avec succès.');

        } catch (\Exception $e) {
            \Log::error("Erreur lors du recalcul du classement : " . $e->getMessage());
            
            return redirect()->back()->with('error', 'Erreur lors du recalcul du classement : ' . $e->getMessage());
        }
    }
}