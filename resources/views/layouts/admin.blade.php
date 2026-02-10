<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- **AJOUT CRUCIAL** : Jeton CSRF pour les requêtes AJAX POST --}}
    <meta name="csrf-token" content="{{ csrf_token() }}"> 
    <title>Admin - U-Cup Tournament</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="/css/global.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @include('partials.realtime')
    <script>
        // Gestion du thème sombre/clair pour l'admin
        document.addEventListener('DOMContentLoaded', function() {
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const body = document.body;
            
            // Vérifier la préférence sauvegardée ou système
            const savedTheme = localStorage.getItem('admin-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            // Appliquer le thème
            function applyTheme(theme) {
                if (theme === 'dark' || (theme === 'system' && prefersDark)) {
                    body.classList.add('dark');
                    themeIcon.className = 'fas fa-sun text-yellow-400';
                } else {
                    body.classList.remove('dark');
                    themeIcon.className = 'fas fa-moon text-yellow-400';
                }
            }
            
            // Initialiser
            applyTheme(savedTheme || 'system');
            
            // Écouteur pour le bouton
            themeToggle.addEventListener('click', function() {
                const currentTheme = localStorage.getItem('admin-theme') || 'system';
                let newTheme;
                
                if (currentTheme === 'dark') {
                    newTheme = 'light';
                } else if (currentTheme === 'light') {
                    newTheme = 'system';
                } else {
                    newTheme = 'dark';
                }
                
                localStorage.setItem('admin-theme', newTheme);
                applyTheme(newTheme);
            });
            
            // Écouteur pour les changements système
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                const currentTheme = localStorage.getItem('admin-theme') || 'system';
                if (currentTheme === 'system') {
                    applyTheme('system');
                }
            });
        });
    </script>
    {{-- Pour s'assurer que l'icône de l'Admin est cohérente --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> 
    <style>
        /* Animations personnalisées */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease-out;
        }
        
        /* Style pour les liens de la sidebar */
        .sidebar-link {
            transition: all 0.2s ease;
        }
        
        .sidebar-link:hover {
            transform: translateX(4px);
        }
        
        /* Style pour les boutons */
        .btn-primary {
            transition: all 0.2s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        /* Style pour les cartes */
        .card-hover {
            transition: all 0.2s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        
        /* Styles pour le mode clair */
        body:not(.dark) {
            background-color: #f3f4f6; /* bg-gray-100 */
            color: #111827; /* text-gray-900 */
        }
        
        body:not(.dark) aside {
            background-color: #ffffff; /* bg-white */
            border-right: 1px solid #e5e7eb; /* border-gray-200 */
        }
        
        body:not(.dark) header {
            background-color: #ffffff; /* bg-white */
            border-bottom: 1px solid #e5e7eb; /* border-gray-200 */
        }
        
        body:not(.dark) main {
            background-color: #f9fafb; /* bg-gray-50 */
        }
        
        /* Couleurs de texte pour le mode clair */
        body:not(.dark) .text-white,
        body:not(.dark) .text-gray-100,
        body:not(.dark) .text-gray-300 {
            color: #111827; /* text-gray-900 */
        }
        
        body:not(.dark) .text-gray-400 {
            color: #6b7280; /* text-gray-500 */
        }
        
        body:not(.dark) .text-gray-500 {
            color: #6b7280; /* text-gray-500 */
        }
        
        /* Couleurs de fond pour le mode clair */
        body:not(.dark) .bg-gray-800 {
            background-color: #f3f4f6; /* bg-gray-100 */
        }
        
        body:not(.dark) .bg-gray-700 {
            background-color: #e5e7eb; /* bg-gray-200 */
        }
        
        body:not(.dark) .bg-gray-900 {
            background-color: #ffffff; /* bg-white */
        }
        
        /* Couleurs d'accent pour le mode clair */
        body:not(.dark) .text-green-500,
        body:not(.dark) .text-green-400 {
            color: #10b981; /* green-500 */
        }
        
        body:not(.dark) .bg-green-600 {
            background-color: #059669; /* green-600 */
        }
        
        body:not(.dark) .bg-green-700 {
            background-color: #047857; /* green-700 */
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .sidebar-link {
                transform: translateX(0) !important;
            }
            
            .card-hover:hover {
                transform: translateY(0) !important;
            }
        }
    </style>
</head>
{{-- 1. FOND GENERAL : Utilisation d'un gris très foncé pour le corps --}}
<body class="bg-gray-900 font-sans antialiased text-white" x-data="{ sidebarOpen: false }" @click.away="sidebarOpen = false">
    <div class="flex h-screen overflow-hidden">
        
        {{-- ASIDE (Barre latérale) : Reste sombre mais avec des couleurs d'accent changées --}}
        <aside class="w-64 bg-gray-900 text-white flex flex-col flex-shrink-0 transition-all duration-300 border-r border-gray-800">
            <div class="h-16 flex items-center justify-center border-b border-gray-800">
                <a href="{{ route('home') }}" class="flex items-center space-x-2 font-bold text-xl">
                    <i class="fas fa-trophy text-green-500"></i> {{-- Accent vert/jaune --}}
                    <span>U-Cup Admin</span>
                </a>
            </div>

            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-2 px-4">
                    <li>
                        {{-- COULEUR ACTIVE CHANGÉE : bg-blue-600 -> bg-green-700 --}}
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.dashboard') ? 'bg-green-700' : '' }}">
                            <i class="fas fa-tachometer-alt w-6 text-center"></i>
                            <span>Tableau de bord</span>
                        </a>
                    </li>
                    
                    <li class="pt-4 pb-2 text-xs text-gray-500 uppercase font-bold">Gestion Tournoi</li>
                    
                    <li>
                        <a href="{{ route('admin.matches.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.matches.*') ? 'bg-green-700' : '' }}">
                            <i class="fas fa-calendar-alt w-6 text-center"></i>
                            <span>Matchs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.live.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.live.*') ? 'bg-green-700' : '' }}">
                            <i class="fas fa-broadcast-tower w-6 text-center text-red-500 animate-pulse"></i>
                            <span>Live Center</span>
                        </a>
                    </li>

                    <li class="pt-4 pb-2 text-xs text-gray-500 uppercase font-bold">Données</li>
                    
                    <li>
                        <a href="{{ route('admin.universities.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.universities.*') ? 'bg-green-700' : '' }}">
                            <i class="fas fa-university w-6 text-center"></i>
                            <span>Universités</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.teams.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.teams.*') ? 'bg-green-700' : '' }}">
                            <i class="fas fa-users w-6 text-center"></i>
                            <span>Équipes</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('admin.players.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.players.*') ? 'bg-green-700' : '' }}">
                            <i class="fas fa-user-friends w-6 text-center"></i>
                            <span>Joueurs</span>
                        </a>
                    </li>
                    
                    <li>
                        <a href="{{ route('admin.gallery.index') }}" class="flex items-center space-x-3 p-3 rounded-lg hover:bg-gray-800 {{ request()->routeIs('admin.gallery.*') ? 'bg-green-700' : '' }}">
                            <i class="fas fa-camera w-6 text-center"></i>
                            <span>Galerie Multimédia</span>
                        </a>
                    </li>
                    
                </ul>
            </nav>

            <div class="p-4 border-t border-gray-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center space-x-3 text-gray-400 hover:text-white transition w-full">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Déconnexion</span>
                    </button>
                </form>
            </div>
        </aside>

        <div class="flex-1 flex flex-col h-screen overflow-hidden">
            {{-- HEADER : Devient sombre et le texte passe au blanc --}}
            <header class="h-16 bg-gray-800 shadow-lg flex items-center justify-between px-8 border-b border-gray-700">
                <h2 class="text-xl font-semibold text-white">
                    @yield('header')
                </h2>
                <div class="flex items-center space-x-4">
                    {{-- BOUTON THÈME SOMBRE/CLAIR --}}
                    <button id="theme-toggle" class="p-2 rounded-full bg-gray-700 hover:bg-gray-600 transition-colors">
                        <i class="fas fa-moon text-yellow-400" id="theme-icon"></i>
                    </button>
                    
                    {{-- LIEN VOIR SITE : Utilisation de la couleur d'accent verte --}}
                    <a href="{{ route('home') }}" target="_blank" class="text-green-500 hover:text-green-400 text-sm">
                        <i class="fas fa-external-link-alt mr-1"></i> Voir le site
                    </a>
                    {{-- AVATAR : Devient text-white sur fond bg-green-600 --}}
                    <div class="h-8 w-8 rounded-full bg-green-600 flex items-center justify-center text-white font-bold">
                        A
                    </div>
                </div>
            </header>

            {{-- MAIN CONTENT : Utilise le même fond sombre que le body (bg-gray-900) --}}
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-900 p-8">
                {{-- Messages de session (succès/erreur) : Ajout de couleurs sombres --}}
                @if(session('success'))
                    <div class="bg-green-800 border-l-4 border-green-500 text-white p-4 mb-6 rounded-md" role="alert">
                        <p>{{ session('success') }}</p>
                    </div>
                @endif

                @if($errors->any())
                    <div class="bg-red-800 border-l-4 border-red-500 text-white p-4 mb-6 rounded-md">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>- {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
