<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Configurateur BALS')</title>

    {{-- Styles Tailwind / Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Font Awesome (icônes) --}}
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
          crossorigin="anonymous" referrerpolicy="no-referrer">

    {{-- Police Exo 2 --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;600;700;900&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Exo 2', sans-serif; }
        .text-bals-blue  { color: #009EE3; }
        .bg-bals-blue    { background-color: #009EE3; }
        .border-bals-blue{ border-color: #009EE3; }
        .ring-bals-blue  { --tw-ring-color: #009EE3; }
        .focus\:ring-bals-blue:focus { --tw-ring-color: #009EE3; }
    </style>
</head>
<body class="min-h-screen bg-gray-50 text-gray-900 antialiased">

    {{-- Barre de marque BALS --}}
    <div style="height:4px; background: linear-gradient(90deg,#009EE3 70%,#DA291C 100%);"></div>

    @yield('content')

    @livewireScripts

    @yield('scripts')
</body>
</html>