{{--
    ============================================================
    COMPOSANT : <x-configurateur.section>
    ============================================================
    Génère une carte accordéon (section repliable) utilisée dans
    les formulaires chantier, etage, industrie et evenementiel.

    Props :
        $id      → identifiant unique, ex : 's1', 's2'
        $number  → numéro affiché dans le badge rond
        $title   → titre de la section
        $accent  → true (défaut) = badge bleu | false = badge gris

    Le contenu (champs du formulaire) est passé via {{ $slot }}.

    Exemple :
        <x-configurateur.section id="s2" number="2" title="Configuration">
            <div class="space-y-5">
                <x-configurateur.radio-group ... />
            </div>
        </x-configurateur.section>
    ============================================================
--}}
@props([
    'id',
    'number',
    'title',
    'accent' => true,
])

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

    {{-- Bouton cliquable qui ouvre/ferme la section via toggleSection() --}}
    <button onclick="toggleSection('{{ $id }}')"
            class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">

        <div class="flex items-center gap-3">
            {{-- Badge numéroté : bleu pour sections principales, gris pour Observations --}}
            <span class="flex-shrink-0 w-7 h-7 rounded-full text-xs font-black flex items-center justify-center
                         {{ $accent ? 'bg-bals-blue text-white' : 'bg-gray-200 text-gray-600' }}">
                {{ $number }}
            </span>
            <span class="font-bold text-gray-800">{{ $title }}</span>
        </div>

        {{-- Flèche ▼/▲ mise à jour par le JS selon l'état ouvert/fermé --}}
        <span id="arrow-{{ $id }}" class="text-gray-400 text-sm">▼</span>

    </button>

    {{-- Corps caché par défaut — "hidden" retiré au clic via toggleSection() --}}
    <div id="section-{{ $id }}" class="hidden px-5 pb-5 space-y-3">
        {{ $slot }}
    </div>

</div>
