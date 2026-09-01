<?php

if (! function_exists('get_app_menu')) {
    function get_app_menu(): array
    {
        return [

            // ── Tableau de bord ──────────────────────────────
            [
                'type'  => 'link',
                'label' => 'Tableau de bord',
                'icon'  => 'bi-speedometer2',
                'url'   => '/',
                'match' => ['', 'dashboard'],
            ],

            // ── Gestion commerciale ──────────────────────────
            [
                'type'  => 'section',
                'label' => 'Gestion commerciale',
            ],
            [
                'type'  => 'link',
                'label' => 'Demandes clients',
                'icon'  => 'bi-chat-left-text',
                'url'   => 'demandes',
                'match' => ['demandes'],
            ],
            [
                'type'  => 'link',
                'label' => 'Cotations',
                'icon'  => 'bi-file-earmark-text',
                'url'   => 'cotations',
                'match' => ['cotations'],
            ],
            [
                'type'  => 'link',
                'label' => 'Départs / Planning',
                'icon'  => 'bi-calendar-event',
                'url'   => 'plannings-depart',
                'match' => ['plannings-depart'],
            ],
            [
                'type'  => 'link',
                'label' => 'Réservations',
                'icon'  => 'bi-calendar-check',
                'url'   => 'reservations',
                'match' => ['reservations'],
            ],

            // ── Finance ──────────────────────────────────────
            [
                'type'  => 'section',
                'label' => 'Finance',
            ],
            [
                'type'  => 'link',
                'label' => 'Paiements',
                'icon'  => 'bi-cash-stack',
                'url'   => 'paiements',
                'match' => ['paiements'],
            ],
            [
                'type'  => 'link',
                'label' => 'Factures',
                'icon'  => 'bi-receipt',
                'url'   => 'factures',
                'match' => ['factures'],
            ],

            // ── Analyse ──────────────────────────────────────
            [
                'type'  => 'section',
                'label' => 'Analyse',
            ],
            [
                'type'  => 'link',
                'label' => 'Rapports & statistiques',
                'icon'  => 'bi-bar-chart-line',
                'url'   => 'rapports',
                'match' => ['rapports'],
            ],

            // ── Clients & partenaires ────────────────────────
            [
                'type'  => 'section',
                'label' => 'Clients & partenaires',
            ],
            [
                'type'  => 'link',
                'label' => 'Clients',
                'icon'  => 'bi-people',
                'url'   => 'clients',
                'match' => ['clients'],
            ],
            [
                'type'  => 'link',
                'label' => 'Fournisseurs',
                'icon'  => 'bi-buildings',
                'url'   => 'fournisseurs',
                'match' => ['fournisseurs'],
            ],

            // ── Catalogue touristique ────────────────────────
            [
                'type'  => 'section',
                'label' => 'Catalogue touristique',
            ],
            [
                'type'  => 'link',
                'label' => 'Destinations',
                'icon'  => 'bi-geo-alt',
                'url'   => 'destinations',
                'match' => ['destinations'],
            ],
            [
                'type'  => 'link',
                'label' => 'Hôtels',
                'icon'  => 'bi-building',
                'url'   => 'hotels',
                'match' => ['hotels'],
            ],
            [
                'type'  => 'link',
                'label' => 'Vols',
                'icon'  => 'bi-airplane',
                'url'   => 'vols',
                'match' => ['vols'],
            ],
            [
                'type'  => 'link',
                'label' => 'Excursions',
                'icon'  => 'bi-binoculars',
                'url'   => 'excursions',
                'match' => ['excursions'],
            ],
            [
                'type'  => 'link',
                'label' => 'Transferts',
                'icon'  => 'bi-taxi-front',
                'url'   => 'transferts',
                'match' => ['transferts'],
            ],
            [
                'type'  => 'link',
                'label' => 'Croisières',
                'icon'  => 'bi-water',
                'url'   => 'croisieres',
                'match' => ['croisieres'],
            ],
            [
                'type'  => 'link',
                'label' => 'Restaurants',
                'icon'  => 'bi-cup-hot',
                'url'   => 'restaurants',
                'match' => ['restaurants'],
            ],
            [
                'type'  => 'link',
                'label' => 'Forfaits',
                'icon'  => 'bi-box-seam',
                'url'   => 'forfaits',
                'match' => ['forfaits'],
            ],
            [
                'type'  => 'link',
                'label' => 'Circuits',
                'icon'  => 'bi-signpost-split',
                'url'   => 'circuits',
                'match' => ['circuits'],
            ],
            [
                'type'  => 'link',
                'label' => 'Assurances',
                'icon'  => 'bi-shield-check',
                'url'   => 'assurances',
                'match' => ['assurances'],
            ],

            // ── Configuration ────────────────────────────────
            [
                'type'  => 'section',
                'label' => 'Configuration',
            ],
            [
                'type'  => 'link',
                'label' => 'Utilisateurs & rôles',
                'icon'  => 'bi-person-gear',
                'url'   => 'utilisateurs',
                'match' => ['utilisateurs'],
            ],
            [
                'type'  => 'link',
                'label' => 'Paramètres',
                'icon'  => 'bi-gear',
                'url'   => 'parametres',
                'match' => ['parametres'],
            ],
        ];
    }
}