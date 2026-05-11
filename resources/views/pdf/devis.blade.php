<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'dejavusans', sans-serif;
            font-size: 10px;
            color: #1e293b;
        }

        /* ── En-tête ── */
        .entete {
            background-color: #1a3a6b;
            color: white;
            padding: 16px 20px;
            width: 100%;
        }
        .entete-inner {
            width: 100%;
            border-collapse: collapse;
        }
        .entete-inner td { vertical-align: middle; }
        .entete-inner td.droite { text-align: right; }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            letter-spacing: 4px;
            color: white;
        }
        .sous-titre {
            font-size: 11px;
            margin-top: 3px;
            color: white;
        }
        .reference {
            font-size: 15px;
            font-weight: bold;
            color: white;
        }
        .date-gen {
            font-size: 9px;
            color: #93c5fd;
            margin-top: 3px;
        }
        .badge-coffret {
            background-color: #22c55e;
            color: white;
            padding: 2px 8px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* ── Corps ── */
        .corps { padding: 16px 4px 0 4px; }

        /* ── Sections ── */
        .section { margin-bottom: 14px; }
        .section-titre {
            background-color: #e8f0fb;
            border-left: 4px solid #1a3a6b;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #1a3a6b;
            margin-bottom: 6px;
        }

        /* ── Grille ── */
        .grille {
            width: 100%;
            border-collapse: collapse;
        }
        .grille td {
            padding: 5px 8px;
            border: 1px solid #e2e8f0;
            vertical-align: top;
        }
        .grille td.label {
            width: 22%;
            font-weight: bold;
            background-color: #f8fafc;
            color: #475569;
        }
        .grille td.valeur { color: #0f172a; }

        /* ── En-têtes de tableau bleu ── */
        .th-bleu {
            background-color: #1a3a6b;
            color: white;
            padding: 5px 8px;
            font-weight: bold;
        }

        /* ── Observations ── */
        .observations {
            background-color: #fefce8;
            border: 1px solid #fde68a;
            padding: 8px 12px;
            font-style: italic;
            color: #78350f;
            font-size: 9px;
        }

        .txt-center { text-align: center; }
        .txt-right  { text-align: right; }
        .txt-bold   { font-weight: bold; }
        .mt8 { margin-top: 8px; }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════════
     EN-TÊTE BALS
     Tableau HTML pour compatibilité mPDF (inline-block non fiable en PDF)
══════════════════════════════════════════ --}}
<div class="entete">
    <table class="entete-inner">
        <tr>
            <td>
                <div class="logo-text">BALS</div>
                <div class="sous-titre">
                    Demande de Devis &mdash; Coffret
                    <span style="text-transform:capitalize;">{{ str_replace('-', ' ', $devis->type_coffret) }}</span>
                </div>
            </td>
            <td class="droite">
                <div class="reference">Réf. {{ $devis->reference }}</div>
                <div class="date-gen">Généré le {{ $devis->created_at?->format('d/m/Y à H:i') ?? now()->format('d/m/Y à H:i') }}</div>
                <div style="margin-top:5px;">
                    <span class="badge-coffret">{{ strtoupper($devis->type_coffret) }}</span>
                </div>
            </td>
        </tr>
    </table>
</div>

{{-- ══════════════════════════════════════════
     CORPS DU DEVIS
══════════════════════════════════════════ --}}
<div class="corps">

    @php $d = $devis->donnees ?? []; @endphp

    {{-- ── Section Contact ─────────────────────────────── --}}
    <div class="section">
        <div class="section-titre">01 · Informations de Contact</div>
        <table class="grille">
            <tr>
                <td class="label">Société / Distributeur</td>
                <td class="valeur">{{ $devis->distributeur ?: '—' }}</td>
                <td class="label">Contact</td>
                <td class="valeur">{{ $devis->contact ?: '—' }}</td>
            </tr>
            <tr>
                <td class="label">Installateur</td>
                <td class="valeur">{{ $devis->installateur ?: '—' }}</td>
                <td class="label">Contact inst.</td>
                <td class="valeur">{{ $devis->contact_installateur ?: '—' }}</td>
            </tr>
            <tr>
                <td class="label">Réf. Affaire</td>
                <td class="valeur">{{ $devis->affaire ?: '—' }}</td>
                <td class="label">Téléphone</td>
                <td class="valeur">{{ $devis->telephone ?: '—' }}</td>
            </tr>
            <tr>
                <td class="label">Email</td>
                <td class="valeur" colspan="3">{{ $devis->email ?: '—' }}</td>
            </tr>
        </table>
    </div>

    {{-- ── Section Caractéristiques Générales ──────────── --}}
    @if (!empty($d['montage']) || !empty($d['materiau']) || !empty($d['ip']))
    <div class="section">
        <div class="section-titre">02 · Caractéristiques Générales</div>
        <table class="grille">
            @if (!empty($d['montage']) || !empty($d['materiau']))
            <tr>
                <td class="label">Type de montage</td>
                <td class="valeur">{{ $d['montage'] ?? '—' }}</td>
                <td class="label">Matériau</td>
                <td class="valeur">{{ $d['materiau'] ?? '—' }}</td>
            </tr>
            @endif
            @if (!empty($d['ip']) || !empty($d['ik']))
            <tr>
                <td class="label">Indice de protection</td>
                <td class="valeur">{{ $d['ip'] ?? '—' }}</td>
                <td class="label">IK</td>
                <td class="valeur">{{ $d['ik'] ?? '—' }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    {{-- ── Prise Industrielle : données spécifiques ────── --}}
    @if (!empty($d['produit']) || !empty($d['amp']) || !empty($d['pol']))
    <div class="section">
        <div class="section-titre">02 · Caractéristiques Produit</div>
        <table class="grille">
            @if (!empty($d['produit']))
            <tr>
                <td class="label">Type de produit</td>
                <td class="valeur" colspan="3">{{ $d['produit'] }}</td>
            </tr>
            @endif
            <tr>
                <td class="label">Tension</td>
                <td class="valeur">{{ $d['tension'] ?? '—' }}</td>
                <td class="label">Intensité</td>
                <td class="valeur">{{ $d['amp'] ?? '—' }}</td>
            </tr>
            <tr>
                <td class="label">Polarité</td>
                <td class="valeur">{{ $d['pol'] ?? '—' }}</td>
                <td class="label">IP</td>
                <td class="valeur">{{ $d['ip'] ?? '—' }}</td>
            </tr>
            @if (!empty($d['quantite']))
            <tr>
                <td class="label">Quantité</td>
                <td class="valeur txt-bold" colspan="3">{{ $d['quantite'] }}</td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    {{-- ── Section Alimentation ─────────────────────────── --}}
    @if (!empty($d['alimentations']) && count(array_filter($d['alimentations'], fn($a) => ($a['quantite'] ?? 0) > 0)) > 0)
    <div class="section">
        <div class="section-titre">03 · Alimentation</div>
        <table class="grille">
            <tr>
                <td class="th-bleu">Type</td>
                <td class="th-bleu">Brochage</td>
                <td class="th-bleu txt-center">Quantité</td>
                <td class="th-bleu txt-center">Tension</td>
            </tr>
            @foreach ($d['alimentations'] as $alim)
                @if (($alim['quantite'] ?? 0) > 0)
                <tr>
                    <td class="valeur">{{ $alim['type'] ?? '—' }}</td>
                    <td class="valeur">{{ $alim['brochage'] ?? '—' }}</td>
                    <td class="valeur txt-center txt-bold">{{ $alim['quantite'] }}</td>
                    <td class="valeur txt-center">{{ $alim['tension'] ?? '—' }}</td>
                </tr>
                @endif
            @endforeach
        </table>
    </div>
    @endif

    {{-- ── Section Prises ───────────────────────────────── --}}
    @if (!empty($d['prises']) && count(array_filter($d['prises'], fn($p) => ($p['quantite'] ?? 0) > 0)) > 0)
    <div class="section">
        <div class="section-titre">04 · Prises de Courant</div>
        <table class="grille">
            <tr>
                <td class="th-bleu">Type</td>
                <td class="th-bleu">Brochage</td>
                <td class="th-bleu txt-center">Ampérage</td>
                <td class="th-bleu txt-center">Tension</td>
                <td class="th-bleu txt-center">Quantité</td>
            </tr>
            @foreach ($d['prises'] as $prise)
                @if (($prise['quantite'] ?? 0) > 0)
                <tr>
                    <td class="valeur">{{ $prise['type'] ?? '—' }}</td>
                    <td class="valeur">{{ $prise['brochage'] ?? '—' }}</td>
                    <td class="valeur txt-center">{{ $prise['ampere'] ?? '—' }}</td>
                    <td class="valeur txt-center">{{ $prise['tension'] ?? '—' }}</td>
                    <td class="valeur txt-center txt-bold">{{ $prise['quantite'] }}</td>
                </tr>
                @endif
            @endforeach
        </table>
    </div>
    @endif

    {{-- ── Protections & Options ────────────────────────── --}}
    @php
        $protTete   = $d['protections_tete']  ?? $d['protection_tete']  ?? null;
        $protPrises = $d['protections_prises'] ?? $d['prot_prises']      ?? null;
        $options    = $d['options'] ?? null;
    @endphp

    @if (!empty($protTete) || !empty($protPrises) || !empty($options))
    <div class="section">
        <div class="section-titre">05 · Protections &amp; Options</div>
        <table class="grille">
            @if (!empty($protTete))
            <tr>
                <td class="label">Protection de tête</td>
                <td class="valeur" colspan="3">
                    {{ is_array($protTete) ? implode(', ', $protTete) : $protTete }}
                </td>
            </tr>
            @endif
            @if (!empty($protPrises))
            <tr>
                <td class="label">Protection prises</td>
                <td class="valeur" colspan="3">
                    {{ is_array($protPrises) ? implode(', ', $protPrises) : $protPrises }}
                </td>
            </tr>
            @endif
            @if (!empty($options))
            <tr>
                <td class="label">Options</td>
                <td class="valeur" colspan="3">
                    {{ is_array($options) ? implode(', ', $options) : $options }}
                </td>
            </tr>
            @endif
        </table>
    </div>
    @endif

    {{-- ── Observations ─────────────────────────────────── --}}
    @if ($devis->observations)
    <div class="section">
        <div class="section-titre">06 · Observations</div>
        <div class="observations">{{ $devis->observations }}</div>
    </div>
    @endif

    {{-- ── Mention légale ───────────────────────────────── --}}
    <div class="section mt8" style="border-top: 1px solid #e2e8f0; padding-top: 12px;">
        <p style="font-size:9px; color:#94a3b8; text-align:center;">
            Ce document est une demande de devis générée automatiquement par le configurateur BALS.
            Il ne constitue pas un engagement contractuel. Notre équipe commerciale vous contactera sous 48h.
        </p>
    </div>

</div>

</body>
</html>
