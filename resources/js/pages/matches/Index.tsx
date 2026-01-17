import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { SearchFilter } from '@/components/SearchFilter';
import { Badge } from '@/components/ui/badge';
import { useEffect, useRef, useState } from 'react';
import gsap from 'gsap';
import { Clock, MapPin } from 'lucide-react';

interface Match {
    id: number;
    team_a_id: number;
    team_b_id: number;
    score_a: number;
    score_b: number;
    status: string;
    started_at: string;
    location?: string;
    team_a: { name: string; logo: string | null };
    team_b: { name: string; logo: string | null };
}

interface Team {
    id: number;
    name: string;
}

interface Props {
    matches: Match[];
    teams: Team[];
    breadcrumbs: BreadcrumbItem[];
}

const statusBadgeColor: Record<string, string> = {
    scheduled: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100',
    live: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100',
    finished: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-100',
};

const statusLabel: Record<string, string> = {
    scheduled: 'Programmé',
    live: 'En direct',
    finished: 'Terminé',
};

export default function MatchesIndex({
    matches = [],
    teams = [],
    breadcrumbs,
}: Props) {
    const [filteredMatches, setFilteredMatches] = useState(matches);
    const containerRef = useRef<HTMLDivElement>(null);

    const handleSearch = (filters: any) => {
        let filtered = matches;

        if (filters.query) {
            const query = filters.query.toLowerCase();
            filtered = filtered.filter(
                (m) =>
                    m.team_a.name.toLowerCase().includes(query) ||
                    m.team_b.name.toLowerCase().includes(query)
            );
        }

        if (filters.teamId) {
            filtered = filtered.filter(
                (m) =>
                    m.team_a_id === filters.teamId ||
                    m.team_b_id === filters.teamId
            );
        }

        if (filters.status) {
            filtered = filtered.filter((m) => m.status === filters.status);
        }

        setFilteredMatches(filtered);
    };

    useEffect(() => {
        if (!containerRef.current) return;

        gsap.fromTo(
            '.match-card',
            { opacity: 0, y: 20 },
            {
                opacity: 1,
                y: 0,
                duration: 0.5,
                stagger: 0.08,
                ease: 'power2.out',
            }
        );
    }, [filteredMatches]);

    function route(arg0: string, id: number): string {
        throw new Error('Function not implemented.');
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Matchs" />
            <div ref={containerRef} className="space-y-6 p-4">
                {/* En-tête */}
                <div>
                    <h1 className="text-3xl font-bold">Matchs</h1>
                    <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {filteredMatches.length} match(s) trouvé(s)
                    </p>
                </div>

                {/* Barre de recherche */}
                <SearchFilter
                    teams={teams}
                    onSearch={handleSearch}
                    placeholder="Rechercher par équipe..."
                    showTeamFilter={true}
                    showStatusFilter={true}
                    showDateFilter={false}
                />

                {/* Liste des matchs */}
                {filteredMatches.length > 0 ? (
                    <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                        {filteredMatches.map((match) => (
                            <Card
                                key={match.id}
                                className="match-card cursor-pointer transition-all hover:shadow-lg"
                                onClick={() =>
                                    (window.location.href = route(
                                        'matches.show',
                                        match.id
                                    ))
                                }
                            >
                                <CardHeader className="pb-3">
                                    <div className="flex items-center justify-between">
                                        <Badge
                                            className={`${statusBadgeColor[match.status] || 'bg-gray-100'}`}
                                        >
                                            {statusLabel[match.status]}
                                        </Badge>
                                        {match.status === 'live' && (
                                            <div className="flex items-center gap-1">
                                                <div className="h-2 w-2 animate-pulse rounded-full bg-red-500" />
                                                <span className="text-xs font-semibold text-red-600 dark:text-red-400">
                                                    EN DIRECT
                                                </span>
                                            </div>
                                        )}
                                    </div>
                                </CardHeader>
                                <CardContent className="space-y-4">
                                    {/* Équipes et score */}
                                    <div className="space-y-3">
                                        {/* Équipe A */}
                                        <div className="flex items-center justify-between gap-2">
                                            <div className="flex flex-1 items-center gap-2">
                                                {match.team_a.logo && (
                                                    <img
                                                        src={
                                                            match.team_a.logo
                                                        }
                                                        alt={
                                                            match.team_a.name
                                                        }
                                                        className="h-8 w-8 rounded-full"
                                                    />
                                                )}
                                                <span className="truncate text-sm font-medium">
                                                    {match.team_a.name}
                                                </span>
                                            </div>
                                            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 font-bold text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                                                {match.score_a}
                                            </div>
                                        </div>

                                        {/* VS */}
                                        <div className="flex items-center justify-center">
                                            <span className="rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600 dark:bg-gray-800 dark:text-gray-400">
                                                VS
                                            </span>
                                        </div>

                                        {/* Équipe B */}
                                        <div className="flex items-center justify-between gap-2">
                                            <div className="flex flex-1 items-center gap-2">
                                                {match.team_b.logo && (
                                                    <img
                                                        src={
                                                            match.team_b.logo
                                                        }
                                                        alt={
                                                            match.team_b.name
                                                        }
                                                        className="h-8 w-8 rounded-full"
                                                    />
                                                )}
                                                <span className="truncate text-sm font-medium">
                                                    {match.team_b.name}
                                                </span>
                                            </div>
                                            <div className="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100 font-bold text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                                                {match.score_b}
                                            </div>
                                        </div>
                                    </div>

                                    {/* Infos supplémentaires */}
                                    <div className="space-y-2 border-t border-gray-200 pt-3 dark:border-gray-700">
                                        <div className="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                            <Clock className="h-4 w-4" />
                                            {new Date(
                                                match.started_at
                                            ).toLocaleString('fr-FR')}
                                        </div>
                                        {match.location && (
                                            <div className="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                                <MapPin className="h-4 w-4" />
                                                {match.location}
                                            </div>
                                        )}
                                    </div>
                                </CardContent>
                            </Card>
                        ))}
                    </div>
                ) : (
                    <Card>
                        <CardContent className="flex h-48 items-center justify-center">
                            <p className="text-center text-gray-600 dark:text-gray-400">
                                Aucun match trouvé avec ces critères de
                                recherche.
                            </p>
                        </CardContent>
                    </Card>
                )}
            </div>
        </AppLayout>
    );
}
