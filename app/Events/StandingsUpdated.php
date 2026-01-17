<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StandingsUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Le groupe dont les classements ont été mis à jour (ou null pour tous les groupes)
     *
     * @var string|null
     */
    public $group;

    /**
     * Créer un nouvel événement
     *
     * @param string|null $group
     */
    public function __construct($group = null)
    {
        $this->group = $group;
    }

    /**
     * Obtenir le nom du canal pour l'événement
     *
     * @return string
     */
    public function broadcastOn()
    {
        return ['standings-updated'];
    }

    /**
     * Obtenir le nom de l'événement pour la diffusion
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'standings.updated';
    }

    /**
     * Obtenir les données à diffuser
     *
     * @return array
     */
    public function broadcastWith()
    {
        return ['group' => $this->group];
    }
}