import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Search, X } from 'lucide-react';
import { useEffect, useState } from 'react';
import gsap from 'gsap';

interface Team {
    id: number;
    name: string;
}

interface SearchFilterProps {
    teams: Team[];
    onSearch: (filters: {
        query: string;
        teamId?: number;
        status?: string;
        dateFrom?: string;
        dateTo?: string;
    }) => void;
    placeholder?: string;
    showTeamFilter?: boolean;
    showStatusFilter?: boolean;
    showDateFilter?: boolean;
}

export function SearchFilter({
    teams = [],
    onSearch,
    placeholder = 'Rechercher...',
    showTeamFilter = true,
    showStatusFilter = true,
    showDateFilter = false,
}: SearchFilterProps) {
    const [query, setQuery] = useState('');
    const [teamId, setTeamId] = useState('');
    const [status, setStatus] = useState('');
    const [dateFrom, setDateFrom] = useState('');
    const [dateTo, setDateTo] = useState('');
    const [isOpen, setIsOpen] = useState(false);

    useEffect(() => {
        const timer = setTimeout(() => {
            onSearch({
                query,
                teamId: teamId ? parseInt(teamId) : undefined,
                status: status || undefined,
                dateFrom: dateFrom || undefined,
                dateTo: dateTo || undefined,
            });
        }, 300);

        return () => clearTimeout(timer);
    }, [query, teamId, status, dateFrom, dateTo, onSearch]);

    const handleReset = () => {
        setQuery('');
        setTeamId('');
        setStatus('');
        setDateFrom('');
        setDateTo('');
        setIsOpen(false);
    };

    const hasFilters =
        query || teamId || status || dateFrom || dateTo;

    return (
        <div className="space-y-3">
            {/* Barre de recherche principale */}
            <div className="flex gap-2">
                <div className="relative flex-1">
                    <Search className="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400" />
                    <Input
                        type="text"
                        placeholder={placeholder}
                        value={query}
                        onChange={(e) => setQuery(e.target.value)}
                        className="pl-10"
                    />
                </div>
                <Button
                    variant="outline"
                    onClick={() => setIsOpen(!isOpen)}
                    className="gap-2"
                >
                    Filtres {hasFilters && <span className="h-2 w-2 rounded-full bg-blue-500" />}
                </Button>
                {hasFilters && (
                    <Button
                        variant="ghost"
                        size="sm"
                        onClick={handleReset}
                        className="gap-1"
                    >
                        <X className="h-4 w-4" />
                        Réinitialiser
                    </Button>
                )}
            </div>

            {/* Panneau de filtres */}
            {isOpen && (
                <div className="space-y-3 rounded-lg border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900">
                    <div className="grid gap-3 sm:grid-cols-2">
                        {/* Filtre Équipe */}
                        {showTeamFilter && (
                            <div>
                                <label className="mb-2 block text-sm font-medium">
                                    Équipe
                                </label>
                                <Select value={teamId} onValueChange={setTeamId}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Toutes les équipes" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="">
                                            Toutes les équipes
                                        </SelectItem>
                                        {teams.map((team) => (
                                            <SelectItem
                                                key={team.id}
                                                value={team.id.toString()}
                                            >
                                                {team.name}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                            </div>
                        )}

                        {/* Filtre Statut */}
                        {showStatusFilter && (
                            <div>
                                <label className="mb-2 block text-sm font-medium">
                                    Statut
                                </label>
                                <Select value={status} onValueChange={setStatus}>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Tous les statuts" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem value="">
                                            Tous les statuts
                                        </SelectItem>
                                        <SelectItem value="scheduled">
                                            Programmé
                                        </SelectItem>
                                        <SelectItem value="live">
                                            En direct
                                        </SelectItem>
                                        <SelectItem value="finished">
                                            Terminé
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        )}

                        {/* Filtre Date Début */}
                        {showDateFilter && (
                            <div>
                                <label className="mb-2 block text-sm font-medium">
                                    Date début
                                </label>
                                <Input
                                    type="date"
                                    value={dateFrom}
                                    onChange={(e) =>
                                        setDateFrom(e.target.value)
                                    }
                                />
                            </div>
                        )}

                        {/* Filtre Date Fin */}
                        {showDateFilter && (
                            <div>
                                <label className="mb-2 block text-sm font-medium">
                                    Date fin
                                </label>
                                <Input
                                    type="date"
                                    value={dateTo}
                                    onChange={(e) =>
                                        setDateTo(e.target.value)
                                    }
                                />
                            </div>
                        )}
                    </div>
                </div>
            )}
        </div>
    );
}
