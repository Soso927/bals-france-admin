<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	{{-- {{
	La balise meta csrf-token est essentielle pour la sécurité. Elle permet à Livewire (et à Javascript) d'inclure automatiquement le token de protection CSRF dans toutes ses requêtes vers le serveur. Sans elle, Laravel refuserait toutes les requêtes de modification avec une erreur 419 "Page Expired".
	}} --}}
	 <meta name="csrf-token" content="{{ csrf_token() }}">
	<title>@yield('title', $title ?? 'Administration')</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	{{--
        @livewireStyles injecte les quelques règles CSS nécessaires
        au bon fonctionnement des composants Livewire.
        Doit être dans le <head> pour éviter les flashs visuels.
    --}}
	@livewireStyles
	<style>
		.text-bals-blue              { color: #009EE3; }
		.bg-bals-blue                { background-color: #009EE3; }
		.border-bals-blue            { border-color: #009EE3; }
		.ring-bals-blue              { --tw-ring-color: #009EE3; }
		.focus\:ring-bals-blue:focus { --tw-ring-color: #009EE3; }
	</style>
	@yield('styles')
</head>
<body class="min-h-screen bg-stone-100 text-stone-900">
	@include('livewire.layout.header')

	<main class="mx-auto max-w-7xl px-6 py-10">
		@yield('content')
		{{ $slot ?? '' }}
	</main>

	@include('livewire.layout.footer')
	@livewireScripts
	@yield('scripts')
</body>
</html>
