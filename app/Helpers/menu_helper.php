<?php

if (! function_exists('get_app_menu')) {

    function get_app_menu(): array
    {
        return [

            // ==================================================
            // TABLEAU DE BORD
            // ==================================================
            [
                'type'       => 'link',
                'label'      => 'Tableau de bord',
                'icon'       => 'bi-speedometer2',
                'url'        => '/',
                'match'      => ['', 'dashboard'],
                'permission' => 'dashboard.view',
            ],


            // ==================================================
            // GESTION COMMERCIALE
            // ==================================================
            [
                'type'  => 'section',
                'label' => 'Gestion commerciale',
            ],

            [
                'type'       => 'link',
                'label'      => 'Demandes clients',
                'icon'       => 'bi-chat-left-text',
                'url'        => 'demandes',
                'match'      => ['demandes'],
                'permission' => 'demandes.view',
            ],

            [
                'type'       => 'link',
                'label'      => 'Cotations',
                'icon'       => 'bi-file-earmark-text',
                'url'        => 'cotations',
                'match'      => ['cotations'],
                'permission' => 'cotations.view',
            ],

            [
                'type'       => 'link',
                'label'      => 'Départs / Planning',
                'icon'       => 'bi-calendar-event',
                'url'        => 'plannings-depart',
                'match'      => ['plannings-depart'],
                'permission' => 'plannings.view',
            ],

            [
                'type'       => 'link',
                'label'      => 'Réservations',
                'icon'       => 'bi-calendar-check',
                'url'        => 'reservations',
                'match'      => ['reservations'],
                'permission' => 'reservations.view',
            ],


            // ==================================================
            // FINANCE
            // ==================================================
            [
                'type'  => 'section',
                'label' => 'Finance',
            ],

            [
                'type'       => 'link',
                'label'      => 'Paiements',
                'icon'       => 'bi-cash-stack',
                'url'        => 'paiements',
                'match'      => ['paiements'],
                'permission' => 'paiements.view',
            ],

            [
                'type'       => 'link',
                'label'      => 'Factures',
                'icon'       => 'bi-receipt',
                'url'        => 'factures',
                'match'      => ['factures'],
                'permission' => 'factures.view',
            ],


            // ==================================================
            // ANALYSE
            // ==================================================
            [
                'type'  => 'section',
                'label' => 'Analyse',
            ],

            [
                'type'       => 'link',
                'label'      => 'Rapports & statistiques',
                'icon'       => 'bi-bar-chart-line',
                'url'        => 'rapports',
                'match'      => ['rapports'],
                'permission' => 'rapports.view',
            ],


            // ==================================================
            // CLIENTS & PARTENAIRES
            // ==================================================
            [
                'type'  => 'section',
                'label' => 'Clients & partenaires',
            ],

            [
                'type'       => 'link',
                'label'      => 'Clients',
                'icon'       => 'bi-people',
                'url'        => 'clients',
                'match'      => ['clients'],
                'permission' => 'clients.view',
            ],

            [
                'type'       => 'link',
                'label'      => 'Fournisseurs',
                'icon'       => 'bi-buildings',
                'url'        => 'fournisseurs',
                'match'      => ['fournisseurs'],
                'permission' => 'fournisseurs.view',
            ],


            // ==================================================
            // CATALOGUE TOURISTIQUE
            // ==================================================
            [
                'type'  => 'section',
                'label' => 'Catalogue touristique',
            ],

            [
                'type'       => 'link',
                'label'      => 'Destinations',
                'icon'       => 'bi-geo-alt',
                'url'        => 'destinations',
                'match'      => ['destinations'],
                'permission' => 'destinations.view',
            ],

            [
                'type'       => 'link',
                'label'      => 'Hôtels',
                'icon'       => 'bi-building',
                'url'        => 'hotels',
                'match'      => ['hotels'],
                'permission' => 'hotels.view',
            ],

            [
                'type'       => 'link',
                'label'      => 'Vols',
                'icon'       => 'bi-airplane',
                'url'        => 'vols',
                'match'      => ['vols'],
                'permission' => 'vols.view',
            ],

            [
                'type'       => 'link',
                'label'      => 'Excursions',
                'icon'       => 'bi-binoculars',
                'url'        => 'excursions',
                'match'      => ['excursions'],
                'permission' => 'excursions.view',
            ],

            [
                'type'       => 'link',
                'label'      => 'Transferts',
                'icon'       => 'bi-taxi-front',
                'url'        => 'transferts',
                'match'      => ['transferts'],
                'permission' => 'transferts.view',
            ],

            [
                'type'       => 'link',
                'label'      => 'Croisières',
                'icon'       => 'bi-water',
                'url'        => 'croisieres',
                'match'      => ['croisieres'],
                'permission' => 'croisieres.view',
            ],

            [
                'type'       => 'link',
                'label'      => 'Restaurants',
                'icon'       => 'bi-cup-hot',
                'url'        => 'restaurants',
                'match'      => ['restaurants'],
                'permission' => 'restaurants.view',
            ],

            [
                'type'       => 'link',
                'label'      => 'Forfaits',
                'icon'       => 'bi-box-seam',
                'url'        => 'forfaits',
                'match'      => ['forfaits'],
                'permission' => 'forfaits.view',
            ],

            [
                'type'       => 'link',
                'label'      => 'Circuits',
                'icon'       => 'bi-signpost-split',
                'url'        => 'circuits',
                'match'      => ['circuits'],
                'permission' => 'circuits.view',
            ],

            // [
            //     'type'       => 'link',
            //     'label'      => 'Assurances',
            //     'icon'       => 'bi-shield-check',
            //     'url'        => 'assurances',
            //     'match'      => ['assurances'],
            //     'permission' => 'assurances.view',
            // ],

            [
                'type'       => 'link',
                'label'      => 'Devises',
                'icon'       => 'bi-currency-exchange',
                'url'        => 'devises',
                'match'      => ['devises'],
                'permission' => 'devises.view',
            ],


            // ==================================================
            // CONFIGURATION
            // ==================================================
            [
                'type'  => 'section',
                'label' => 'Configuration',
            ],

            [
                'type'       => 'link',
                'label'      => 'Utilisateurs & rôles',
                'icon'       => 'bi-person-gear',
                'url'        => 'utilisateurs',
                'match'      => ['utilisateurs'],
                'permission' => 'utilisateurs.view',
            ],

            [
                'type'       => 'link',
                'label'      => 'Paramètres',
                'icon'       => 'bi-gear',
                'url'        => 'parametres',
                'match'      => ['parametres'],
                'permission' => 'parametres.view',
            ],
        ];
    }
}


/**
 * ============================================================
 * FILTRAGE DU MENU
 * ============================================================
 */
if (! function_exists('filterAppMenu')) {

    function filterAppMenu(array $menu): array
    {
        $filtered = [];

        foreach ($menu as $item) {

            if (! is_array($item)) {
                continue;
            }

            $type = $item['type'] ?? 'link';


            // --------------------------------------------------
            // SECTION
            // --------------------------------------------------
            if ($type === 'section') {
                $filtered[] = $item;
                continue;
            }


            // --------------------------------------------------
            // PERMISSION
            // --------------------------------------------------
            if (! empty($item['permission'])) {

                if (! can($item['permission'])) {
                    continue;
                }
            }

            $filtered[] = $item;
        }


        $final = [];
        $count = count($filtered);

        for ($i = 0; $i < $count; $i++) {

            $item = $filtered[$i];

            if (($item['type'] ?? 'link') !== 'section') {
                $final[] = $item;
                continue;
            }

            $hasAccessibleLink = false;

            for ($j = $i + 1; $j < $count; $j++) {

                if (($filtered[$j]['type'] ?? 'link') === 'section') {
                    break;
                }

                if (($filtered[$j]['type'] ?? 'link') === 'link') {
                    $hasAccessibleLink = true;
                    break;
                }
            }


            if ($hasAccessibleLink) {
                $final[] = $item;
            }
        }

        return $final;
    }
}


if (! function_exists('get_filtered_app_menu')) {

    function get_filtered_app_menu(): array
    {
        return filterAppMenu(
            get_app_menu()
        );
    }
}