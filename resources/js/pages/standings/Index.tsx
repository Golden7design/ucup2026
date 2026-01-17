import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
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
    PieChart,
    Pie,
    Cell,
} from 'recharts';
import { useEffect, useRef } from 'react';
import gsap from 'gsap';
import { Trophy, TrendingUp, Target } from 'lucide-react';

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

interface Props {
    standings: Standing[];
    breadcrumbs: BreadcrumbItem[];
}

const COLORS = [
    '#3b82f6',
    '#ef4444',
    '#10b981',
    '#f59e0b',
    '#8b5cf6',
    '#ec4899',
    '#06b6d4',
    '#f97316',
];

export default function StandingsPage({
    standings = [],
    breadcrumbs,
}: Props) {
    const containerRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (!containerRef.current) return;

        gsap.fromTo(
            '.standing-row',
            { opacity: 0, x: -20 },
            {
                opacity: 1,
                x: 0,
                duration: 0.5,
                stagger: 0.05,
                ease: 'power2.out',
            }
        );

        gsap.fromTo(
            '.chart-card',
            { opacity: 0, y: 20 },
            {
                opacity: 1,
                y: 0,
                duration: 0.6,
                stagger: 0.15,
                ease: 'power2.out',
                delay: 0.3,
            }
        );
    }, [standings]);

    // Données pour le graphique des points
    const pointsData = standings.map((s) => ({
        name: s.team.name.substring(0, 10),
        points: s.points,
        goals_for: s.goals_for,
        goals_against: s.goals_against,
    }));

    // Données pour le graphique des résultats (top 5)
    const topTeams = standings.slice(0, 5);
    const resultsData = topTeams.map((s) => ({
        name: s.team.name.substring(0, 15),
        Victoires: s.won,
        Nuls: s.drawn,
        Défaites: s.lost,
    }));

    // Données pour le graphique des différences de buts
    const goalDiffData = standings.map((s) => ({
        name: s.team.name.substring(0, 10),
        'Pour': s.goals_for,
        'Contre': s.goals_against,
        'Différence': s.goal_difference,
    }));

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Classement" />
            <div ref={containerRef} className="space-y-6 p-4">
                {/* En-tête */}
                <div className="flex flex-col items-start justify-between gap-4 md:flex-row md:items-center">
                    <div>
                        <h1 className="flex items-center gap-2 text-3xl font-bold">
                            <Trophy className="h-8 w-8 text-yellow-500" />
                            Classement du tournoi
                        </h1>
                        <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            {standings.length} équipes
                        </p>
                    </div>
                </div>

                {/* Tableau principal */}
                <Card className="chart-card">
                    <CardHeader>
                        <CardTitle>Classement général</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="overflow-x-auto">
                            <table className="w-full">
                                <thead>
                                    <tr className="border-b-2 border-gray-200 dark:border-gray-700">
                                        <th className="px-4 py-3 text-left font-semibold">
                                            Pos
                                        </th>
                                        <th className="px-4 py-3 text-left font-semibold">
                                            Équipe
                                        </th>
                                        <th className="px-4 py-3 text-center font-semibold">
                                            J
                                        </th>
                                        <th className="px-4 py-3 text-center font-semibold">
                                            G
                                        </th>
                                        <th className="px-4 py-3 text-center font-semibold">
                                            N
                                        </th>
                                        <th className="px-4 py-3 text-center font-semibold">
                                            P
                                        </th>
                                        <th className="px-4 py-3 text-center font-semibold">
                                            BP
                                        </th>
                                        <th className="px-4 py-3 text-center font-semibold">
                                            BC
                                        </th>
                                        <th className="px-4 py-3 text-center font-semibold">
                                            DB
                                        </th>
                                        <th className="px-4 py-3 text-center font-semibold">
                                            PTS
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {standings.map((stand, idx) => (
                                        <tr
                                            key={stand.id}
                                            className="standing-row border-b border-gray-100 hover:bg-gray-50 dark:border-gray-800 dark:hover:bg-gray-900"
                                        >
                                            <td className="px-4 py-3">
                                                <div className="flex h-8 w-8 items-center justify-center rounded-full bg-gradient-to-r from-blue-500 to-blue-600 font-bold text-white">
                                                    {idx + 1}
                                                </div>
                                            </td>
                                            <td className="px-4 py-3 font-medium">
                                                <div className="flex items-center gap-2">
                                                    {stand.team.logo && (
                                                        <img
                                                            src={
                                                                stand.team
                                                                    .logo
                                                            }
                                                            alt={
                                                                stand.team
                                                                    .name
                                                            }
                                                            className="h-6 w-6 rounded-full"
                                                        />
                                                    )}
                                                    {stand.team.name}
                                                </div>
                                            </td>
                                            <td className="px-4 py-3 text-center">
                                                {stand.played}
                                            </td>
                                            <td className="px-4 py-3 text-center text-green-600 dark:text-green-400">
                                                <span className="font-semibold">
                                                    {stand.won}
                                                </span>
                                            </td>
                                            <td className="px-4 py-3 text-center text-yellow-600 dark:text-yellow-400">
                                                <span className="font-semibold">
                                                    {stand.drawn}
                                                </span>
                                            </td>
                                            <td className="px-4 py-3 text-center text-red-600 dark:text-red-400">
                                                <span className="font-semibold">
                                                    {stand.lost}
                                                </span>
                                            </td>
                                            <td className="px-4 py-3 text-center font-medium">
                                                {stand.goals_for}
                                            </td>
                                            <td className="px-4 py-3 text-center font-medium">
                                                {stand.goals_against}
                                            </td>
                                            <td className="px-4 py-3 text-center font-medium">
                                                <span
                                                    className={
                                                        stand.goal_difference >=
                                                        0
                                                            ? 'text-green-600 dark:text-green-400'
                                                            : 'text-red-600 dark:text-red-400'
                                                    }
                                                >
                                                    {stand.goal_difference > 0
                                                        ? '+'
                                                        : ''}
                                                    {stand.goal_difference}
                                                </span>
                                            </td>
                                            <td className="px-4 py-3 text-center">
                                                <div className="flex h-8 w-8 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-600 dark:bg-blue-900 dark:text-blue-300">
                                                    {stand.points}
                                                </div>
                                            </td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </CardContent>
                </Card>

                {/* Graphiques */}
                <div className="grid gap-6 lg:grid-cols-2">
                    {/* Graphique des points */}
                    <Card className="chart-card">
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                <TrendingUp className="h-5 w-5 text-blue-500" />
                                Points par équipe
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <ResponsiveContainer
                                width="100%"
                                height={300}
                            >
                                <BarChart
                                    data={pointsData}
                                    margin={{
                                        top: 20,
                                        right: 30,
                                        left: 0,
                                        bottom: 60,
                                    }}
                                >
                                    <CartesianGrid strokeDasharray="3 3" />
                                    <XAxis
                                        dataKey="name"
                                        angle={-45}
                                        textAnchor="end"
                                        height={100}
                                    />
                                    <YAxis />
                                    <Tooltip />
                                    <Bar
                                        dataKey="points"
                                        fill="#3b82f6"
                                        radius={[8, 8, 0, 0]}
                                    />
                                </BarChart>
                            </ResponsiveContainer>
                        </CardContent>
                    </Card>

                    {/* Graphique des résultats (top 5) */}
                    <Card className="chart-card">
                        <CardHeader>
                            <CardTitle className="flex items-center gap-2">
                                <Target className="h-5 w-5 text-red-500" />
                                Résultats des top 5
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <ResponsiveContainer
                                width="100%"
                                height={300}
                            >
                                <BarChart
                                    data={resultsData}
                                    margin={{
                                        top: 20,
                                        right: 30,
                                        left: 0,
                                        bottom: 60,
                                    }}
                                >
                                    <CartesianGrid strokeDasharray="3 3" />
                                    <XAxis
                                        dataKey="name"
                                        angle={-45}
                                        textAnchor="end"
                                        height={100}
                                    />
                                    <YAxis />
                                    <Tooltip />
                                    <Legend />
                                    <Bar dataKey="Victoires" fill="#10b981" />
                                    <Bar dataKey="Nuls" fill="#f59e0b" />
                                    <Bar dataKey="Défaites" fill="#ef4444" />
                                </BarChart>
                            </ResponsiveContainer>
                        </CardContent>
                    </Card>

                    {/* Graphique des différences de buts */}
                    <Card className="chart-card lg:col-span-2">
                        <CardHeader>
                            <CardTitle>Buts marqués vs encaissés</CardTitle>
                        </CardHeader>
                        <CardContent>
                            <ResponsiveContainer
                                width="100%"
                                height={300}
                            >
                                <LineChart
                                    data={goalDiffData}
                                    margin={{
                                        top: 20,
                                        right: 30,
                                        left: 0,
                                        bottom: 60,
                                    }}
                                >
                                    <CartesianGrid strokeDasharray="3 3" />
                                    <XAxis
                                        dataKey="name"
                                        angle={-45}
                                        textAnchor="end"
                                        height={100}
                                    />
                                    <YAxis />
                                    <Tooltip />
                                    <Legend />
                                    <Line
                                        type="monotone"
                                        dataKey="Pour"
                                        stroke="#10b981"
                                        strokeWidth={2}
                                        dot={{ r: 4 }}
                                    />
                                    <Line
                                        type="monotone"
                                        dataKey="Contre"
                                        stroke="#ef4444"
                                        strokeWidth={2}
                                        dot={{ r: 4 }}
                                    />
                                </LineChart>
                            </ResponsiveContainer>
                        </CardContent>
                    </Card>
                </div>

                {/* Stats récapitulatives */}
                <div className="grid gap-4 sm:grid-cols-4">
                    <Card className="chart-card">
                        <CardContent className="pt-6">
                            <div className="text-center">
                                <p className="text-3xl font-bold text-blue-600">
                                    {standings.length}
                                </p>
                                <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    Équipes
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                    <Card className="chart-card">
                        <CardContent className="pt-6">
                            <div className="text-center">
                                <p className="text-3xl font-bold text-green-600">
                                    {standings.reduce((sum, s) => sum + s.played, 0) / standings.length}
                                </p>
                                <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    Matchs par équipe (moy)
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                    <Card className="chart-card">
                        <CardContent className="pt-6">
                            <div className="text-center">
                                <p className="text-3xl font-bold text-red-600">
                                    {standings.reduce((sum, s) => sum + s.goals_for, 0)}
                                </p>
                                <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    Buts marqués (total)
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                    <Card className="chart-card">
                        <CardContent className="pt-6">
                            <div className="text-center">
                                <p className="text-3xl font-bold text-purple-600">
                                    {Math.round((standings.reduce((sum, s) => sum + s.goals_for, 0) / standings.reduce((sum, s) => sum + s.played, 0)) * 10) / 10}
                                </p>
                                <p className="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    Buts par match (moy)
                                </p>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
