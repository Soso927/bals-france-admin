<?php

namespace App\Livewire\Admin;

use App\Models\Devis;
use Livewire\Component;
use Livewire\WithPagination;

class DevisManager extends Component
{
    use WithPagination;

    public string $search        = '';
    public string $filtreType    = '';
    public string $filtreStatut  = '';
    public ?int   $devisSelectionne = null;

    public function updatingSearch():        void { $this->resetPage(); }
    public function updatingFiltreType():    void { $this->resetPage(); }
    public function updatingFiltreStatut():  void { $this->resetPage(); }
    public function updatedPage():           void { $this->devisSelectionne = null; }

    public function voirDetail(int $id): void
    {
        $this->devisSelectionne = $id;
    }

    public function fermerDetail(): void
    {
        $this->devisSelectionne = null;
    }

    public function changerStatut(int $id, string $statut): void
    {
        abort_unless(in_array($statut, ['nouveau', 'lu', 'traité']), 422);
        Devis::findOrFail($id)->update(['statut' => $statut]);
    }

    public function supprimer(int $id): void
    {
        Devis::findOrFail($id)->delete();
        if ($this->devisSelectionne === $id) {
            $this->devisSelectionne = null;
        }
        session()->flash('success', 'Devis supprimé.');
    }

    public function render()
    {
        $query = Devis::query()
            ->when($this->search, fn ($q) => $q->where(fn ($q2) =>
                $q2->where('distributeur', 'like', "%{$this->search}%")
                   ->orWhere('contact',    'like', "%{$this->search}%")
                   ->orWhere('email',      'like', "%{$this->search}%")
                   ->orWhere('affaire',    'like', "%{$this->search}%")
            ))
            ->when($this->filtreType,   fn ($q) => $q->where('type_coffret', $this->filtreType))
            ->when($this->filtreStatut, fn ($q) => $q->where('statut', $this->filtreStatut))
            ->latest();

        return view('livewire.admin.devis-manager', [
            'devis'       => $query->paginate(15),
            'devisDetail' => $this->devisSelectionne ? Devis::find($this->devisSelectionne) : null,
            'types'       => Devis::select('type_coffret')->distinct()->orderBy('type_coffret')->pluck('type_coffret'),
        ]);
    }
}