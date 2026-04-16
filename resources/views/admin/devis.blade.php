@extends('layouts.admin')

@section('content')
<div class="space-y-8">

    <h1 class="text-2xl font-bold text-stone-900">Demandes de devis</h1>

    {{-- ══════════════════════════════════════════════════
         Cartes de statistiques
    ══════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">

        <div class="rounded-3xl bg-white p-6 text-center shadow-sm ring-1 ring-stone-200">
            <div class="mb-2 text-xs font-bold uppercase tracking-wider text-stone-400">
                Nouveaux
            </div>
            <div class="text-4xl font-extrabold text-orange-500">
                {{ $stats['nouveaux'] }}
            </div>
            <div class="mt-1 text-xs text-stone-400">en attente de lecture</div>
        </div>

        <div class="rounded-3xl bg-white p-6 text-center shadow-sm ring-1 ring-stone-200">
            <div class="mb-2 text-xs font-bold uppercase tracking-wider text-stone-400">
                Lus
            </div>
            <div class="text-4xl font-extrabold text-stone-500">
                {{ $stats['lus'] }}
            </div>
            <div class="mt-1 text-xs text-stone-400">en cours de traitement</div>
        </div>

        <div class="rounded-3xl bg-white p-6 text-center shadow-sm ring-1 ring-stone-200">
            <div class="mb-2 text-xs font-bold uppercase tracking-wider text-stone-400">
                Traités
            </div>
            <div class="text-4xl font-extrabold text-emerald-600">
                {{ $stats['traites'] }}
            </div>
            <div class="mt-1 text-xs text-stone-400">devis finalisés</div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════
         Composant Livewire — Gestion des devis
    ══════════════════════════════════════════════════ --}}
    <livewire:admin.devis-manager />

</div>
@endsection