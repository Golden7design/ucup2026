<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'U-Cup Tournoi'))</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="/css/global.css" rel="stylesheet">
    
    <!-- GSAP Animation Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    @include('partials.realtime')
    
    <style>
        body {
            background-color: #111827; /* bg-gray-900 */
            color: #f3f4f6; /* text-gray-100 */
            transition: background-color 0.3s ease, color 0.3s ease;
        }
        
        /* Style pour le mode clair */
        body.light-mode {
            background-color: #f3f4f6; /* bg-gray-100 */
            color: #111827; /* text-gray-900 */
        }
        
        /* Header styles for light mode */
        body.light-mode header {
            background-color: #ffffff; /* bg-white */
            box-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1);
        }
        
        /* Main content styles for light mode */
        body.light-mode main {
            background-color: #ffffff; /* bg-white */
        }
        
        /* Card styles for light mode */
        body.light-mode .bg-gray-800 {
            background-color: #f9fafb; /* bg-gray-50 */
            color: #111827; /* text-gray-900 */
        }
        
        /* Navigation link styles for light mode */
        body.light-mode .text-gray-300 {
            color: #6b7280; /* text-gray-500 */
        }
        
        body.light-mode .text-white {
            color: #111827; /* text-gray-900 */
        }
        
        body.light-mode .text-green-500 {
            color: #10b981; /* green-500 */
        }
        
        body.light-mode .text-red-500 {
            color: #ef4444; /* red-500 */
        }
        
        /* Button styles for light mode */
        body.light-mode .bg-green-600 {
            background-color: #10b981; /* green-500 */
        }
        
        body.light-mode .hover\:bg-green-600:hover {
            background-color: #059669; /* green-600 */
        }
        
        /* Border styles for light mode */
        body.light-mode .border-b-2 {
            border-color: #10b981; /* green-500 */
        }
        
        body.light-mode .border-gray-700 {
            border-color: #e5e7eb; /* gray-200 */
        }
        
        /* Theme toggle button styles for light mode */
        body.light-mode #frontend-theme-toggle {
            background-color: #e5e7eb; /* gray-200 */
            color: #111827; /* text-gray-900 */
        }
        
        body.light-mode #frontend-theme-toggle:hover {
            background-color: #d1d5db; /* gray-300 */
        }
        
        /* Responsive improvements for frontend */
        @media (max-width: 768px) {
            .hidden.md\:flex {
                display: none !important;
            }
            
            .flex.md\:hidden {
                display: flex !important;
            }
        }
        
        /* Mobile menu button */
        @media (max-width: 768px) {
            .mobile-menu-button {
                display: block;
            }
        }
        
        /* Ensure all text is readable in light mode */
        body.light-mode a,
        body.light-mode p,
        body.light-mode span,
        body.light-mode div,
        body.light-mode li {
            color: inherit;
        }
        
        /* Specific fixes for common elements */
        body.light-mode .text-gray-400 {
            color: #6b7280; /* gray-500 */
        }
        
        body.light-mode .bg-gray-700 {
            background-color: #e5e7eb; /* gray-200 */
        }
        
        /* Mobile menu styles */
        .mobile-menu-container {
            display: none;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-container.show {
            display: block;
        }
        
        /* Hide on desktop */
        @media (min-width: 768px) {
            .mobile-menu-container {
                display: none !important;
            }
        }
        
        /* Hamburger icon rotation */
        .rotate-90 {
            transform: rotate(90deg);
            transition: transform 0.3s ease;
        }
        
        /* Mobile menu link styles */
        #mobile-menu a {
            transition: all 0.2s ease;
        }
        
        #mobile-menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .hidden.md\:flex {
                display: none !important;
            }
            
            .flex.md\:hidden {
                display: flex !important;
            }
            
            /* Mobile-specific styles */
            .text-xl {
                font-size: 1.25rem;
            }
            
            /* Better spacing on mobile */
            .px-4 {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
        
        /* Animation for mobile menu */
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .mobile-menu-container.show {
            animation: slideDown 0.3s ease;
        }
        
        /* Theme toggle button mobile */
        #frontend-theme-toggle-mobile {
            margin-right: 0;
        }
        
        /* Light mode for mobile menu */
        body.light-mode .mobile-menu-container {
            background-color: #ffffff;
            border-color: #e5e7eb;
        }
        
        body.light-mode .mobile-menu-container a {
            color: #111827;
        }
        
        body.light-mode .mobile-menu-container a:hover {
            background-color: #f3f4f6;
        }
        
        body.light-mode .mobile-menu-container .border-t {
            border-color: #e5e7eb;
        }

        /* GSAP Animation Classes */
        main {
            opacity: 1;
        }

        .page-exit {
            animation: pageExit 0.5s ease-out forwards;
        }

        @keyframes pageExit {
            to {
                opacity: 0;
                transform: translateY(20px);
            }
        }

        /* Hover effect pour les cartes */
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-5px);
        }
    </style>
    
    <script>
        // Gestion du menu mobile
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const desktopNav = document.getElementById('desktop-nav');
            
            // Toggle mobile menu
            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('show');
                    
                    // Rotation de l'icône du menu hamburger
                    const icon = mobileMenuButton.querySelector('svg');
                    if (icon) {
                        icon.classList.toggle('rotate-90');
                    }
                });
            }
            
            // Fermer le menu mobile lorsque l'on clique sur un lien
            const mobileMenuLinks = mobileMenu ? mobileMenu.querySelectorAll('a') : [];
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (mobileMenu.classList.contains('show')) {
                        mobileMenu.classList.remove('show');
                        
                        // Réinitialiser la rotation de l'icône
                        const icon = mobileMenuButton.querySelector('svg');
                        if (icon) {
                            icon.classList.remove('rotate-90');
                        }
                    }
                });
            });
        });
        
        // Gestion du thème sombre/clair pour le frontend (desktop et mobile)
        function setupThemeToggle(themeToggleId, themeIconId) {
            const themeToggle = document.getElementById(themeToggleId);
            const themeIcon = document.getElementById(themeIconId);
            const body = document.body;
            
            if (!themeToggle || !themeIcon) return;
            
            // Vérifier la préférence sauvegardée ou système
            const savedTheme = localStorage.getItem('frontend-theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            
            // Appliquer le thème
            function applyTheme(theme) {
                if (theme === 'dark' || (theme === 'system' && prefersDark)) {
                    body.classList.remove('light-mode');
                    themeIcon.className = 'fas fa-sun text-yellow-400';
                } else {
                    body.classList.add('light-mode');
                    themeIcon.className = 'fas fa-moon text-yellow-400';
                }
            }
            
            // Initialiser
            applyTheme(savedTheme || 'system');
            
            // Écouteur pour le bouton
            themeToggle.addEventListener('click', function() {
                const currentTheme = localStorage.getItem('frontend-theme') || 'system';
                let newTheme;
                
                if (currentTheme === 'dark') {
                    newTheme = 'light';
                } else if (currentTheme === 'light') {
                    newTheme = 'system';
                } else {
                    newTheme = 'dark';
                }
                
                localStorage.setItem('frontend-theme', newTheme);
                applyTheme(newTheme);
            });
            
            // Écouteur pour les changements système
            window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
                const currentTheme = localStorage.getItem('frontend-theme') || 'system';
                if (currentTheme === 'system') {
                    applyTheme('system');
                }
            });
        }
        
        // Initialiser les boutons de thème
        document.addEventListener('DOMContentLoaded', function() {
            setupThemeToggle('frontend-theme-toggle', 'frontend-theme-icon');
            setupThemeToggle('frontend-theme-toggle-mobile', 'frontend-theme-icon-mobile');
        });
    </script>
</head>

<body class="font-sans antialiased bg-gray-900">
    
    {{-- BARRE DE NAVIGATION ADAPTÉE (Thème Sombre) --}}
    <header class="bg-gray-800 shadow-xl sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                
                {{-- Menu Hamburger Mobile --}}
                <div class="flex items-center md:hidden">
                    <button id="mobile-menu-button" class="text-gray-300 hover:text-white focus:outline-none focus:text-white mr-4">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>

                {{-- Logo U-CUP Tournoi --}}
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 font-extrabold text-2xl text-yellow-500 logo-link">
                        <i class="fas fa-trophy text-green-500"></i>
                        <span>U-CUP</span>
                    </a>
                </div>

                {{-- Bouton de basculement thème sombre/clair - Mobile --}}
                <div class="flex items-center md:hidden">
                    <button id="frontend-theme-toggle-mobile" class="p-2 rounded-full bg-gray-700 hover:bg-gray-600 transition-colors">
                        <i class="fas fa-moon text-yellow-400" id="frontend-theme-icon-mobile"></i>
                    </button>
                </div>

                {{-- Liens de Navigation Principaux --}}
                <nav class="hidden md:ml-6 md:flex md:space-x-8 items-center" id="desktop-nav">
                    
                    {{-- Bouton de basculement thème sombre/clair --}}
                    <button id="frontend-theme-toggle" class="p-2 rounded-full bg-gray-700 hover:bg-gray-600 transition-colors mr-4">
                        <i class="fas fa-moon text-yellow-400" id="frontend-theme-icon"></i>
                    </button>
                    
                    {{-- Fonction pour marquer l'onglet actif (basé sur la route) --}}
                    @php
                        $navLinkClasses = 'text-gray-300 hover:text-white px-3 py-2 text-sm font-medium transition duration-150 ease-in-out nav-link';
                        $activeLinkClasses = 'text-white border-b-2 border-green-500 font-bold px-3 py-2 text-sm nav-link';
                    @endphp
                    
                    <a href="{{ route('home') }}" 
                        class="{{ request()->routeIs('home') ? $activeLinkClasses : $navLinkClasses }}">
                        Accueil
                    </a>
                    
                    <a href="{{ route('matches.index') }}" 
                        class="{{ request()->routeIs('matches.index') ? $activeLinkClasses : $navLinkClasses }}">
                        Matchs
                    </a>
                    
                    <a href="{{ route('matches.live') }}" 
                        class="text-red-500 font-bold hover:text-red-400 px-3 py-2 text-sm flex items-center nav-link">
                        <i class="fas fa-circle text-xs animate-pulse mr-1"></i> En Direct
                    </a>

                    <a href="{{ route('teams.index') }}" 
                        class="{{ request()->routeIs('teams.index') ? $activeLinkClasses : $navLinkClasses }}">
                        Équipes
                    </a>
                    
                    <a href="{{ route('players.index') }}" 
                        class="{{ request()->routeIs('players.index') ? $activeLinkClasses : $navLinkClasses }}">
                        Joueurs
                    </a>
                    
                    {{-- J'utilise la route 'players.top' pour le top buteurs (à définir si ce n'est pas 'players.index') --}}
                    <a href="{{ route('players.leaderboard') }}" 
                        class="{{ request()->routeIs('players.leaderboard') ? $activeLinkClasses : $navLinkClasses }}">
                        Top Buteurs/Passeurs
                    </a>
                    
                    <a href="{{ route('gallery.index') }}" 
                        class="{{ request()->routeIs('gallery.index') ? $activeLinkClasses : $navLinkClasses }}">
                        Galerie
                    </a>
                    
                    <a href="{{ route('standings.index') }}" 
                        class="{{ request()->routeIs('standings.index') ? $activeLinkClasses : $navLinkClasses }}">
                        Classement
                    </a>

                </nav>
                
                {{-- Menu Mobile --}}
                <div class="mobile-menu-container" id="mobile-menu">
                    <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-gray-800 border-t border-gray-700">
                        <a href="{{ route('home') }}" 
                            class="block px-3 py-2 rounded-md text-base font-medium mobile-nav-link {{ request()->routeIs('home') ? 'text-white bg-gray-700' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                            Accueil
                        </a>
                        
                        <a href="{{ route('matches.index') }}" 
                            class="block px-3 py-2 rounded-md text-base font-medium mobile-nav-link {{ request()->routeIs('matches.index') ? 'text-white bg-gray-700' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                            Matchs
                        </a>
                        
                        <a href="{{ route('matches.live') }}" 
                            class="block px-3 py-2 rounded-md text-base font-medium text-red-500 font-bold hover:text-red-400 mobile-nav-link">
                            <i class="fas fa-circle text-xs animate-pulse mr-1"></i> En Direct
                        </a>
                        
                        <a href="{{ route('teams.index') }}" 
                            class="block px-3 py-2 rounded-md text-base font-medium mobile-nav-link {{ request()->routeIs('teams.index') ? 'text-white bg-gray-700' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                            Équipes
                        </a>
                        
                        <a href="{{ route('players.index') }}" 
                            class="block px-3 py-2 rounded-md text-base font-medium mobile-nav-link {{ request()->routeIs('players.index') ? 'text-white bg-gray-700' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                            Joueurs
                        </a>
                        
                        <a href="{{ route('players.leaderboard') }}" 
                            class="block px-3 py-2 rounded-md text-base font-medium mobile-nav-link {{ request()->routeIs('players.leaderboard') ? 'text-white bg-gray-700' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                            Top Buteurs/Passeurs
                        </a>
                        
                        <a href="{{ route('gallery.index') }}" 
                            class="block px-3 py-2 rounded-md text-base font-medium mobile-nav-link {{ request()->routeIs('gallery.index') ? 'text-white bg-gray-700' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                            Galerie
                        </a>
                        
                        <a href="{{ route('standings.index') }}" 
                            class="block px-3 py-2 rounded-md text-base font-medium mobile-nav-link {{ request()->routeIs('standings.index') ? 'text-white bg-gray-700' : 'text-gray-300 hover:text-white hover:bg-gray-700' }}">
                            Classement
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-8">
        @yield('content')
    </main>

    {{-- 
    ***************************************************
    FOOTER DÉTAILLÉ ET PROFESSIONNEL
    *************************************************** --}}
    <footer class="bg-gray-800/90 border-t border-gray-700 mt-12">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            {{-- Grille principale du footer (4 colonnes sur grand écran, 2 colonnes sur mobile) --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-sm footer-grid">
                
                {{-- 1. Identité & Organisation --}}
                <div class="col-span-2 md:col-span-1 footer-section">
                    <h4 class="text-lg font-extrabold text-green-400 mb-3 flex items-center">
                        <i class="fas fa-trophy mr-2"></i> U-Cup Tournoi 2026
                    </h4>
                    <p class="text-gray-400 mb-2">Plateforme officielle de suivi du Tournoi Inter-Universitaire de Football.</p>
                    <p class="text-gray-300 font-semibold mt-4">Organisation :</p>
                    <p class="text-gray-400">Bureau Départemental des Étudiants (BDDE).</p>
                </div>

                {{-- 2. Navigation Rapide --}}
                <div class="footer-section">
                    <h4 class="text-lg font-bold text-white mb-3">Navigation Rapide</h4>
                    <ul class="space-y-2 footer-links">
                        <li><a href="{{ route('home') }}" class="text-gray-400 hover:text-white transition duration-150">Accueil</a></li>
                        <li><a href="{{ route('matches.index') }}" class="text-gray-400 hover:text-white transition duration-150">Matchs & Résultats</a></li>
                        <li><a href="{{ route('matches.live') }}" class="text-red-400 hover:text-red-300 font-bold transition duration-150">En Direct <i class="fas fa-satellite-dish ml-1 text-sm"></i></a></li>
                        <li><a href="{{ route('standings.index') }}" class="text-gray-400 hover:text-white transition duration-150">Classement</a></li>
                    </ul>
                </div>

                {{-- 3. Statistiques & Média --}}
                <div class="footer-section">
                    <h4 class="text-lg font-bold text-white mb-3">Statistiques & Équipes</h4>
                    <ul class="space-y-2 footer-links">
                        <li><a href="{{ route('teams.index') }}" class="text-gray-400 hover:text-white transition duration-150">Équipes participantes</a></li>
                        <li><a href="{{ route('players.index') }}" class="text-gray-400 hover:text-white transition duration-150">Liste des Joueurs</a></li>
                        <li><a href="{{ route('players.leaderboard') }}" class="text-gray-400 hover:text-white transition duration-150">Top Buteurs/Passeurs</a></li>
                        <li><a href="{{ route('gallery.index') }}" class="text-gray-400 hover:text-white transition duration-150">Galerie Photos</a></li>
                    </ul>
                </div>

                {{-- 4. Crédit & Contact --}}
                <div class="footer-section">
                    <h4 class="text-lg font-bold text-white mb-3">Développement & Contact</h4>
                    <div class="space-y-2">
                        <p class="text-gray-300 font-medium">Conçu par : Elmish MOUKOUANGA</p>
                        <p class="text-xs text-gray-500">Étudiant en Génie Informatique – ESTAM</p>
                    </div>
                    
                    <div class="mt-4 space-y-2 footer-links">
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
            <div class="border-t border-gray-700 mt-8 pt-4 text-center footer-copyright">
                <p class="text-sm text-gray-500">
                    &copy; {{ date('Y') }} U-Cup / BDDE – Tous droits réservés.
                    <a href="{{ route('login') }}" class="ml-4 text-xs text-gray-600 hover:text-gray-400">Accès Admin</a>
                </p>
            </div>
        </div>
    </footer>
    {{-- 
    ***************************************************
    FIN DU FOOTER
    *************************************************** --}}

    <script>
        // GSAP Page Animation Functions
        function animatePageLoad() {
            // Animer le header
            gsap.fromTo('header', 
                { opacity: 0, y: -30 },
                { opacity: 1, y: 0, duration: 0.6, ease: 'power2.out' }
            );

            // Animer le logo
            gsap.fromTo('.logo-link',
                { opacity: 0, scale: 0.8 },
                { opacity: 1, scale: 1, duration: 0.5, ease: 'back.out', delay: 0.1 }
            );

            // Animer les liens de navigation
            gsap.fromTo('.nav-link, .mobile-nav-link',
                { opacity: 0, x: 20 },
                { opacity: 1, x: 0, duration: 0.5, stagger: 0.08, ease: 'power2.out', delay: 0.2 }
            );

            // Animer le contenu principal avec délai
            gsap.fromTo('main',
                { opacity: 0, y: 30 },
                { opacity: 1, y: 0, duration: 0.7, ease: 'power2.out', delay: 0.3 }
            );

            // Animer les sections du footer
            gsap.fromTo('.footer-section',
                { opacity: 0, y: 20 },
                { opacity: 1, y: 0, duration: 0.6, stagger: 0.1, ease: 'power2.out', delay: 0.4 }
            );

            // Animer les liens du footer
            gsap.fromTo('.footer-links a',
                { opacity: 0, x: -10 },
                { opacity: 1, x: 0, duration: 0.4, stagger: 0.05, ease: 'power2.out', delay: 0.5 }
            );

            // Animer le copyright
            gsap.fromTo('.footer-copyright',
                { opacity: 0 },
                { opacity: 1, duration: 0.5, ease: 'power2.out', delay: 0.6 }
            );
        }

        // Animation de transition entre pages
        function animatePageExit(url) {
            gsap.to('main', {
                duration: 0.4,
                opacity: 0,
                y: -20,
                ease: 'power2.in',
                onComplete: () => {
                    window.location.href = url;
                }
            });
        }

        // Écouter les clics sur les liens internes
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link && link.hostname === window.location.hostname && !link.target) {
                const url = link.getAttribute('href');
                if (url && !url.startsWith('#')) {
                    e.preventDefault();
                    animatePageExit(url);
                }
            }
        });

        // Animation des cartes au survol
        function setupCardHoverAnimations() {
            const cards = document.querySelectorAll('.bg-gray-800, .bg-gray-900, .rounded-lg');
            cards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    gsap.to(this, {
                        duration: 0.3,
                        y: -5,
                        boxShadow: '0 20px 25px -5px rgba(0, 0, 0, 0.3)',
                        ease: 'power2.out'
                    });
                });

                card.addEventListener('mouseleave', function() {
                    gsap.to(this, {
                        duration: 0.3,
                        y: 0,
                        boxShadow: '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
                        ease: 'power2.out'
                    });
                });
            });
        }

        // Animation des boutons
        function setupButtonAnimations() {
            const buttons = document.querySelectorAll('button, a.bg-red-600, a.bg-blue-600, a.bg-green-600');
            buttons.forEach(btn => {
                btn.addEventListener('mouseenter', function() {
                    gsap.to(this, {
                        duration: 0.2,
                        scale: 1.05,
                        ease: 'power2.out'
                    });
                });

                btn.addEventListener('mouseleave', function() {
                    gsap.to(this, {
                        duration: 0.2,
                        scale: 1,
                        ease: 'power2.out'
                    });
                });
            });
        }

        // Initialiser les animations au chargement
        document.addEventListener('DOMContentLoaded', function() {
            animatePageLoad();
            setupCardHoverAnimations();
            setupButtonAnimations();
        });

        function toggleMobileMenu() {
            document.getElementById('mobileMenu').classList.toggle('hidden');
        }
    </script>

    @stack('scripts')
    
</body>
</html>
