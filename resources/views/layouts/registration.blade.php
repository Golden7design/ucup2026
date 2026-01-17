<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Inscription U-Cup 2026')</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        .registration-bg {
            background-color: #111827;
        }
    </style>
</head>
<body class="registration-bg min-h-screen antialiased text-gray-100 flex items-center justify-center py-10">
    
    <main class="w-full">
        @yield('content')
    </main>

    <footer class="absolute bottom-0 w-full text-center py-4 text-xs text-gray-500">
        &copy; {{ date('Y') }} U-Cup Tournoi 2026 | BDDE - ESTAM.
    </footer>
    
    @stack('scripts')
</body>
</html>