<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Connexion admin</title>
	@vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-stone-100 text-stone-900">
	<main class="mx-auto flex min-h-screen max-w-md items-center px-6 py-12">
		<section class="w-full rounded-3xl bg-white p-8 shadow-sm ring-1 ring-stone-200">
			<h1 class="text-2xl font-semibold">Administration</h1>
			<p class="mt-2 text-sm text-stone-600">Connectez-vous pour acceder au tableau de bord.</p>

			@if ($errors->any())
				<div class="mt-6 rounded-2xl bg-red-50 px-4 py-3 text-sm text-red-700">
					{{ $errors->first() }}
				</div>
			@endif

			<form method="POST" action="{{ route('admin.authenticate') }}" class="mt-6 space-y-4">
				@csrf
				<label class="block">
					<span class="mb-1 block text-sm font-medium">Email</span>
					<input type="email" name="email" value="{{ old('email') }}" required class="w-full rounded-2xl border border-stone-300 px-4 py-3">
				</label>

				<label class="block">
					<span class="mb-1 block text-sm font-medium">Mot de passe</span>
					<input type="password" name="password" required class="w-full rounded-2xl border border-stone-300 px-4 py-3">
				</label>

				<label class="flex items-center gap-2 text-sm text-stone-600">
					<input type="checkbox" name="remember" value="1" class="rounded border-stone-300">
					<span>Se souvenir de moi</span>
				</label>

				<button type="submit" class="w-full rounded-2xl bg-stone-900 px-4 py-3 font-medium text-white">
					Se connecter
				</button>
			</form>
		</section>
	</main>
</body>
</html>
