{{--
    ============================================================
    COMPOSANT : <x-configurateur.checkbox-group>
    ============================================================
    Génère un groupe de cases à cocher stylisées.
    Plusieurs options peuvent être sélectionnées simultanément.

    Props :
        $label   → libellé affiché en majuscules au-dessus
        $name    → base du nom — le composant ajoute [] pour que
                   PHP reçoive un tableau (ex : prot_tete[])
        $options → tableau PHP des valeurs disponibles

    Exemple :
        <x-configurateur.checkbox-group
            label="Protection en tête"
            name="prot_tete"
            :options="['Différentiel 30mA', 'Disjoncteur général', 'Parafoudre', 'Sans']"
        />
    ============================================================
--}}
@props([
    'label',
    'name',
    'options',
])

<div>
    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">{{ $label }}</p>

    <div class="flex flex-wrap gap-2">
        @foreach($options as $opt)
        <label class="flex items-center gap-2 cursor-pointer border border-gray-200 rounded-xl px-3 py-2
                       hover:border-bals-blue transition-all has-[:checked]:border-bals-blue has-[:checked]:bg-bals-blue/5">
            {{--
                name="{{ $name }}[]" : le [] indique à PHP de collecter
                toutes les valeurs cochées dans un tableau.
            --}}
            <input type="checkbox" name="{{ $name }}[]" value="{{ $opt }}"
                   onchange="mettreAJour()" class="accent-[#009EE3]">
            <span class="text-sm">{{ $opt }}</span>
        </label>
        @endforeach
    </div>
</div>
