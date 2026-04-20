{{--
    ============================================================
    COMPOSANT : <x-configurateur.radio-group>
    ============================================================
    Génère un groupe de boutons radio stylisés.
    Une seule option peut être sélectionnée à la fois.

    Props :
        $label   → libellé affiché en majuscules au-dessus
        $name    → attribut name des inputs (identifie le groupe)
        $options → tableau PHP des valeurs disponibles
        $bold    → true = texte en gras (pour IP, tension…) | false (défaut)

    Exemple :
        <x-configurateur.radio-group
            label="Type de montage"
            name="montage"
            :options="['Mobile', 'Fixe au sol', 'Fixe au mur']"
        />

        <x-configurateur.radio-group
            label="Indice de protection (IP)"
            name="ip"
            :options="['IP44', 'IP54', 'IP65']"
            :bold="true"
        />
    ============================================================
--}}
@props([
    'label',
    'name',
    'options',
    'bold' => false,
])

<div>
    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ $label }}</p>

    <div class="flex flex-wrap gap-2">
        @foreach($options as $opt)
        {{--
            Le <label> englobe l'input pour que cliquer sur le texte
            sélectionne également le bouton radio.
            has-[:checked]:... applique le style actif via CSS natif.
        --}}
        <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-4 py-2
                       hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
            <input type="radio" name="{{ $name }}" value="{{ $opt }}"
                   onchange="mettreAJour()" class="accent-[#009EE3]">
            <span class="text-sm {{ $bold ? 'font-bold' : 'font-medium' }}">{{ $opt }}</span>
        </label>
        @endforeach
    </div>
</div>
