<?php

namespace App\Livewire;

use App\Models\Agent;
use App\Models\Region;
use Livewire\Component;

/**
 * Composant Livewire — Gestion des agents commerciaux
 *
 * Ce composant gère l'intégralité du CRUD des agents directement
 * depuis le tableau de bord admin, sans JavaScript manuel.
 * Livewire s'occupe de synchroniser l'état entre le PHP et le HTML.
 *
 * Cycle de vie d'une action typique :
 *   1. L'admin clique sur "Supprimer" dans le Blade
 *   2. Livewire intercepte le clic (wire:click="deleteAgent(5)")
 *   3. Livewire envoie une requête AJAX au serveur PHP
 *   4. La méthode deleteAgent(5) s'exécute côté serveur
 *   5. Livewire re-rend automatiquement le Blade mis à jour
 *   6. Le navigateur affiche le résultat sans rechargement de page
 */
class AgentManager extends Component
{
    /*
    |--------------------------------------------------------------------------
    | PROPRIÉTÉS PUBLIQUES — Ce sont les "données réactives" du composant.
    |
    | Toute propriété déclarée ici avec "public" est automatiquement
    | accessible dans le Blade via la variable du même nom (ex: $nom → $nom).
    | Et toute modification de ces propriétés côté PHP déclenche un re-rendu
    | automatique du Blade. C'est le cœur de la réactivité de Livewire.
    |--------------------------------------------------------------------------
    */

    // Contrôle l'affichage du formulaire d'ajout/édition
    public bool $showForm = false;

    // Indique si on est en mode édition (true) ou ajout (false)
    public bool $isEditing = false;

    // Identifiant de l'agent en cours d'édition (null si ajout)
    public ?int $editingAgentId = null;

    // Champs du formulaire — wire:model dans le Blade les synchronise
    // automatiquement avec ces propriétés à chaque frappe de l'utilisateur
    public ?int $selectedRegionId = null;
    public ?string $agence = null;
    public string $nom = '';
    public ?string $departement = null;
    public ?string $tel = null;
    public ?string $telRaw = null;
    public string $email = '';

    /*
    |--------------------------------------------------------------------------
    | RÈGLES DE VALIDATION
    |
    | Ces règles sont vérifiées lors de l'appel à $this->validate() dans save().
    | Si une règle échoue, Livewire remonte automatiquement les erreurs vers le
    | Blade via @error('nom'), @error('email'), etc. — sans aucun JavaScript.
    |--------------------------------------------------------------------------
    */
    protected array $rules = [
        'selectedRegionId' => 'required|integer|exists:regions,id',
        'nom'              => 'required|string|max:255',
        'email'            => 'required|email|max:255',
        'agence'           => 'nullable|string|max:255',
        'departement'      => 'nullable|string|max:500',
        'tel'              => 'nullable|string|max:50',
        'telRaw'           => 'nullable|string|max:50',
    ];

    /*
    |--------------------------------------------------------------------------
    | MESSAGES D'ERREUR PERSONNALISÉS (optionnel mais professionnel)
    |
    | Par défaut Laravel génère des messages en anglais comme
    | "The nom field is required." On les remplace par du français lisible.
    |--------------------------------------------------------------------------
    */
    protected array $messages = [
        'selectedRegionId.required' => 'Veuillez sélectionner une région.',
        'selectedRegionId.exists'   => 'Cette région n\'existe pas en base de données.',
        'nom.required'              => 'Le nom de l\'agent est obligatoire.',
        'email.required'            => 'L\'adresse email est obligatoire.',
        'email.email'               => 'L\'adresse email n\'est pas valide.',
    ];

    /*
    |--------------------------------------------------------------------------
    | render() — Méthode obligatoire dans tout composant Livewire
    |
    | Elle est appelée automatiquement à chaque fois que Livewire
    | doit re-rendre le composant (après chaque action, au chargement, etc.).
    | Elle charge les données nécessaires à l'affichage et les passe au Blade.
    |
    | Le with('agents') est du "eager loading" : Eloquent charge les agents
    | de chaque région en une seule requête SQL optimisée, au lieu de faire
    | une requête par région (ce qu'on appelle le problème N+1).
    |--------------------------------------------------------------------------
    */
    public function render()
    {
        return view('livewire.agent-manager', [
            // On charge toutes les régions avec leurs agents en une requête
            'regions' => Region::with('agents')->orderBy('nom')->get(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | openAddForm() — Ouvre le formulaire en mode "ajout"
    |
    | Cette méthode est appelée par wire:click="openAddForm" dans le Blade.
    | Elle réinitialise tous les champs pour éviter que les données d'une
    | édition précédente n'apparaissent dans un nouveau formulaire d'ajout.
    |--------------------------------------------------------------------------
    */
    public function openAddForm(): void
    {
        // On réinitialise toutes les propriétés du formulaire
        $this->reset([
            'selectedRegionId', 'agence', 'nom',
            'departement', 'tel', 'telRaw', 'email',
            'editingAgentId',
        ]);

        // On efface les éventuelles erreurs de validation du formulaire précédent
        $this->resetValidation();

        $this->isEditing = false;
        $this->showForm  = true;
    }

    /*
    |--------------------------------------------------------------------------
    | editAgent() — Pré-remplit le formulaire avec les données d'un agent
    |
    | Appelée par wire:click="editAgent({{ $agent->id }})" dans le Blade.
    | Livewire passe automatiquement l'ID de l'agent comme argument.
    |
    | @param int $agentId  L'identifiant de l'agent à modifier
    |--------------------------------------------------------------------------
    */
    public function editAgent(int $agentId): void
    {
        // On récupère l'agent depuis la base de données
        // findOrFail() lève une exception si l'ID n'existe pas,
        // ce qui protège contre les manipulations malveillantes
        $agent = Agent::findOrFail($agentId);

        // On pré-remplit les propriétés du formulaire avec les données existantes.
        // wire:model dans le Blade s'occupera d'afficher ces valeurs dans les champs.
        $this->editingAgentId = $agent->id;
        $this->selectedRegionId = $agent->region_id;
        $this->agence           = $agent->agence;
        $this->nom              = $agent->nom;
        $this->departement      = $agent->departement;
        $this->tel              = $agent->tel;
        $this->telRaw           = $agent->tel_raw;
        $this->email            = $agent->email;

        $this->isEditing = true;
        $this->showForm  = true;
    }

    /*
    |--------------------------------------------------------------------------
    | save() — Crée ou met à jour un agent selon le mode actuel
    |
    | Appelée par wire:submit="save" sur le formulaire dans le Blade.
    | C'est une seule méthode qui gère les deux cas (ajout et édition)
    | en se basant sur la valeur de $isEditing. C'est une bonne pratique
    | qui évite la duplication de code.
    |--------------------------------------------------------------------------
    */
    public function save(): void
    {
        // On valide les données AVANT de toucher à la base de données.
        // Si la validation échoue, Livewire arrête l'exécution ici et
        // envoie les erreurs au Blade via les directives @error().
        $donnees = $this->validate();

        if ($this->isEditing) {
            // MODE ÉDITION : on met à jour l'agent existant
            $agent = Agent::findOrFail($this->editingAgentId);
            $agent->update([
                'region_id'   => $this->selectedRegionId,
                'agence'      => $this->agence ?: null,
                'nom'         => $this->nom,
                'departement' => $this->departement,
                'tel'         => $this->tel,
                'tel_raw'     => $this->telRaw,
                'email'       => $this->email,
            ]);

            session()->flash('success', 'Agent modifié avec succès.');

        } else {
            // MODE AJOUT : on crée un nouvel agent en base de données
            Agent::create([
                'region_id'   => $this->selectedRegionId,
                'agence'      => $this->agence ?: null,
                'nom'         => $this->nom,
                'departement' => $this->departement,
                'tel'         => $this->tel,
                'tel_raw'     => $this->telRaw,
                'email'       => $this->email,
            ]);

            session()->flash('success', 'Nouvel agent ajouté avec succès.');
        }

        // Après la sauvegarde, on ferme le formulaire et on réinitialise
        $this->showForm  = false;
        $this->isEditing = false;
        $this->reset([
            'selectedRegionId', 'agence', 'nom',
            'departement', 'tel', 'telRaw', 'email',
            'editingAgentId',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | deleteAgent() — Supprime un agent de la base de données
    |
    | Appelée par wire:click="deleteAgent({{ $agent->id }})" dans le Blade.
    | La directive wire:confirm="..." dans le Blade affiche une boîte de
    | confirmation native du navigateur avant d'appeler cette méthode.
    |
    | @param int $agentId  L'identifiant de l'agent à supprimer
    |--------------------------------------------------------------------------
    */
    public function deleteAgent(int $agentId): void
    {
        // findOrFail() protège contre la suppression d'un ID inexistant
        $agent = Agent::findOrFail($agentId);
        $agent->delete();

        session()->flash('success', 'Agent supprimé avec succès.');
    }
}