<?php

namespace App\Livewire\Admin;

use App\Models\Agent;
use App\Models\Region;
use Livewire\Component;
use Livewire\WithPagination;

class AgentManager extends Component
{
    use WithPagination; // il faut l'activer dans la classe
    // Propriétés du formulaire d'ajout/modification
    // Livewire les synchronise automatiquement avec les champs HTML (wire:model)
    public $selectedRegionId = null;
    public $agentId = null;
    public $agence = '';
    public $nom = '';
    public $departement = '';
    public $tel = '';
    public $telRaw = '';
    public $email = '';
    public $color = '#94A3B8';

    // Contrôle l'affichage du formulaire
    public $showForm = false;
    public $isEditing = false;

    // Pagination et recherche
    public string $search  = '';  // terme de recherche saisi par l'utilisateur
    public int    $perPage = 10;  // nombre d'agents affichés par page

    // Validation : règles appliquées automatiquement avant toute action
    protected $rules = [
        'selectedRegionId' => 'required|exists:regions,id',
        'nom'              => 'required|string|max:255',
        'email'            => 'required|email|max:255',
        'agence'           => 'nullable|string|max:255',
        'departement'      => 'nullable|string|max:255',
        'tel'              => 'nullable|string|max:50',
        'telRaw'           => 'nullable|string|max:50',
        'color'            => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
    ];

    /** Affiche le formulaire vide pour un ajout */
    public function openAddForm()
    {
        $this->reset(['agentId', 'agence', 'nom', 'departement', 'tel', 'telRaw', 'email']);
        $this->color = '#94A3B8'; // reset() mettrait '' → invalide pour type="color"
        $this->isEditing = false;
        $this->showForm = true;
    }

    /** Charge les données d'un agent existant dans le formulaire */
    public function editAgent(int $agentId)
    {
        $agent = Agent::findOrFail($agentId);

        // On remplit les propriétés Livewire avec les valeurs de l'agent
        $this->agentId         = $agent->id;
        $this->selectedRegionId = $agent->region_id;
        $this->agence          = $agent->agence ?? '';
        $this->nom             = $agent->nom;
        $this->departement     = $agent->departement ?? '';
        $this->tel             = $agent->tel ?? '';
        $this->telRaw          = $agent->tel_raw ?? '';
        $this->email           = $agent->email;
        $this->color           = $agent->color ?? '#94A3B8';

        $this->isEditing = true;
        $this->showForm = true;
    }

    /** Sauvegarde (ajout ou modification selon $isEditing) */
    public function save()
    {
        $this->validate();

        $data = [
            'region_id' => $this->selectedRegionId,
            'agence'    => $this->agence ?: null,
            'nom'       => $this->nom,
            'departement'  => $this->departement,
            'tel'       => $this->tel,
            'tel_raw'   => $this->telRaw,
            'email'     => $this->email,
            'color'     => $this->color ?: '#94A3B8',
        ];

        if ($this->isEditing) {
            Agent::findOrFail($this->agentId)->update($data);
            session()->flash('success', 'Agent modifié avec succès.');
        } else {
            Agent::create($data);
            session()->flash('success', 'Nouvel agent ajouté.');
        }

        $this->showForm = false;
        $this->reset(['agentId', 'agence', 'nom', 'departement', 'tel', 'telRaw', 'email']);
        $this->color = '#94A3B8';
    }

    /** Réinitialise la page à 1 avant d'appliquer le nouveau terme de recherche */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /** Réinitialise la page à 1 quand l'utilisateur change le nombre de résultats par page */
    public function updatingPerPage(): void
    {
        $this->resetPage();
    }

    /** Suppression avec confirmation côté serveur */
    public function deleteAgent(int $agentId)
    {
        Agent::findOrFail($agentId)->delete();
        session()->flash('success', 'Agent supprimé.');
    }

    public function render()
    {
        // Requête principale : agents avec leur région (eager loading pour éviter N+1)
        // ->when() ajoute les filtres LIKE uniquement si $search est non vide
        $query = Agent::with('region')
            ->when(
                $this->search,
                fn ($q) => $q->where(fn ($q2) =>
                    $q2->where('nom',          'like', "%{$this->search}%")
                       ->orWhere('agence',      'like', "%{$this->search}%")
                       ->orWhere('email',       'like', "%{$this->search}%")
                       ->orWhere('departement', 'like', "%{$this->search}%")
                )
            )
            ->orderBy('nom');

        return view('livewire.admin.agent-manager', [
            // paginate() émet SELECT COUNT(*) + SELECT ... LIMIT x OFFSET y
            // Retourne un LengthAwarePaginator avec total(), hasPages(), links()
            'agents'     => $query->paginate($this->perPage),

            // Requête séparée pour le select du formulaire : doit toujours lister
            // TOUTES les régions, indépendamment de la page ou du filtre actif
            'allRegions' => Region::orderBy('nom')->get(),
        ]);
    }
}