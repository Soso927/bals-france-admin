<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Bals France') }}</title>

    {{-- Police Exo 2 — charte graphique Bals --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Flux UI et styles Livewire --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-50 font-sans antialiased">

    {{-- Barre de marque Bals : dégradé bleu → rouge en haut de page --}}
    <div style="height:4px; background: linear-gradient(90deg, #0095DA 70%, #ED1C24 100%);"></div>

    {{--
        $slot est une variable spéciale de Blade.
        Elle reçoit automatiquement le contenu du composant qui utilise ce layout.
        Ici, ce sera le formulaire de connexion depuis login.blade.php.
    --}}
    <main class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">

            {{-- Logo / Titre de l'application --}}
            <div class="mb-8 text-center">
                <h1 class="text-2xl font-extrabold" style="color: #1A1A1A;">
                    Bals <span style="color: #0095DA;">France</span>
                </h1>
                <p class="mt-1 text-sm text-gray-500">Espace administration</p>
            </div>

            {{-- Contenu de la page (le formulaire de connexion) --}}
            {{ $slot }}

        </div>
    </main>

@livewireScripts
</body>
</html>