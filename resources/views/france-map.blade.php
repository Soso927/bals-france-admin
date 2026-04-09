{{--
╔═══════════════════════════════════════════════════════════════════════════════╗
║  CARTE INTERACTIVE — RÉSEAU COMMERCIAL BALS FRANCE                          ║
║                                                                              ║
║  Cette page affiche la carte SVG de France avec les agents commerciaux.      ║
║  Les données sont chargées DYNAMIQUEMENT depuis l'API Laravel (/api/agents)  ║
║  qui elle-même lit depuis la base de données MySQL.                          ║
║                                                                              ║
║  Avantage par rapport à l'ancienne version avec localStorage :               ║
║  - Les données sont partagées entre tous les navigateurs et utilisateurs     ║
║  - Une modification faite par l'admin est visible immédiatement partout      ║
║  - C'est une vraie architecture web professionnelle                          ║
╚═══════════════════════════════════════════════════════════════════════════════╝
--}}

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carte interactive — Bals France</title>

    {{-- D3.js : bibliothèque qui permet de dessiner la carte SVG --}}
    <script src="https://cdn.jsdelivr.net/npm/d3@7"></script>

    {{-- Tailwind CSS : framework CSS utilitaire --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Police Exo 2 : charte graphique Bals --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- Feuille de style dédiée à la carte --}}
    <link rel="stylesheet" href="{{ asset('css/france-map.css') }}">
</head>
<body>

    <div class="brand-bar"></div>

    @include('livewire.layout.header')

    <div class="page-wrapper">

        <header class="page-header">
            <div class="eyebrow">Réseau commercial</div>
            <h1>Notre réseau d'agents <span>commerciaux</span></h1>
            <p class="tagline">
                Cliquez sur un département pour afficher l'agent local de votre région.
                Pour toute question : <a href="tel:+33164786080">01 64 78 60 80</a>
            </p>
        </header>

        <div class="map-grid">

            {{-- CARTE DE FRANCE --}}
            <div class="map-card">
                <div class="map-card-header">
                    <span class="map-card-title">France métropolitaine</span>
                    <span class="map-card-badge" id="region-count">Chargement...</span>
                </div>
                <div id="map-container"></div>
                <p class="map-hint">Cliquez sur un département · Double-clic pour réinitialiser</p>
            </div>

            {{-- PANNEAU LATÉRAL --}}
            <div class="side-panel">
                <div class="info-box" id="info-box">
                    <div class="placeholder">
                        <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M12 22s-8-4.5-8-11.8A8 8 0 0 1 12 2a8 8 0 0 1 8 8.2c0 7.3-8 11.8-8 11.8z"/>
                            <circle cx="12" cy="10" r="3"/>
                        </svg>
                        <span>Cliquez sur un département<br>pour afficher l'agent local</span>
                    </div>
                </div>

                <button class="reset-btn" id="reset-btn" onclick="resetMap()">
                    ↺ &nbsp; Réinitialiser la sélection
                </button>

                <div class="region-list-card">
                    <div class="region-list-header">Toutes les régions</div>
                    <ul class="region-list" id="region-list"></ul>
                </div>
            </div>
        </div>
    </div>

    <div id="tooltip"></div>

    @include('livewire.layout.footer')

    {{-- Script JavaScript dédié à la carte --}}
    <script src="{{ asset('js/france-map.js') }}"></script>

</body>
</html>