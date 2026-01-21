<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'U-Cup Tournoi')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    @stack('styles')
    
    <style>
        /* Styles personnalisés pour le design */
        
        /* Styles pour l'élément actif dans la barre de navigation */
        .nav-link.active {
            border-bottom: 3px solid white; 
            font-weight: 600;
            color: white !important;
        }
        
        /* Style pour le lien LIVE */
        .nav-link.live {
            color: #f87171 !important; /* Red 400 */
            border-bottom: 2px solid #f87171; 
            font-weight: 700;
        }
        
        /* Applique le style actif pour le LIVE si c'est la page actuelle */
        .nav-link.active.live {
            border-bottom: 3px solid white; 
            color: white !important;
        }
    </style>
</head>
{{-- FOND GENERAL : bg-gray-900 pour le Mode Sombre --}}
<body class="bg-gray-900 min-h-screen antialiased text-gray-100">
    
    {{-- NAV BAR : bg-gray-800 pour le contraste avec le fond du body --}}
    <nav class="bg-gray-800 shadow-lg border-b border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                <div class="flex items-center flex-shrink-0">
                    {{-- ACCENT VERT --}}
                    <a href="{{ route('home') }}" class="text-2xl font-bold text-white flex items-center space-x-2">
                        <i class="fas fa-trophy text-green-400"></i>
                        <span>U-Cup Tournoi</span>
                    </a>
                </div>
                
                <div class="hidden md:flex flex-1 justify-center items-stretch space-x-2 sm:space-x-8">
                    @php
                        $currentRoute = Route::currentRouteName();
                        $isStandingsActive = request()->routeIs('standings.*');
                        $isMatchesActive = request()->routeIs('matches.index');
                        $isLiveRoute = request()->routeIs('matches.live');
                        $isTeamsActive = request()->routeIs('teams.*');
                        $isPlayersIndexActive = request()->routeIs('players.index');
                        $isLeaderboardActive = request()->routeIs('players.leaderboard'); 
                        $isGalleryActive = request()->routeIs('gallery.index'); 
                    @endphp

                    <a href="{{ route('home') }}" class="nav-link {{ $currentRoute == 'home' ? 'active' : '' }} text-white hover:text-gray-300 inline-flex items-center px-3 pt-1 text-sm font-medium">Accueil</a>
                    
                    <a href="{{ route('matches.index') }}" class="nav-link {{ $isMatchesActive ? 'active' : '' }} text-white hover:text-gray-300 inline-flex items-center px-3 pt-1 text-sm font-medium">Matchs</a>
                    
                    <a href="{{ route('matches.live') }}" class="nav-link {{ $isLiveRoute ? 'active live' : 'live' }} inline-flex items-center px-3 pt-1 text-sm font-medium animate-pulse">En Direct</a>
                    
                    <a href="{{ route('teams.index') }}" class="nav-link {{ $isTeamsActive ? 'active' : '' }} text-white hover:text-gray-300 inline-flex items-center px-3 pt-1 text-sm font-medium">Équipes</a>
                    
                    <a href="{{ route('players.index') }}" class="nav-link {{ $isPlayersIndexActive ? 'active' : '' }} text-white hover:text-gray-300 inline-flex items-center px-3 pt-1 text-sm font-medium">Joueurs</a>
                    
                    <a href="{{ route('players.leaderboard') }}" class="nav-link {{ $isLeaderboardActive ? 'active' : '' }} text-white hover:text-gray-300 inline-flex items-center px-3 pt-1 text-sm font-medium">
                        <i class="fas fa-star mr-1"></i> Top Buteurs/Passeurs
                    </a>
                    
                    <a href="{{ route('gallery.index') }}" class="nav-link {{ $isGalleryActive ? 'active' : '' }} text-white hover:text-gray-300 inline-flex items-center px-3 pt-1 text-sm font-medium">Galerie</a>
                    
                    <a href="{{ route('standings.index') }}" class="nav-link {{ $isStandingsActive ? 'active' : '' }} text-white hover:text-gray-300 inline-flex items-center px-3 pt-1 text-sm font-medium">Classement</a>
                </div>

                <div class="flex items-center">
                    @auth
                        {{-- Bouton Admin avec accent vert --}}
                        <a href="{{ route('admin.dashboard') }}" class="text-sm font-bold text-gray-900 bg-green-500 px-3 py-1 rounded-md hover:bg-green-400 transition shadow-md">
                            Admin
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-gray-900 bg-green-500 px-3 py-1 rounded-md hover:bg-green-400 transition shadow-md">
                            Connexion
                        </a>
                    @endauth
                </div>

                <button class="md:hidden text-white" onclick="toggleMobileMenu()">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>

            {{-- Menu Mobile : Reste sombre et liens text-white --}}
            <div id="mobileMenu" class="hidden md:hidden pb-4 text-white">
                <a href="{{ route('home') }}" class="block py-2 hover:text-gray-300">Accueil</a>
                <a href="{{ route('matches.index') }}" class="block py-2 hover:text-gray-300">Matchs</a>
                <a href="{{ route('matches.live') }}" class="block py-2 text-red-400 hover:text-red-300">En Direct</a>
                <a href="{{ route('teams.index') }}" class="block py-2 hover:text-gray-300">Équipes</a>
                <a href="{{ route('players.index') }}" class="block py-2 hover:text-gray-300">Joueurs</a>
                <a href="{{ route('players.leaderboard') }}" class="block py-2 hover:text-gray-300">Top Buteurs/Passeurs</a>
                <a href="{{ route('gallery.index') }}" class="block py-2 hover:text-gray-300">Galerie</a>
                <a href="{{ route('standings.index') }}" class="block py-2 hover:text-gray-300">Classement</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="block py-2 text-green-400 hover:text-green-300">Admin</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Messages de session (succès/erreur) --}}
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-800 border border-green-400 text-white px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-800 border border-red-400 text-white px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <main>
        <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
            @yield('content')
        </div>
    </main>

    {{-- 
        ***************************************************
        NOUVEAU FOOTER DÉTAILLÉ ET PROFESSIONNEL
        *************************************************** --}}
    <footer class="bg-gray-800/90 border-t border-gray-700 mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            {{-- Grille principale du footer (4 colonnes sur grand écran, 2 colonnes sur mobile) --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-sm">
                
                {{-- 1. Identité & Organisation --}}
                <div class="col-span-2 md:col-span-1">
                    <h4 class="text-lg font-extrabold text-green-400 mb-3 flex items-center">
                        <i class="fas fa-trophy mr-2"></i> U-Cup Tournoi 2026
                    </h4>
                    <p class="text-gray-400 mb-2">Plateforme officielle de suivi du Tournoi Inter-Universitaire de Football.</p>
                    <p class="text-gray-300 font-semibold mt-4">Organisation :</p>
                    <p class="text-gray-400">Bureau Départemental des Étudiants (BDDE).</p>
                </div>

                {{-- 2. Navigation Rapide --}}
                <div>
                    <h4 class="text-lg font-bold text-white mb-3">Navigation Rapide</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition duration-150">Accueil</a></li>
                        <li><a href="{{ route('matches.index') }}" class="text-gray-400 hover:text-white transition duration-150">Matchs & Résultats</a></li>
                        <li><a href="{{ route('matches.live') }}" class="text-red-400 hover:text-red-300 font-bold transition duration-150">En Direct <i class="fas fa-satellite-dish ml-1 text-sm"></i></a></li>
                        <li><a href="{{ route('standings.index') }}" class="text-gray-400 hover:text-white transition duration-150">Classement</a></li>
                    </ul>
                </div>

                {{-- 3. Statistiques & Média --}}
                <div>
                    <h4 class="text-lg font-bold text-white mb-3">Statistiques & Équipes</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('teams.index') }}" class="text-gray-400 hover:text-white transition duration-150">Équipes participantes</a></li>
                        <li><a href="{{ route('players.index') }}" class="text-gray-400 hover:text-white transition duration-150">Liste des Joueurs</a></li>
                        <li><a href="{{ route('players.leaderboard') }}" class="text-gray-400 hover:text-white transition duration-150">Top Buteurs/Passeurs</a></li>
                        <li><a href="{{ route('gallery.index') }}" class="text-gray-400 hover:text-white transition duration-150">Galerie Photos</a></li>
                    </ul>
                </div>

                {{-- 4. Crédit & Contact --}}
                <div>
                    <h4 class="text-lg font-bold text-white mb-3">Développement & Contact</h4>
                    <div class="space-y-2">
                        <p class="text-gray-300 font-medium">Conçu par : Elmish MOUKOUANGA</p>
                        <p class="text-xs text-gray-500">Étudiant en Génie Informatique – ESTAM</p>
                    </div>
                    
                    <div class="mt-4 space-y-2">
                        <a href="tel:+242064149149" class="text-gray-400 hover:text-white flex items-center">
                            <i class="fas fa-phone-alt text-green-400 mr-2"></i> +242 06 414 91 49
                        </a>
                        <a href="mailto:emoukouanga@gmail.com" class="text-gray-400 hover:text-white flex items-center">
                            <i class="fas fa-envelope text-green-400 mr-2"></i> emoukouanga@gmail.com
                        </a>
                    </div>
                </div>
            </div>
            
            {{-- Barre de copyright du bas --}}
            <div class="border-t border-gray-700 mt-8 pt-4 text-center">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} U-Cup / BDDE – Tous droits réservés.
                    <a href="{{ route('login') }}" class="ml-4 text-xs text-gray-600 hover:text-gray-400">Accès Admin</a>
                </p>
            </div>
        </div>
    </footer>
    {{-- 
        ***************************************************
        FIN DU NOUVEAU FOOTER
        *************************************************** --}}

    <script>
        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        }
    </script>

    @stack('scripts')
</body>
</html>