<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DevisController extends Controller
{
    /**
     * Reçoit le JSON du configurateur, sauvegarde, génère le PDF et le retourne.
     * Route publique (même protection CSRF que l'API).
     */
    public function store(Request $request): \Illuminate\Http\Response
    {
        $validated = $request->validate([
            'type_coffret'         => 'required|string|max:100',
            'distributeur'         => 'nullable|string|max:255',
            'societe'              => 'nullable|string|max:255',
            'contact'              => 'nullable|string|max:255',
            'installateur'         => 'nullable|string|max:255',
            'contact_installateur' => 'nullable|string|max:255',
            'affaire'              => 'nullable|string|max:255',
            'email'                => 'nullable|email|max:255',
            'telephone'            => 'nullable|string|max:50',
            'donnees'              => 'nullable|array',
            'observations'         => 'nullable|string',
        ]);

        // JS envoie 'societe', la BDD stocke 'distributeur'
        $validated['distributeur'] = $validated['distributeur'] ?? $validated['societe'] ?? null;
        unset($validated['societe']);

        $validated['statut'] = 'nouveau';

        $devis = Devis::create($validated);

        return $this->genererEtRetourner($devis);
    }

    /**
     * Permet à l'admin de télécharger ou régénérer le PDF d'un devis existant.
     */
    public function exportPdf(Devis $devis): \Illuminate\Http\Response
    {
        if ($devis->statut === 'nouveau') {
            $devis->update(['statut' => 'lu']);
        }

        return $this->genererEtRetourner($devis);
    }

    private function genererEtRetourner(Devis $devis): \Illuminate\Http\Response
    {
        $pdf = Pdf::loadView('pdf.devis', compact('devis'))
                  ->setPaper('A4', 'portrait');

        $cheminRelatif = "devis/{$devis->reference}.pdf";
        Storage::disk('local')->put($cheminRelatif, $pdf->output());
        $devis->update(['pdf_path' => $cheminRelatif]);

        return $pdf->download("Devis-{$devis->reference}.pdf");
    }
}
