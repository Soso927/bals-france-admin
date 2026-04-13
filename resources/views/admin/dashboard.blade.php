@extends('layouts.admin')

@section('content')
<div class="space-y-8">

    <h1 class="text-2xl font-bold text-stone-900">Tableau de bord</h1>

    {{-- ══════════════════════════════════════════════════
         Cartes de statistiques — valeurs PHP depuis MySQL
         Agent::count() et Region::count() sont calculés
         dans la closure de routes/web.php et passés ici
         via compact('stats').
    ══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">

        <div class="rounded-3xl bg-white p-6 text-center shadow-sm ring-1 ring-stone-200">
            <div class="mb-2 text-xs font-bold uppercase tracking-wider text-stone-400">
                Agents commerciaux
            </div>
            <div class="text-4xl font-extrabold text-blue-600">
                {{ $stats['totalAgents'] }}
            </div>
            <div class="mt-1 text-xs text-stone-400">sur tout le territoire</div>
        </div>

        <div class="rounded-3xl bg-white p-6 text-center shadow-sm ring-1 ring-stone-200">
            <div class="mb-2 text-xs font-bold uppercase tracking-wider text-stone-400">
                Régions couvertes
            </div>
            <div class="text-4xl font-extrabold text-red-600">
                {{ $stats['totalRegions'] }}
            </div>
            <div class="mt-1 text-xs text-stone-400">France métropolitaine</div>
        </div>

        <div class="rounded-3xl bg-white p-6 text-center shadow-sm ring-1 ring-stone-200">
            <div class="mb-2 text-xs font-bold uppercase tracking-wider text-stone-400">
                Agences partenaires
            </div>
            <div class="text-4xl font-extrabold text-stone-900">
                {{ $stats['totalAgences'] }}
            </div>
            <div class="mt-1 text-xs text-stone-400">réseau de distribution</div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════
         Composant Livewire — Gestion des agents
         Namespace Admin → tag <livewire:admin.agent-manager />
         Rend app/Livewire/Admin/AgentManager.php
         qui charge Region::with('agents')->orderBy('nom')->get()
    ══════════════════════════════════════════════════ --}}
    <livewire:admin.agent-manager />

</div>
@endsection
