<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


/*
|--------------------------------------------------------------------------
| AUTHENTIFICATION — PUBLIC
|--------------------------------------------------------------------------
*/

$routes->get('login', 'Auth\AuthController::login');
$routes->post('login', 'Auth\AuthController::attemptLogin');

$routes->get('register', 'Auth\AuthController::register');
$routes->post('register', 'Auth\AuthController::attemptRegister');

$routes->get('logout', 'Auth\AuthController::logout');

$routes->get('forgot-password', 'Auth\AuthController::forgotPassword');
$routes->post('forgot-password', 'Auth\AuthController::attemptForgotPassword');

$routes->get(
    'reset-password/(:any)',
    'Auth\AuthController::resetPassword/$1'
);

$routes->post(
    'reset-password',
    'Auth\AuthController::attemptResetPassword'
);


/*
|--------------------------------------------------------------------------
| ESPACE CONNECTÉ
|--------------------------------------------------------------------------
|
| Toutes les routes ci-dessous nécessitent :
|
| filter => auth
|
*/

$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes) {


    /*
    |--------------------------------------------------------------------------
    | TABLEAU DE BORD
    |--------------------------------------------------------------------------
    */

    $routes->get(
        '/',
        'DashboardController::index',
        ['filter' => 'permission:dashboard.view']
    );

    $routes->get(
        'dashboard',
        'DashboardController::index',
        ['filter' => 'permission:dashboard.view']
    );


    /*
    |--------------------------------------------------------------------------
    | DEMANDES CLIENTS
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'demandes',
        'DemandeController::index',
        ['filter' => 'permission:demandes.view']
    );

    $routes->get(
        'demandes/new',
        'DemandeController::create',
        ['filter' => 'permission:demandes.create']
    );

    $routes->post(
        'demandes',
        'DemandeController::store',
        ['filter' => 'permission:demandes.create']
    );

    $routes->get(
        'demandes/(:num)',
        'DemandeController::show/$1',
        ['filter' => 'permission:demandes.view']
    );

    $routes->get(
        'demandes/(:num)/edit',
        'DemandeController::edit/$1',
        ['filter' => 'permission:demandes.edit']
    );

    $routes->post(
        'demandes/(:num)',
        'DemandeController::update/$1',
        ['filter' => 'permission:demandes.edit']
    );

    $routes->post(
        'demandes/(:num)/delete',
        'DemandeController::delete/$1',
        ['filter' => 'permission:demandes.delete']
    );

    /*
     * Conversion demande → cotation
     */
    $routes->get(
        'demandes/(:num)/convertir',
        'DemandeController::convertir/$1',
        ['filter' => 'permission:demandes.create']
    );

    $routes->post(
        'demandes/(:num)/convertir',
        'DemandeController::storeConversion/$1',
        ['filter' => 'permission:demandes.create']
    );

    /*
     * Lignes
     */
    $routes->post(
        'demandes/(:num)/lignes',
        'DemandeController::addLigne/$1',
        ['filter' => 'permission:demandes.edit']
    );

    $routes->delete(
        'demandes/(:num)/lignes/(:num)',
        'DemandeController::deleteLigne/$1/$2',
        ['filter' => 'permission:demandes.edit']
    );

    $routes->post(
        'demandes/(:num)/lignes/(:num)/delete',
        'DemandeController::deleteLigne/$1/$2',
        ['filter' => 'permission:demandes.edit']
    );

    $routes->post(
        'demandes/(:num)/tarif',
        'DemandeController::updateTarif/$1',
        ['filter' => 'permission:demandes.edit']
    );


    /*
    |--------------------------------------------------------------------------
    | COTATIONS
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'cotations',
        'CotationController::index',
        ['filter' => 'permission:cotations.view']
    );

    $routes->get(
        'cotations/new',
        'CotationController::create',
        ['filter' => 'permission:cotations.create']
    );

    $routes->post(
        'cotations',
        'CotationController::store',
        ['filter' => 'permission:cotations.create']
    );

    $routes->get(
        'cotations/(:num)',
        'CotationController::show/$1',
        ['filter' => 'permission:cotations.view']
    );

    $routes->get(
        'cotations/(:num)/edit',
        'CotationController::edit/$1',
        ['filter' => 'permission:cotations.edit']
    );

    $routes->post(
        'cotations/(:num)',
        'CotationController::update/$1',
        ['filter' => 'permission:cotations.edit']
    );

    $routes->get(
        'cotations/(:num)/delete',
        'CotationController::delete/$1',
        ['filter' => 'permission:cotations.delete']
    );

    $routes->get(
        'cotations/(:num)/print',
        'CotationController::print/$1',
        ['filter' => 'permission:cotations.print']
    );

    $routes->get(
        'cotations/(:num)/pdf',
        'CotationController::pdf/$1',
        ['filter' => 'permission:cotations.print']
    );

    $routes->post(
        'cotations/(:num)/lignes/(:num)/duplicate',
        'CotationController::duplicateLigne/$1/$2',
        ['filter' => 'permission:cotations.edit']
    );

    $routes->post(
        'cotations/(:num)/send',
        'CotationController::send/$1',
        ['filter' => 'permission:cotations.send']
    );

    $routes->post(
        'cotations/(:num)/statut',
        'CotationController::changeStatut/$1',
        ['filter' => 'permission:cotations.edit']
    );

    $routes->post(
        'cotations/(:num)/lignes',
        'CotationController::addLigne/$1',
        ['filter' => 'permission:cotations.edit']
    );

    $routes->post(
        'cotations/(:num)/lignes/(:num)',
        'CotationController::updateLigne/$1/$2',
        ['filter' => 'permission:cotations.edit']
    );

    $routes->post(
        'cotations/(:num)/lignes/(:num)/delete',
        'CotationController::deleteLigne/$1/$2',
        ['filter' => 'permission:cotations.edit']
    );

    $routes->post(
        'cotations/(:num)/recalculer',
        'CotationController::recalculer/$1',
        ['filter' => 'permission:cotations.edit']
    );

    $routes->post(
        'cotations/(:num)/convert-to-reservation',
        'CotationController::convertToReservation/$1',
        ['filter' => 'permission:cotations.send']
    );


    /*
    |--------------------------------------------------------------------------
    | RÉSERVATIONS
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'reservations',
        'ReservationController::index',
        ['filter' => 'permission:reservations.view']
    );

    $routes->get(
        'reservations/new',
        'ReservationController::create',
        ['filter' => 'permission:reservations.create']
    );

    $routes->post(
        'reservations',
        'ReservationController::store',
        ['filter' => 'permission:reservations.create']
    );

    $routes->get(
        'reservations/(:num)',
        'ReservationController::show/$1',
        ['filter' => 'permission:reservations.view']
    );

    $routes->get(
        'reservations/(:num)/edit',
        'ReservationController::edit/$1',
        ['filter' => 'permission:reservations.edit']
    );

    $routes->post(
        'reservations/(:num)',
        'ReservationController::update/$1',
        ['filter' => 'permission:reservations.edit']
    );

    $routes->get(
        'reservations/(:num)/delete',
        'ReservationController::delete/$1',
        ['filter' => 'permission:reservations.delete']
    );

    $routes->post(
        'reservations/(:num)/statut',
        'ReservationController::changeStatut/$1',
        ['filter' => 'permission:reservations.edit']
    );

    $routes->post(
        'reservations/(:num)/confirm',
        'ReservationController::confirm/$1',
        ['filter' => 'permission:reservations.edit']
    );

    $routes->post(
        'reservations/(:num)/cancel',
        'ReservationController::cancel/$1',
        ['filter' => 'permission:reservations.edit']
    );

    $routes->get(
        'reservations/planning/(:num)',
        'ReservationController::planning/$1',
        ['filter' => 'permission:reservations.view']
    );


    /*
    |--------------------------------------------------------------------------
    | PLANNING / DÉPARTS
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'plannings-depart',
        'PlanningDepartController::index',
        ['filter' => 'permission:plannings.view']
    );

    $routes->get(
        'plannings-depart/new',
        'PlanningDepartController::new',
        ['filter' => 'permission:plannings.create']
    );

    $routes->post(
        'plannings-depart',
        'PlanningDepartController::create',
        ['filter' => 'permission:plannings.create']
    );

    $routes->get(
        'plannings-depart/(:num)',
        'PlanningDepartController::show/$1',
        ['filter' => 'permission:plannings.view']
    );

    $routes->get(
        'plannings-depart/(:num)/edit',
        'PlanningDepartController::edit/$1',
        ['filter' => 'permission:plannings.edit']
    );

    $routes->post(
        'plannings-depart/(:num)',
        'PlanningDepartController::update/$1',
        ['filter' => 'permission:plannings.edit']
    );

    $routes->post(
        'plannings-depart/(:num)/delete',
        'PlanningDepartController::delete/$1',
        ['filter' => 'permission:plannings.delete']
    );

    $routes->get(
        'plannings-depart/available',
        'PlanningDepartController::available',
        ['filter' => 'permission:plannings.view']
    );


    /*
    |--------------------------------------------------------------------------
    | CLIENTS
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'clients',
        'ClientController::index',
        ['filter' => 'permission:clients.view']
    );

    $routes->get(
        'clients/new',
        'ClientController::create',
        ['filter' => 'permission:clients.create']
    );

    $routes->post(
        'clients',
        'ClientController::store',
        ['filter' => 'permission:clients.create']
    );

    $routes->get(
        'clients/(:num)',
        'ClientController::show/$1',
        ['filter' => 'permission:clients.view']
    );

    $routes->get(
        'clients/(:num)/edit',
        'ClientController::edit/$1',
        ['filter' => 'permission:clients.edit']
    );

    $routes->post(
        'clients/(:num)',
        'ClientController::update/$1',
        ['filter' => 'permission:clients.edit']
    );

    $routes->get(
        'clients/(:num)/delete',
        'ClientController::delete/$1',
        ['filter' => 'permission:clients.delete']
    );

    $routes->post(
        'clients/quick-store',
        'ClientController::quickStore',
        ['filter' => 'permission:clients.create']
    );


    /*
    |--------------------------------------------------------------------------
    | FOURNISSEURS
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'fournisseurs',
        'FournisseurController::index',
        ['filter' => 'permission:fournisseurs.view']
    );

    $routes->get(
        'fournisseurs/new',
        'FournisseurController::create',
        ['filter' => 'permission:fournisseurs.create']
    );

    $routes->post(
        'fournisseurs',
        'FournisseurController::store',
        ['filter' => 'permission:fournisseurs.create']
    );

    $routes->get(
        'fournisseurs/(:num)',
        'FournisseurController::show/$1',
        ['filter' => 'permission:fournisseurs.view']
    );

    $routes->get(
        'fournisseurs/(:num)/edit',
        'FournisseurController::edit/$1',
        ['filter' => 'permission:fournisseurs.edit']
    );

    $routes->post(
        'fournisseurs/(:num)',
        'FournisseurController::update/$1',
        ['filter' => 'permission:fournisseurs.edit']
    );

    $routes->get(
        'fournisseurs/(:num)/delete',
        'FournisseurController::delete/$1',
        ['filter' => 'permission:fournisseurs.delete']
    );


    /*
    |--------------------------------------------------------------------------
    | CATALOGUE TOURISTIQUE
    |--------------------------------------------------------------------------
    */

    foreach ([

        'destinations' => 'DestinationController',
        'hotels'       => 'HotelController',
        'vols'         => 'VolController',
        'excursions'   => 'ExcursionController',
        'transferts'   => 'TransfertController',
        'croisieres'   => 'CroisiereController',
        'forfaits'     => 'ForfaitController',
        'circuits'     => 'CircuitController',

    ] as $uri => $controller) {

        /*
         * Liste
         */
        $routes->get(
            $uri,
            $controller . '::index',
            ['filter' => 'permission:' . $uri . '.view']
        );

        /*
         * Création
         */
        $routes->get(
            $uri . '/new',
            $controller . '::create',
            ['filter' => 'permission:' . $uri . '.create']
        );

        $routes->post(
            $uri,
            $controller . '::store',
            ['filter' => 'permission:' . $uri . '.create']
        );

        /*
         * Détails
         */
        $routes->get(
            $uri . '/(:num)',
            $controller . '::show/$1',
            ['filter' => 'permission:' . $uri . '.view']
        );

        /*
         * Modification
         */
        $routes->get(
            $uri . '/(:num)/edit',
            $controller . '::edit/$1',
            ['filter' => 'permission:' . $uri . '.edit']
        );

        $routes->post(
            $uri . '/(:num)',
            $controller . '::update/$1',
            ['filter' => 'permission:' . $uri . '.edit']
        );

        /*
         * Suppression
         */
        $routes->get(
            $uri . '/(:num)/delete',
            $controller . '::delete/$1',
            ['filter' => 'permission:' . $uri . '.delete']
        );
    }


    /*
    |--------------------------------------------------------------------------
    | QUICK STORE DESTINATION
    |--------------------------------------------------------------------------
    */

    $routes->post(
        'destinations/quick-store',
        'DestinationController::quickStore',
        ['filter' => 'permission:destinations.create']
    );


    /*
    |--------------------------------------------------------------------------
    | RESTAURANTS
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'restaurants',
        'RestaurantController::index',
        ['filter' => 'permission:restaurants.view']
    );

    $routes->get(
        'restaurants/new',
        'RestaurantController::create',
        ['filter' => 'permission:restaurants.create']
    );

    $routes->post(
        'restaurants',
        'RestaurantController::store',
        ['filter' => 'permission:restaurants.create']
    );

    $routes->get(
        'restaurants/(:num)/edit',
        'RestaurantController::edit/$1',
        ['filter' => 'permission:restaurants.edit']
    );

    $routes->post(
        'restaurants/(:num)',
        'RestaurantController::update/$1',
        ['filter' => 'permission:restaurants.edit']
    );

    $routes->get(
        'restaurants/(:num)/delete',
        'RestaurantController::delete/$1',
        ['filter' => 'permission:restaurants.delete']
    );


    /*
    |--------------------------------------------------------------------------
    | ASSURANCES
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'assurances',
        'AssuranceController::index',
        ['filter' => 'permission:assurances.view']
    );

    $routes->get(
        'assurances/create',
        'AssuranceController::create',
        ['filter' => 'permission:assurances.create']
    );

    $routes->post(
        'assurances',
        'AssuranceController::store',
        ['filter' => 'permission:assurances.create']
    );

    $routes->get(
        'assurances/(:num)',
        'AssuranceController::show/$1',
        ['filter' => 'permission:assurances.view']
    );

    $routes->get(
        'assurances/(:num)/edit',
        'AssuranceController::edit/$1',
        ['filter' => 'permission:assurances.edit']
    );

    $routes->post(
        'assurances/(:num)',
        'AssuranceController::update/$1',
        ['filter' => 'permission:assurances.edit']
    );

    $routes->post(
        'assurances/(:num)/delete',
        'AssuranceController::delete/$1',
        ['filter' => 'permission:assurances.delete']
    );


    /*
    |--------------------------------------------------------------------------
    | FACTURES
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'factures',
        'FactureController::index',
        ['filter' => 'permission:factures.view']
    );

    $routes->get(
        'factures/create',
        'FactureController::create',
        ['filter' => 'permission:factures.create']
    );

    $routes->post(
        'factures',
        'FactureController::store',
        ['filter' => 'permission:factures.create']
    );

    $routes->get(
        'factures/(:num)',
        'FactureController::show/$1',
        ['filter' => 'permission:factures.view']
    );

    $routes->get(
        'factures/(:num)/edit',
        'FactureController::edit/$1',
        ['filter' => 'permission:factures.edit']
    );

    $routes->post(
        'factures/(:num)',
        'FactureController::update/$1',
        ['filter' => 'permission:factures.edit']
    );

    $routes->post(
        'factures/(:num)/delete',
        'FactureController::delete/$1',
        ['filter' => 'permission:factures.delete']
    );

    $routes->post(
        'factures/(:num)/lignes',
        'FactureController::addLigne/$1',
        ['filter' => 'permission:factures.edit']
    );

    $routes->post(
        'factures/(:num)/lignes/(:num)/delete',
        'FactureController::deleteLigne/$1/$2',
        ['filter' => 'permission:factures.edit']
    );

    $routes->get(
        'factures/(:num)/print',
        'FactureController::print/$1',
        ['filter' => 'permission:factures.print']
    );

    $routes->get(
        'reservations/(:num)/facture',
        'FactureController::fromReservation/$1',
        ['filter' => 'permission:factures.create']
    );


    /*
    |--------------------------------------------------------------------------
    | PAIEMENTS
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'paiements',
        'PaiementController::index',
        ['filter' => 'permission:paiements.view']
    );

    $routes->post(
        'paiements',
        'PaiementController::store',
        ['filter' => 'permission:paiements.create']
    );

    $routes->post(
        'paiements/(:num)/annuler',
        'PaiementController::annuler/$1',
        ['filter' => 'permission:paiements.delete']
    );


    /*
    |--------------------------------------------------------------------------
    | RAPPORTS & STATISTIQUES
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'rapports',
        'RapportController::index',
        ['filter' => 'permission:rapports.view']
    );

    $routes->get(
        'rapports/dashboard',
        'RapportController::dashboard',
        ['filter' => 'permission:rapports.view']
    );

    $routes->get(
        'rapports/cotations',
        'RapportController::cotations',
        ['filter' => 'permission:rapports.view']
    );

    $routes->get(
        'rapports/reservations',
        'RapportController::reservations',
        ['filter' => 'permission:rapports.view']
    );

    $routes->get(
        'rapports/clients',
        'RapportController::clients',
        ['filter' => 'permission:rapports.view']
    );

    $routes->get(
        'rapports/chiffre-affaires',
        'RapportController::chiffreAffaires',
        ['filter' => 'permission:rapports.view']
    );

    $routes->get(
        'rapports/marges',
        'RapportController::marges',
        ['filter' => 'permission:rapports.view']
    );


    /*
    |--------------------------------------------------------------------------
    | UTILISATEURS & RÔLES
    |--------------------------------------------------------------------------
    */

    $routes->group(
        'utilisateurs',
        static function (RouteCollection $routes) {

            $routes->get(
                '/',
                'UtilisateurController::index',
                ['filter' => 'permission:utilisateurs.view']
            );

            $routes->get(
                'new',
                'UtilisateurController::create',
                ['filter' => 'permission:utilisateurs.create']
            );

            $routes->post(
                '/',
                'UtilisateurController::store',
                ['filter' => 'permission:utilisateurs.create']
            );

            $routes->get(
                '(:num)/edit',
                'UtilisateurController::edit/$1',
                ['filter' => 'permission:utilisateurs.edit']
            );

            $routes->post(
                '(:num)',
                'UtilisateurController::update/$1',
                ['filter' => 'permission:utilisateurs.edit']
            );

            $routes->get(
                '(:num)/delete',
                'UtilisateurController::delete/$1',
                ['filter' => 'permission:utilisateurs.delete']
            );
        }
    );

    $routes->group(
        'roles',
        static function (RouteCollection $routes) {

        $routes->get(
            '/',
            'RoleController::index',
            ['filter' => 'permission:utilisateurs.permissions']
        );

        $routes->get(
            'new',
            'RoleController::create',
            ['filter' => 'permission:utilisateurs.permissions']
        );

        $routes->post(
            '/',
            'RoleController::store',
            ['filter' => 'permission:utilisateurs.permissions']
        );

        $routes->get(
            '(:num)/edit',
            'RoleController::edit/$1',
            ['filter' => 'permission:utilisateurs.permissions']
        );

        $routes->post(
            '(:num)',
            'RoleController::update/$1',
            ['filter' => 'permission:utilisateurs.permissions']
        );

        $routes->get(
            '(:num)/delete',
            'RoleController::delete/$1',
            ['filter' => 'permission:utilisateurs.permissions']
        );

        $routes->get(
            '(:num)/permissions',
            'RolePermissionController::edit/$1',
            ['filter' => 'permission:utilisateurs.permissions']
        );

        $routes->post(
            '(:num)/permissions',
            'RolePermissionController::update/$1',
            ['filter' => 'permission:utilisateurs.permissions']
        );
    }
);


    /*
    |--------------------------------------------------------------------------
    | DEVISES
    |--------------------------------------------------------------------------
    */

    $routes->group(
        'devises',
        static function (RouteCollection $routes) {

            $routes->get(
                '/',
                'DeviseController::index',
                ['filter' => 'permission:devises.view']
            );

            $routes->get(
                'new',
                'DeviseController::new',
                ['filter' => 'permission:devises.create']
            );

            $routes->post(
                '/',
                'DeviseController::create',
                ['filter' => 'permission:devises.create']
            );

            $routes->get(
                '(:num)/edit',
                'DeviseController::edit/$1',
                ['filter' => 'permission:devises.edit']
            );

            $routes->post(
                '(:num)',
                'DeviseController::update/$1',
                ['filter' => 'permission:devises.edit']
            );

            $routes->post(
                '(:num)/delete',
                'DeviseController::delete/$1',
                ['filter' => 'permission:devises.delete']
            );

            $routes->post(
                '(:num)/set-default',
                'DeviseController::setDefault/$1',
                ['filter' => 'permission:devises.edit']
            );
        }
    );


    /*
    |--------------------------------------------------------------------------
    | PARAMÈTRES
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'parametres',
        'ParametresController::index',
        ['filter' => 'permission:parametres.view']
    );

    $routes->post(
        'parametres/update',
        'ParametresController::update',
        ['filter' => 'permission:parametres.edit']
    );

    $routes->post(
        'parametres/logo/delete',
        'ParametresController::deleteLogo',
        ['filter' => 'permission:parametres.edit']
    );


    /*
    |--------------------------------------------------------------------------
    | PARAMÈTRES — TAXES
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'parametres/taxes',
        'ParametresController::taxes',
        ['filter' => 'permission:parametres.view']
    );

    $routes->post(
        'parametres/taxes',
        'ParametresController::storeTaxe',
        ['filter' => 'permission:parametres.edit']
    );


    /*
    |--------------------------------------------------------------------------
    | PARAMÈTRES — COTATIONS
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'parametres/cotations',
        'ParametresController::cotations',
        ['filter' => 'permission:parametres.view']
    );

    $routes->post(
        'parametres/cotations',
        'ParametresController::updateCotations',
        ['filter' => 'permission:parametres.edit']
    );


    /*
    |--------------------------------------------------------------------------
    | PARAMÈTRES — CONDITIONS COMMERCIALES
    |--------------------------------------------------------------------------
    */

    $routes->get(
        'parametres/conditions',
        'ParametresController::conditions',
        ['filter' => 'permission:parametres.view']
    );

    $routes->post(
        'parametres/conditions',
        'ParametresController::updateConditions',
        ['filter' => 'permission:parametres.edit']
    );
});