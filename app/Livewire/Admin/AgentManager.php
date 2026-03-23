<?php

namespace App\Livewire\Admin;

use App\Models\Agent;
use App\Models\Region;
use Livewire\Component;

class AgentManager extends Component
{
    // Propriétés du formulaire d'ajout/modification
    // Livewire les synchronise automatiquement avec les champs HTML (wire:model)
    public $selectedRegionId = null;
    public $agentId = null;
    public $agence = '';
    public $nom = '';
    public $depts = '';
    public $tel = '';
    public $telRaw = '';
    public $email = '';

    // Contrôle l'affichage du formulaire
    public $showForm = false;
    public $isEditing = false;

    // Validation : règles appliquées automatiquement avant toute action
    protected $rules = [
        'selectedRegionId' => 'required|exists:regions,id',
        'nom'              => 'required|string|max:255',
        'email'            => 'required|email|max:255',
        'agence'           => 'nullable|string|max:255',
        'depts'            => 'nullable|string|max:255',
        'tel'              => 'nullable|string|max:50',
        'telRaw'           => 'nullable|string|max:50',
    ];

    /** Affiche le formulaire vide pour un ajout */
    public function openAddForm()
    {
        $this->reset(['agentId', 'agence', 'nom', 'depts', 'tel', 'telRaw', 'email']);
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
        $this->depts           = $agent->depts ?? '';
        $this->tel             = $agent->tel ?? '';
        $this->telRaw          = $agent->tel_raw ?? '';
        $this->email           = $agent->email;

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
            'depts'     => $this->depts,
            'tel'       => $this->tel,
            'tel_raw'   => $this->telRaw,
            'email'     => $this->email,
        ];

        if ($this->isEditing) {
            Agent::findOrFail($this->agentId)->update($data);
            session()->flash('success', 'Agent modifié avec succès.');
        } else {
            Agent::create($data);
            session()->flash('success', 'Nouvel agent ajouté.');
        }

        $this->showForm = false;
        $this->reset(['agentId', 'agence', 'nom', 'depts', 'tel', 'telRaw', 'email']);
    }

    /** Suppression avec confirmation côté serveur */
    public function deleteAgent(int $agentId)
    {
        Agent::findOrFail($agentId)->delete();
        session()->flash('success', 'Agent supprimé.');
    }

    public function render()
    {
        // On recharge les régions avec leurs agents à chaque rendu
        // Livewire gère ça automatiquement après chaque action
        return view('livewire.admin.agent-manager', [
            'regions' => Region::with('agents')->orderBy('name')->get(),
        ]);
    }
}