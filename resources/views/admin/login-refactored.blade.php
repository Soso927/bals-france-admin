{{--
    Les doubles accolades avec tirets sont des commentaires Blade.
    Ils n'apparaissent JAMAIS dans le code source de la page,
    contrairement aux commentaires HTML <!-- --> qui sont visibles
    dans l'inspecteur du navigateur. C'est important pour une page
    d'administration : on ne veut pas révéler d'informations.
--}}
<!DOCTYPE html>
<html lang="fr" data-page="login">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration — Bals France</title>

    {{-- Police Exo 2 — même police que le reste du site Bals --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    {{-- Charger tous les styles via Vite --}}
    @vite('resources/css/app.css')
</head>

<body>

    {{-- ══════ BARRE BLEUE→ROUGE EN HAUT ══════ --}}
    <div class="brand-bar"></div>

    {{-- Flexbox qui centre la carte verticalement et horizontalement --}}
    <div class="flex items-center justify-center min-h-screen px-4 py-12">

        <div class="login-card w-full max-w-md">

            {{--
            ══════════════════════════════════════════════════════
            EN-TÊTE : icône cadenas + titre
            ══════════════════════════════════════════════════════
            --}}
            <div class="text-center mb-8">

                {{-- Cercle avec l'icône cadenas dessinée en SVG --}}
                <div class="lock-icon-circle inline-flex items-center justify-center w-16 h-16 rounded-full mb-6">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none"
                         stroke="#0095DA" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </div>

                <h1 class="text-2xl font-extrabold text-white">Administration</h1>
                <p class="text-sm mt-2 subtitle-text">
                    Espace réservé — Bals France
                </p>
            </div>

            {{--
            ══════════════════════════════════════════════════════
            MESSAGES D'ERREUR
            ──────────────────────────────────────────────────────
            @if ($errors->any()) est une directive Blade.
            $errors est une variable que Laravel remplit automatiquement
            quand la validation échoue dans AdminAuthController.
            Si la connexion échoue, les messages d'erreur définis dans
            le contrôleur avec withErrors([...]) apparaissent ici.
            ══════════════════════════════════════════════════════
            --}}
            @if ($errors->any())
                <div class="error-box mb-6 p-4 rounded-lg text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            {{--
            ══════════════════════════════════════════════════════
            FORMULAIRE DE CONNEXION
            ══════════════════════════════════════════════════════
            --}}
            <form method="POST" action="{{ route('admin.login') }}" data-auth-form>
                @csrf

                {{-- Champ Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-300">
                        Email
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        class="input-field"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        placeholder="votre@email.com"
                    />
                </div>

                {{-- Champ Mot de passe --}}
                <div class="mb-6" data-password-field>
                    <label for="password" class="block text-sm font-medium text-gray-300">
                        Mot de passe
                    </label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="input-field"
                        required
                        placeholder="••••••••"
                    />
                </div>

                {{-- Bouton de connexion --}}
                <button
                    type="submit"
                    class="w-full py-3 rounded-lg font-medium text-white transition"
                    style="background: linear-gradient(90deg, var(--bals-blue), #0076b0);
                           hover:opacity: 0.9;"
                >
                    Se connecter
                </button>
            </form>

        </div>

    </div>

    {{-- Charger tous les scripts via Vite --}}
    @vite('resources/js/app.js')

</body>

</html>
