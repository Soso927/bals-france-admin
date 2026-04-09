<header class="border-b border-stone-200 bg-white/90 backdrop-blur">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
        <div>
            <a href="{{ route('home') }}" class="text-lg font-semibold">Notre reseau</a>
            <p class="text-sm text-stone-500">Administration des agents commerciaux</p>
        </div>

        <nav class="flex items-center gap-3 text-sm">
            <a href="{{ route('admin.dashboard') }}" class="rounded-2xl px-4 py-2 text-stone-700 hover:bg-stone-100">Tableau de bord</a>

            @auth
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="rounded-2xl bg-stone-900 px-4 py-2 text-white">Deconnexion</button>
                </form>
            @endauth
        </nav>
    </div>
</header>