import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import {
    LineChart,
    Line,
    BarChart,
    Bar,
    XAxis,
    YAxis,
    CartesianGrid,
    Tooltip,
    Legend,
    ResponsiveContainer,
    RadarChart,
    PolarGrid,
    PolarAngleAxis,
    PolarRadiusAxis,
    Radar,
} from 'recharts';
import { Badge } from '@/components/ui/badge';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Button } from '@/components/ui/button';
import { useEffect, useRef } from 'react';
import gsap from 'gsap';
import { ChevronLeft, Trophy, Target, Heart, AlertCircle } from 'lucide-react';

interface Player {
    id: number;
    name: string;
    number: number;
    position: string;
    university: { name: string };
    team: { name: string };
    photo: string | null;
}

interface Stats {
    goals: number;
    assists: number;
    yellow_cards: number;
    red_cards: number;
    matches_played: number;
    minutes_played: number;
    passes_completed: number;
    pass_accuracy: number;
    tackles: number;
    interceptions: number;
    fouls_committed: number;
    fouls_suffered: number;
    shots_on_target: number;
    dribbles: number;
}

interface MatchPerformance {
    match_id: number;
    match_date: string;
    opponent: string;
    goals: number;
    assists: number;
    minutes: number;
    rating: number;
}

interface Props {
    player: Player;
    stats: Stats;
    performanceHistory: MatchPerformance[];
    breadcrumbs: BreadcrumbItem[];
}

export default function PlayerShow({
    player,
    stats,
    performanceHistory = [],
    breadcrumbs,
}: Props) {
    const containerRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!containerRef.current) return;

        // Animation du header
        gsap.fromTo(
            '.player-header',
            { opacity: 0, y: -20 },
            { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out' }
        );

        // Animation des cartes stats
        gsap.fromTo(
            '.stat-box',
            { opacity: 0, scale: 0.9 },
            {
                opacity: 1,
                scale: 1,
                duration: 0.5,
                stagger: 0.08,
                ease: 'back.out',
            }
        );

        // Animation du contenu des tabs
        gsap.fromTo(
            '.tab-content',
            { opacity: 0 },
            { opacity: 1, duration: 0.4, delay: 0.2 }
        );
    }, []);

    const radarData = [
        {
            name: 'Buts',
            value: Math.min((stats.goals / 20) * 100, 100),
        },
        {
            name: 'Passes',
            value: Math.min((stats.pass_accuracy / 100) * 100, 100),
        },
        {
            name: 'Défense',
            value: Math.min(((stats.tackles + stats.interceptions) / 50) * 100, 100),
        },
        {
            name: 'Dribbles',
            value: Math.min((stats.dribbles / 20) * 100, 100),
        },
        {
            name: 'Tirs',
            value: Math.min((stats.shots_on_target / 10) * 100, 100),
        },
        {
            name: 'Discipline',
            value: Math.max(
                100 - (stats.yellow_cards * 10 + stats.red_cards * 50),
                0
            ),
        },
    ];

    const performanceChartData = performanceHistory.slice(-10).map((perf) => ({
        date: new Date(perf.match_date).toLocaleDateString('fr-FR', {
            month: 'short',
            day: 'numeric',
        }),
        goals: perf.goals,
        assists: perf.assists,
        rating: perf.rating,
        minutes: perf.minutes,
    }));

    const positionBadgeColor: Record<string, string> = {
        GK: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100',
        DEF: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100',
        MID: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100',
        FWD: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-100',
    };

    function route(arg0: string): string | import("@inertiajs/core").UrlMethodPair | undefined {
        throw new Error('Function not implemented.');
    }

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={`${player.name} - Profil`} />
            <div ref={containerRef} className="space-y-6 p-4">
                {/* En-tête du joueur */}
                <div className="player-header rounded-xl border border-sidebar-border/70 bg-gradient-to-r from-blue-600 to-blue-800 p-6 text-white dark:border-sidebar-border">
                    <div className="mb-4 flex items-start justify-between">
                        <Link href={route('players.index')}>
                            <Button
                                variant="ghost"
                                size="icon"
                                className="text-white hover:bg-white/20"
                            >
                                <ChevronLeft className="h-4 w-4" />
                            </Button>
                        </Link>
                    </div>

                    <div className="grid gap-6 md:grid-cols-3">
                        {/* Photo et infos basiques */}
                        <div className="md:col-span-1">
                            <div className="aspect-square overflow-hidden rounded-lg border-4 border-white bg-gray-300">
                                {player.photo ? (
                                    <img
                                        src={player.photo}
                                        alt={player.name}
                                        className="h-full w-full object-cover"
                                    />
                                ) : (
                                    <div className="flex items-center justify-center bg-gray-400">
                                        <span className="text-4xl font-bold">
                                            {player.number}
                                        </span>
                                    </div>
                                )}
                            </div>
                            <div className="mt-4">
                                <p className="text-3xl font-bold">{player.name}</p>
                                <p className="text-lg opacity-90">
                                    N° {player.number}
                                </p>
                                <Badge
                                    className={`mt-2 ${
                                        positionBadgeColor[
                                            player.position.toUpperCase()
                                        ] || 'bg-gray-100'
                                    }`}
                                >
                                    {player.position}
                                </Badge>
                            </div>
                        </div>

                        {/* Stats principales */}
                        <div className="md:col-span-2">
                            <div className="mb-4">
                                <p className="text-sm opacity-80">Équipe</p>
                                <p className="text-xl font-semibold">
                                    {player.team.name}
                                </p>
                            </div>
                            <div className="mb-4">
                                <p className="text-sm opacity-80">Université</p>
                                <p className="text-xl font-semibold">
                                    {player.university.name}
                                </p>
                            </div>

                            <div className="grid gap-4 sm:grid-cols-4">
                                <div className="rounded-lg bg-white/20 p-3">
                                    <p className="text-xs opacity-80">Buts</p>
                                    <p className="text-2xl font-bold">
                                        {stats.goals}
                                    </p>
                                </div>
                                <div className="rounded-lg bg-white/20 p-3">
                                    <p className="text-xs opacity-80">Passes</p>
                                    <p className="text-2xl font-bold">
                                        {stats.assists}
                                    </p>
                                </div>
                                <div className="rounded-lg bg-white/20 p-3">
                                    <p className="text-xs opacity-80">Matchs</p>
                                    <p className="text-2xl font-bold">
                                        {stats.matches_played}
                                    </p>
                                </div>
                                <div className="rounded-lg bg-white/20 p-3">
                                    <p className="text-xs opacity-80">Cartons</p>
                                    <p className="text-2xl font-bold">
                                        {stats.yellow_cards}J{' '}
                                        {stats.red_cards}R
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Statistiques détaillées */}
                <Tabs defaultValue="overview" className="w-full">
                    <TabsList className="grid w-full grid-cols-3">
                        <TabsTrigger value="overview">Vue d'ensemble</TabsTrigger>
                        <TabsTrigger value="stats">Statistiques</TabsTrigger>
                        <TabsTrigger value="performance">
                            Performances
                        </TabsTrigger>
                    </TabsList>

                    {/* Onglet Vue d'ensemble */}
                    <TabsContent value="overview" className="tab-content space-y-4">
                        <div className="grid gap-4 sm:grid-cols-2">
                            {/* Graphique Radar */}
                            <Card>
                                <CardHeader>
                                    <CardTitle>Profil du joueur</CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <ResponsiveContainer
                                        width="100%"
                                        height={300}
                                    >
                                        <RadarChart data={radarData}>
                                            <PolarGrid stroke="#ccc" />
                                            <PolarAngleAxis
                                                dataKey="name"
                                                tick={{ fontSize: 12 }}
                                            />
                                            <PolarRadiusAxis />
                                            <Radar
                                                name="Performance"
                                                dataKey="value"
                                                stroke="#3b82f6"
                                                fill="#3b82f6"
                                                fillOpacity={0.6}
                                            />
                                        </RadarChart>
                                    </ResponsiveContainer>
                                </CardContent>
                            </Card>

                            {/* Cartes de statistiques clés */}
                            <div className="space-y-3">
                                <Card className="stat-box">
                                    <CardHeader className="pb-2">
                                        <CardTitle className="flex items-center gap-2 text-sm">
                                            <Trophy className="h-4 w-4 text-yellow-500" />
                                            Efficacité offensive
                                        </CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        <div className="grid grid-cols-2 gap-2">
                                            <div>
                                                <p className="text-xs text-gray-600 dark:text-gray-400">
                                                    Buts
                                                </p>
                                                <p className="text-2xl font-bold">
                                                    {stats.goals}
                                                </p>
                                            </div>
                                            <div>
                                                <p className="text-xs text-gray-600 dark:text-gray-400">
                                                    Tirs cadrés
                                                </p>
                                                <p className="text-2xl font-bold">
                                                    {stats.shots_on_target}
                                                </p>
                                            </div>
                                        </div>
                                    </CardContent>
                                </Card>

                                <Card className="stat-box">
                                    <CardHeader className="pb-2">
                                        <CardTitle className="flex items-center gap-2 text-sm">
                                            <Target className="h-4 w-4 text-blue-500" />
                                            Passes et création
                                        </CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        <div className="grid grid-cols-2 gap-2">
                                            <div>
                                                <p className="text-xs text-gray-600 dark:text-gray-400">
                                                    Passes réussies
                                                </p>
                                                <p className="text-2xl font-bold">
                                                    {stats.passes_completed}
                                                </p>
                                            </div>
                                            <div>
                                                <p className="text-xs text-gray-600 dark:text-gray-400">
                                                    Précision
                                                </p>
                                                <p className="text-2xl font-bold">
                                                    {stats.pass_accuracy}%
                                                </p>
                                            </div>
                                        </div>
                                    </CardContent>
                                </Card>

                                <Card className="stat-box">
                                    <CardHeader className="pb-2">
                                        <CardTitle className="flex items-center gap-2 text-sm">
                                            <Heart className="h-4 w-4 text-red-500" />
                                            Défense
                                        </CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        <div className="grid grid-cols-2 gap-2">
                                            <div>
                                                <p className="text-xs text-gray-600 dark:text-gray-400">
                                                    Tacles
                                                </p>
                                                <p className="text-2xl font-bold">
                                                    {stats.tackles}
                                                </p>
                                            </div>
                                            <div>
                                                <p className="text-xs text-gray-600 dark:text-gray-400">
                                                    Interceptions
                                                </p>
                                                <p className="text-2xl font-bold">
                                                    {stats.interceptions}
                                                </p>
                                            </div>
                                        </div>
                                    </CardContent>
                                </Card>

                                <Card className="stat-box">
                                    <CardHeader className="pb-2">
                                        <CardTitle className="flex items-center gap-2 text-sm">
                                            <AlertCircle className="h-4 w-4 text-orange-500" />
                                            Discipline
                                        </CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        <div className="grid grid-cols-2 gap-2">
                                            <div>
                                                <p className="text-xs text-gray-600 dark:text-gray-400">
                                                    Cartons jaunes
                                                </p>
                                                <p className="text-2xl font-bold text-yellow-600">
                                                    {stats.yellow_cards}
                                                </p>
                                            </div>
                                            <div>
                                                <p className="text-xs text-gray-600 dark:text-gray-400">
                                                    Cartons rouges
                                                </p>
                                                <p className="text-2xl font-bold text-red-600">
                                                    {stats.red_cards}
                                                </p>
                                            </div>
                                        </div>
                                    </CardContent>
                                </Card>
                            </div>
                        </div>
                    </TabsContent>

                    {/* Onglet Statistiques détaillées */}
                    <TabsContent
                        value="stats"
                        className="tab-content space-y-4"
                    >
                        <div className="grid gap-4 sm:grid-cols-3">
                            {[
                                {
                                    label: 'Minutes jouées',
                                    value: stats.minutes_played,
                                    icon: '⏱️',
                                },
                                {
                                    label: 'Dribbles réussis',
                                    value: stats.dribbles,
                                    icon: '⚽',
                                },
                                {
                                    label: 'Fautes commises',
                                    value: stats.fouls_committed,
                                    icon: '⚠️',
                                },
                                {
                                    label: 'Fautes subies',
                                    value: stats.fouls_suffered,
                                    icon: '🛡️',
                                },
                                {
                                    label: 'Tirs au but',
                                    value: stats.shots_on_target,
                                    icon: '🎯',
                                },
                                {
                                    label: 'Précision des passes',
                                    value: `${stats.pass_accuracy}%`,
                                    icon: '🎯',
                                },
                            ].map((stat, idx) => (
                                <Card key={idx} className="stat-box">
                                    <CardContent className="pt-6">
                                        <div className="text-center">
                                            <p className="text-3xl">
                                                {stat.icon}
                                            </p>
                                            <p className="mt-2 text-xs text-gray-600 dark:text-gray-400">
                                                {stat.label}
                                            </p>
                                            <p className="mt-1 text-2xl font-bold">
                                                {stat.value}
                                            </p>
                                        </div>
                                    </CardContent>
                                </Card>
                            ))}
                        </div>
                    </TabsContent>

                    {/* Onglet Performances */}
                    <TabsContent
                        value="performance"
                        className="tab-content space-y-4"
                    >
                        {performanceChartData.length > 0 ? (
                            <Card>
                                <CardHeader>
                                    <CardTitle>
                                        Buts et passes par match
                                    </CardTitle>
                                </CardHeader>
                                <CardContent>
                                    <ResponsiveContainer
                                        width="100%"
                                        height={300}
                                    >
                                        <BarChart data={performanceChartData}>
                                            <CartesianGrid strokeDasharray="3 3" />
                                            <XAxis dataKey="date" />
                                            <YAxis />
                                            <Tooltip />
                                            <Legend />
                                            <Bar
                                                dataKey="goals"
                                                fill="#ef4444"
                                                name="Buts"
                                            />
                                            <Bar
                                                dataKey="assists"
                                                fill="#3b82f6"
                                                name="Passes"
                                            />
                                        </BarChart>
                                    </ResponsiveContainer>
                                </CardContent>
                            </Card>
                        ) : (
                            <Card>
                                <CardContent className="pt-6">
                                    <p className="text-center text-gray-600 dark:text-gray-400">
                                        Aucune donnée de performance
                                        disponible
                                    </p>
                                </CardContent>
                            </Card>
                        )}
                    </TabsContent>
                </Tabs>
            </div>
        </AppLayout>
    );
}
