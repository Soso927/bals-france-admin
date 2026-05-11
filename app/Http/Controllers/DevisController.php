<?php

namespace App\Http\Controllers;

use App\Models\Devis;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mpdf\Config\ConfigVariables;
use Mpdf\Config\FontVariables;
use Mpdf\Mpdf;

class DevisController extends Controller
{
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

    public function exportPdf(Devis $devis): \Illuminate\Http\Response
    {
        if ($devis->statut === 'nouveau') {
            $devis->update(['statut' => 'lu']);
        }

        return $this->genererEtRetourner($devis);
    }

    private function genererEtRetourner(Devis $devis): \Illuminate\Http\Response
    {
        $html = view('pdf.devis', compact('devis'))->render();

        $mpdf = $this->creerMpdf();
        $mpdf->SetHTMLFooter('
            <table width="100%" style="border-top:2px solid #1a3a6b; font-size:9px; color:#94a3b8; padding-top:5px;">
                <tr>
                    <td style="text-align:left;"><strong style="color:#1a3a6b;">BALS</strong></td>
                    <td style="text-align:center;">Demande de devis n° ' . $devis->reference . '</td>
                    <td style="text-align:right;">Page {PAGENO}/{nbpg}</td>
                </tr>
            </table>
        ');
        $mpdf->WriteHTML($html);

        $contenuPdf    = $mpdf->Output('', 'S');
        $cheminRelatif = "devis/{$devis->reference}.pdf";
        Storage::disk('local')->put($cheminRelatif, $contenuPdf);
        $devis->update(['pdf_path' => $cheminRelatif]);

        return response($contenuPdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"Devis-{$devis->reference}.pdf\"",
        ]);
    }

    private function creerMpdf(): Mpdf
    {
        $fontDirs = (new ConfigVariables())->getDefaults()['fontDir'];
        $fontData = (new FontVariables())->getDefaults()['fontdata'];

        return new Mpdf([
            'format'            => 'A4',
            'orientation'       => 'P',
            'margin_top'        => 10,
            'margin_right'      => 12,
            'margin_bottom'     => 20,
            'margin_left'       => 12,
            'default_font'      => 'dejavusans',
            'default_font_size' => 10,
            'tempDir'           => storage_path('app/mpdf-temp'),
            'fontDir'           => $fontDirs,
            'fontdata'          => $fontData,
        ]);
    }
}
