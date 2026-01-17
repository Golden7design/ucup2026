<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - U-Cup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-xl w-96">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-blue-700">U-Cup Admin</h1>
            <p class="text-gray-500">Accès réservé aux organisateurs</p>
        </div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                    Email
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" 
                       id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Mot de passe
                </label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" 
                       id="password" type="password" name="password" required>
            </div>

            <div class="flex items-center justify-between">
                <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition" type="submit">
                    Se connecter
                </button>
            </div>
        </form>
        
        <div class="mt-6 text-center">
            <a href="{{ route('home') }}" class="text-sm text-gray-500 hover:text-blue-600">
                &larr; Retour au site public
            </a>
        </div>
    </div>

</body>
</html>