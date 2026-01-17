import AppLayout from '@/layouts/app-layout';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Activity, TrendingUp, Users } from 'lucide-react';
import { useEffect, useRef } from 'react';
import gsap from 'gsap';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    },
];

interface Match {
    id: number;
    home_team_id: number;
    away_team_id: number;
    home_score: number;
    away_score: number;
    status: string;
    start_time: string;
    home_team: { name: string; logo: string | null };
    away_team: { name: string; logo: string | null };
}

interface Standing {
    id: number;
    team_id: number;
    team: { name: string; logo: string | null };
    played: number;
    won: number;
    drawn: number;
    lost: number;
    goals_for: number;
    goals_against: number;
    goal_difference: number;
    points: number;
}

interface PlayerStat {
    id: number;
    player_id: number;
    player: { name: string };
    goals: number;
    assists: number;
    cards: number;
}

interface Props {
    liveMatches?: Match[];
    standings?: Standing[];
    topScorers?: PlayerStat[];
    stats?: {
        totalMatches: number;
        liveMatches: number;
        teams: number;
        players: number;
    };
}

export default function Dashboard({
    liveMatches = [],
    standings = [],
    topScorers = [],
    stats = {
        totalMatches: 0,
        liveMatches: 0,
        teams: 0,
        players: 0,
    },
}: Props) {
    const containerRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!containerRef.current) return;

        // Animation des cartes statistiques
        gsap.fromTo(
            '.stat-card',
            { opacity: 0, y: 20 },
            {
                opacity: 1,
                y: 0,
                duration: 0.6,
                stagger: 0.1,
                ease: 'power2.out',
            }
        );

        // Animation du contenu principal
        gsap.fromTo(
            '.content-section',
            { opacity: 0, y: 30 },
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                stagger: 0.2,
                ease: 'power2.out',
                delay: 0.3,
            }
        );
    }, []);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div ref={containerRef} className="flex flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                {/* Statistiques principales */}
                <div className="grid auto-rows-min gap-4 md:grid-cols-4">
                    <div className="stat-card relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-gradient-to-br from-blue-50 to-blue-100 p-4 dark:border-sidebar-border dark:from-blue-950 dark:to-blue-900">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-xs font-medium text-gray-600 dark:text-gray-400">
                                    Matchs totaux
                                </p>
                                <p className="mt-2 text-2xl font-bold">
                                    {stats.totalMatches}
                                </p>
                            </div>
                            <Activity className="h-8 w-8 text-blue-500" />
                        </div>
                    </div>

                    <div className="stat-card relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-gradient-to-br from-red-50 to-red-100 p-4 dark:border-sidebar-border dark:from-red-950 dark:to-red-900">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-xs font-medium text-gray-600 dark:text-gray-400">
                                    En direct
                                </p>
                                <div className="mt-2 flex items-center gap-2">
                                    <div className="h-2 w-2 animate-pulse rounded-full bg-red-500" />
                                    <p className="text-2xl font-bold">
                                        {stats.liveMatches}
                                    </p>
                                </div>
                            </div>
                            <Activity className="h-8 w-8 text-red-500" />
                        </div>
                    </div>

                    <div className="stat-card relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-gradient-to-br from-green-50 to-green-100 p-4 dark:border-sidebar-border dark:from-green-950 dark:to-green-900">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-xs font-medium text-gray-600 dark:text-gray-400">
                                    Équipes
                                </p>
                                <p className="mt-2 text-2xl font-bold">
                                    {stats.teams}
                                </p>
                            </div>
                            <Users className="h-8 w-8 text-green-500" />
                        </div>
                    </div>

                    <div className="stat-card relative overflow-hidden rounded-xl border border-sidebar-border/70 bg-gradient-to-br from-purple-50 to-purple-100 p-4 dark:border-sidebar-border dark:from-purple-950 dark:to-purple-900">
                        <div className="flex items-center justify-between">
                            <div>
                                <p className="text-xs font-medium text-gray-600 dark:text-gray-400">
                                    Joueurs
                                </p>
                                <p className="mt-2 text-2xl font-bold">
                                    {stats.players}
                                </p>
                            </div>
                            <TrendingUp className="h-8 w-8 text-purple-500" />
                        </div>
                    </div>
                </div>

                {/* Matchs en direct */}
                {liveMatches.length > 0 && (
                    <div className="content-section relative overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                        <div className="mb-4 flex items-center justify-between">
                            <h2 className="flex items-center gap-2 text-lg font-semibold">
                                <div className="h-2 w-2 animate-pulse rounded-full bg-red-500" />
                                Matchs en direct
                            </h2>
                            <Link href={route('matches.live')} className="text-xs font-medium text-blue-600 hover:underline dark:text-blue-400">
                                Voir tous
                            </Link>
                        </div>
                        <div className="space-y-3">
                            {liveMatches.slice(0, 3).map((match) => (
                                <div
                                    key={match.id}
                                    className="flex flex-col gap-3 rounded-lg border border-sidebar-border/70 bg-gray-50 p-4 dark:border-sidebar-border dark:bg-gray-900"
                                >
                                    <div className="flex items-center justify-between">
                                        <div className="flex flex-1 items-center justify-between gap-4">
                                            <div className="flex flex-1 items-center gap-2">
                                                <div className="h-8 w-8 rounded-full bg-gray-300" />
                                                <span className="text-sm font-medium">
                                                    {match.home_team?.name}
                                                </span>
                                            </div>
                                            <div className="flex items-center gap-2 rounded-lg bg-white px-3 py-1 dark:bg-gray-800">
                                                <span className="text-lg font-bold">
                                                    {match.home_score}
                                                </span>
                                                <span className="text-gray-500">-</span>
                                                <span className="text-lg font-bold">
                                                    {match.away_score}
                                                </span>
                                            </div>
                                            <div className="flex flex-1 items-center justify-end gap-2">
                                                <span className="text-sm font-medium">
                                                    {match.away_team?.name}
                                                </span>
                                                <div className="h-8 w-8 rounded-full bg-gray-300" />
                                            </div>
                                        </div>
                                    </div>
                                    <div className="flex items-center justify-between text-xs">
                                        <span className="text-gray-600 dark:text-gray-400">
                                            {new Date(match.start_time).toLocaleTimeString()}
                                        </span>
                                        <Link href={route('matches.show', match.id)} className="font-medium text-blue-600 hover:underline dark:text-blue-400">
                                            Détails →
                                        </Link>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </div>
                )}

                {/* Classement et Top Scoreurs */}
                <div className="content-section grid gap-4 lg:grid-cols-3">
                    {/* Classement */}
                    <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border lg:col-span-2">
                        <div className="mb-4 flex items-center justify-between">
                            <h2 className="text-lg font-semibold">Classement</h2>
                            <Link href={route('standings.index')} className="text-xs font-medium text-blue-600 hover:underline dark:text-blue-400">
                                Détails complets
                            </Link>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm">
                                <thead>
                                    <tr className="border-b border-sidebar-border/70 dark:border-sidebar-border">
                                        <th className="py-2 text-left font-medium">
                                            Pos
                                        </th>
                                        <th className="py-2 text-left font-medium">
                                            Équipe
                                        </th>
                                        <th className="py-2 text-center font-medium">
                                            J
                                        </th>
                                        <th className="py-2 text-center font-medium">
                                            G
                                        </th>
                                        <th className="py-2 text-center font-medium">
                                            N
                                        </th>
                                        <th className="py-2 text-center font-medium">
                                            P
                                        </th>
                                        <th className="py-2 text-center font-medium">
                                            Pts
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {standings.slice(0, 5).map((stand, idx) => (
                                        <tr
                                            key={stand.id}
                                            className="border-b border-sidebar-border/70 hover:bg-gray-50 dark:border-sidebar-border dark:hover:bg-gray-900"
                                        >
                                            <td className="py-2 font-bold text-gray-500">
                                                {idx + 1}
                                            </td>
                                            <td className="py-2 font-medium">
                                                {stand.team.name}
                                            </td>
                                            <td className="py-2 text-center">
                                                {stand.played}
                                            </td>
                                            <td className="py-2 text-center text-green-600 dark:text-green-400">
                                                {stand.won}
                                            </td>
                                            <td className="py-2 text-center text-yellow-600 dark:text-yellow-400">
                                                {stand.drawn}
                                            </td>
                                            <td className="py-2 text-center text-red-600 dark:text-red-400">
                                                {stand.lost}
                                            </td>
                                            <td className="py-2 text-center font-bold">
                                                {stand.points}
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {/* Top Scoreurs */}
                    <div className="relative overflow-hidden rounded-xl border border-sidebar-border/70 p-4 dark:border-sidebar-border">
                        <h2 className="mb-4 text-lg font-semibold">Top Buteurs</h2>
                        <div className="space-y-2">
                            {topScorers.slice(0, 8).map((scorer, idx) => (
                                <div
                                    key={scorer.id}
                                    className="flex items-center justify-between rounded-lg bg-gray-50 p-2 dark:bg-gray-900"
                                >
                                    <div className="flex items-center gap-2">
                                        <div className="flex h-5 w-5 items-center justify-center rounded-full bg-yellow-400 text-xs font-bold">
                                            {idx + 1}
                                        </div>
                                        <span className="text-xs font-medium">
                                            {scorer.player.name}
                                        </span>
                                    </div>
                                    <span className="text-xs font-bold text-yellow-600 dark:text-yellow-400">
                                        {scorer.goals}
                                    </span>
                                </div>
                            ))}
                        </div>
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
