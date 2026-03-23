<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{ $title ?? 'Administration' }}</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
	@livewireStyles
</head>
<body class="min-h-screen bg-stone-100 text-stone-900">
	@include('livewire.layout.header')

	<main class="mx-auto max-w-7xl px-6 py-10">
		@yield('content')
		{{ $slot ?? '' }}
	</main>

	@include('livewire.layout.footer')
	@livewireScripts
</body>
</html>
