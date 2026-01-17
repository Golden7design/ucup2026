import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { Card, CardContent } from '@/components/ui/card';
import { SearchFilter } from '@/components/SearchFilter';
import { Badge } from '@/components/ui/badge';
import { useEffect, useRef, useState } from 'react';
import gsap from 'gsap';
import { Link } from '@inertiajs/react';
import { Trophy } from 'lucide-react';

interface Player {
    id: number;
    name: string;
    number: number;
    position: string;
    photo: string | null;
    team_id: number;
    team: { name: string; logo: string | null };
}

interface Team {
    id: number;
    name: string;
}

interface PlayerWithStats extends Player {
    stats?: {
        goals: number;
        assists: number;
        matches_played: number;
    };
}

interface Props {
    players: PlayerWithStats[];
    teams: Team[];
    breadcrumbs: BreadcrumbItem[];
}

const positionBadgeColor: Record<string, string> = {
    GK: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100',
    DEF: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100',
    MID: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100',
    FWD: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100',
};

export default function PlayersIndex({
    players = [],
    teams = [],
    breadcrumbs,
}: Props) {
    const [filteredPlayers, setFilteredPlayers] = useState(players);
    const containerRef = useRef<HTMLDivElement>(null);

    const handleSearch = (filters: any) => {
        let filtered = players;

        if (filters.query) {
            const query = filters.query.toLowerCase();
            filtered = filtered.filter((p) =>
                p.name.toLowerCase().includes(query)
            );
        }

        if (filters.teamId) {
            filtered = filtered.filter((p) => p.team_id === filters.teamId);
        }

        setFilteredPlayers(filtered);
    };

    useEffect(() => {
        if (!containerRef.current) return;

        gsap.fromTo(
            '.player-card',
            { opacity: 0, scale: 0.95 },
            {
                opacity: 1,
                scale: 1,
                duration: 0.5,
                stagger: 0.06,
                ease: 'back.out',
            }
        );
    }, [filteredPlayers]);

    function route(arg0: string, id: number): string | import("@inertiajs/core").UrlMethodPair | undefined {
        throw new Error('Function not implemented.');
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Joueurs" />
            <div ref={containerRef} className="space-y-6 p-4">
                {/* En-tête */}
                <div>
                    <h1 className="text-3xl font-bold">Joueurs</h1>
                    <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {filteredPlayers.length} joueur(s) trouvé(s)
                    </p>
                </div>

                {/* Barre de recherche */}
                <SearchFilter
                    teams={teams}
                    onSearch={handleSearch}
                    placeholder="Rechercher par nom..."
                    showTeamFilter={true}
                    showStatusFilter={false}
                    showDateFilter={false}
                />

                {/* Grille des joueurs */}
                {filteredPlayers.length > 0 ? (
                    <div className="grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        {filteredPlayers.map((player) => (
                            <Link
                                key={player.id}
                                href={route('players.show', player.id)}
                            >
                                <Card className="player-card h-full cursor-pointer transition-all hover:shadow-lg hover:scale-105">
                                    <CardContent className="p-4">
                                        {/* Photo */}
                                        <div className="relative mb-3 aspect-square overflow-hidden rounded-lg bg-gray-300">
                                            {player.photo ? (
                                                <img
                                                    src={player.photo}
                                                    alt={player.name}
                                                    className="h-full w-full object-cover"
                                                />
                                            ) : (
                                                <div className="flex h-full items-center justify-center bg-gray-400">
                                                    <span className="text-3xl font-bold text-white">
                                                        {player.number}
                                                    </span>
                                                </div>
                                            )}
                                            {/* Badge position */}
                                            <div className="absolute right-2 top-2">
                                                <Badge
                                                    className={`${
                                                        positionBadgeColor[
                                                            player.position.toUpperCase()
                                                        ] ||
                                                        'bg-gray-100 text-gray-800'
                                                    }`}
                                                >
                                                    {player.position}
                                                </Badge>
                                            </div>
                                            {/* Numéro */}
                                            <div className="absolute bottom-2 left-2 flex h-8 w-8 items-center justify-center rounded-full bg-white font-bold text-blue-600 shadow-md">
                                                {player.number}
                                            </div>
                                        </div>

                                        {/* Infos */}
                                        <div className="space-y-2">
                                            <div>
                                                <h3 className="font-semibold text-gray-900 dark:text-white">
                                                    {player.name}
                                                </h3>
                                                <p className="text-xs text-gray-600 dark:text-gray-400">
                                                    {player.team.name}
                                                </p>
                                            </div>

                                            {/* Stats rapides */}
                                            {player.stats && (
                                                <div className="border-t border-gray-200 pt-2 dark:border-gray-700">
                                                    <div className="grid grid-cols-3 gap-2 text-center text-xs">
                                                        <div>
                                                            <p className="font-bold text-red-600">
                                                                {
                                                                    player
                                                                        .stats
                                                                        .goals
                                                                }
                                                            </p>
                                                            <p className="text-gray-600 dark:text-gray-400">
                                                                Buts
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p className="font-bold text-blue-600">
                                                                {
                                                                    player
                                                                        .stats
                                                                        .assists
                                                                }
                                                            </p>
                                                            <p className="text-gray-600 dark:text-gray-400">
                                                                Passes
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <p className="font-bold text-green-600">
                                                                {
                                                                    player
                                                                        .stats
                                                                        .matches_played
                                                                }
                                                            </p>
                                                            <p className="text-gray-600 dark:text-gray-400">
                                                                Matchs
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            )}
                                        </div>
                                    </CardContent>
                                </Card>
                            </Link>
                        ))}
                    </div>
                ) : (
                    <Card>
                        <CardContent className="flex h-48 items-center justify-center">
                            <p className="text-center text-gray-600 dark:text-gray-400">
                                Aucun joueur trouvé avec ces critères de
                                recherche.
                            </p>
                        </CardContent>
                    </Card>
                )}
            </div>
        </AppLayout>
    );
}
