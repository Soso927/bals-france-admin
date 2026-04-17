@extends('layouts.admin')

@section('content')

{{-- ══════════════════════════════════════════════════════
     Barre de couleur BALS France (bleu → rouge)
     Identique à celle du layout guest (page de connexion)
     pour une cohérence visuelle sur tout le site.
══════════════════════════════════════════════════════ --}}
<div style="height:4px; background: linear-gradient(90deg, #0095DA 70%, #ED1C24 100%);
            margin: -2.5rem -1.5rem 2.5rem; max-width: calc(100% + 3rem);"></div>

<div class="space-y-12">

    {{-- ══════════════════════════════════════════════════
         Bloc hero — titre, description, bouton d'accès
    ══════════════════════════════════════════════════ --}}
    <div class="rounded-3xl bg-white px-10 py-14 text-center shadow-sm ring-1 ring-stone-200">

        <h1 class="text-4xl font-extrabold tracking-tight text-stone-900">
            Bals <span style="color: #0095DA;">France</span>
        </h1>

        <p class="mt-2 text-sm font-semibold uppercase tracking-widest text-stone-400">
            Espace administration
        </p>

        <p class="mx-auto mt-6 max-w-md text-stone-600">
            Gérez vos agents commerciaux, vos régions et votre réseau
            de distribution en France depuis cette interface.
        </p>

        <div class="mt-8 flex flex-col items-center gap-3 sm:flex-row sm:justify-center">

            {{--
                Bouton conditionnel selon l'état de connexion :
                - @auth  → l'utilisateur est connecté → on l'envoie au dashboard
                - @else  → l'utilisateur n'est pas connecté → on l'envoie au login
                Laravel Auth gère automatiquement la session.
            --}}
            @auth
                <a href="{{ route('admin.dashboard') }}"
                   class="rounded-2xl px-6 py-3 font-semibold text-white"
                   style="background-color: #0095DA;">
                    Accéder au tableau de bord &rarr;
                </a>
            @else
                <a href="{{ route('login') }}"
                   class="rounded-2xl px-6 py-3 font-semibold text-white"
                   style="background-color: #0095DA;">
                    Se connecter &rarr;
                </a>
            @endauth

            {{-- Lien discret vers la carte interactive publique --}}
            <a href="/france-map"
               class="rounded-2xl border border-stone-300 px-6 py-3 text-sm font-medium text-stone-700 hover:bg-stone-50">
                Voir la carte France
            </a>

            {{-- Lien vers les configurateurs de devis --}}
            <a href="{{ route('configurateur.index') }}"
               class="rounded-2xl border border-stone-300 px-6 py-3 text-sm font-medium text-stone-700 hover:bg-stone-50">
                Configurateurs de devis →
            </a>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════
         Cartes de statistiques — chiffres réels depuis MySQL
         Même design que resources/views/admin/dashboard.blade.php.
         $stats est calculé dans la closure de routes/web.php :
           Agent::count(), Region::count(), Agent::distinct('agence')
    ══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">

        <div class="rounded-3xl bg-white p-8 text-center shadow-sm ring-1 ring-stone-200">
            <div class="mb-2 text-xs font-bold uppercase tracking-wider text-stone-400">
                Agents commerciaux
            </div>
            <div class="text-5xl font-extrabold" style="color: #0095DA;">
                {{ $stats['totalAgents'] }}
            </div>
            <div class="mt-2 text-xs text-stone-400">sur tout le territoire</div>
        </div>

        <div class="rounded-3xl bg-white p-8 text-center shadow-sm ring-1 ring-stone-200">
            <div class="mb-2 text-xs font-bold uppercase tracking-wider text-stone-400">
                Régions couvertes
            </div>
            <div class="text-5xl font-extrabold" style="color: #ED1C24;">
                {{ $stats['totalRegions'] }}
            </div>
            <div class="mt-2 text-xs text-stone-400">France métropolitaine</div>
        </div>

        <div class="rounded-3xl bg-white p-8 text-center shadow-sm ring-1 ring-stone-200">
            <div class="mb-2 text-xs font-bold uppercase tracking-wider text-stone-400">
                Agences partenaires
            </div>
            <div class="text-5xl font-extrabold text-stone-900">
                {{ $stats['totalAgences'] }}
            </div>
            <div class="mt-2 text-xs text-stone-400">réseau de distribution</div>
        </div>

    </div>

</div>

@endsection
