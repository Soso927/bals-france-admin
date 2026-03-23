@extends('layouts.admin')

@section('content')
    <section class="space-y-6">
        <div>
            <p class="text-sm uppercase tracking-[0.3em] text-stone-500">Espace admin</p>
            <h1 class="mt-2 text-3xl font-semibold">Tableau de bord</h1>
        </div>

        <livewire:admin.agent-manager />
    </section>
@endsection