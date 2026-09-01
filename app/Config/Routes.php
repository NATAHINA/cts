<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ---------------------------------------------------------------------
// AUTHENTIFICATION (PUBLIC)
// ---------------------------------------------------------------------

$routes->get('login', 'Auth\AuthController::login');
$routes->post('login', 'Auth\AuthController::attemptLogin');

$routes->get('register', 'Auth\AuthController::register');
$routes->post('register', 'Auth\AuthController::attemptRegister');

$routes->get('logout', 'Auth\AuthController::logout');

$routes->get('forgot-password', 'Auth\AuthController::forgotPassword');
$routes->post('forgot-password', 'Auth\AuthController::attemptForgotPassword');

$routes->get('reset-password/(:any)', 'Auth\AuthController::resetPassword/$1');
$routes->post('reset-password', 'Auth\AuthController::attemptResetPassword');


// ---------------------------------------------------------------------
// ESPACE CONNECTÉ
// Filtre : auth
// ---------------------------------------------------------------------

$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes) {

    // =============================================================
    // TABLEAU DE BORD
    // =============================================================

    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');


    // =============================================================
    // GESTION COMMERCIALE
    // =============================================================


    // -------------------------------------------------------------
    // DEMANDES CLIENTS
    // -------------------------------------------------------------

    $routes->get('demandes', 'DemandeController::index');
    $routes->get('demandes/new', 'DemandeController::create');
    $routes->post('demandes', 'DemandeController::store');

    $routes->get(
        'demandes/(:num)',
        'DemandeController::show/$1'
    );

    $routes->get(
        'demandes/(:num)/edit',
        'DemandeController::edit/$1'
    );

    $routes->post(
        'demandes/(:num)',
        'DemandeController::update/$1'
    );

    $routes->post(
        'demandes/(:num)/delete',
        'DemandeController::delete/$1'
    );

    // Conversion de la demande en cotation
    $routes->get('demandes/(:num)/convertir', 'DemandeController::convertir/$1');
    $routes->post('demandes/(:num)/convertir', 'DemandeController::storeConversion/$1');

    // Dans Config/Routes.php
    $routes->post('demandes/(:num)/lignes', 'DemandeController::addLigne/$1');
    $routes->delete('demandes/(:num)/lignes/(:num)', 'DemandeController::deleteLigne/$1/$2');
    $routes->post('demandes/(:num)/lignes/(:num)/delete', 'DemandeController::deleteLigne/$1/$2');
    $routes->post('demandes/(:num)/tarif', 'DemandeController::updateTarif/$1');

    // -------------------------------------------------------------
    // COTATIONS
    // -------------------------------------------------------------

    $routes->get('cotations', 'CotationController::index');

    $routes->get(
        'cotations/new',
        'CotationController::create'
    );

    $routes->post(
        'cotations',
        'CotationController::store'
    );

    // Afficher une cotation
    $routes->get(
        'cotations/(:num)',
        'CotationController::show/$1'
    );

    // Modifier
    $routes->get(
        'cotations/(:num)/edit',
        'CotationController::edit/$1'
    );

    $routes->post(
        'cotations/(:num)',
        'CotationController::update/$1'
    );

    // Supprimer
    $routes->get(
        'cotations/(:num)/delete',
        'CotationController::delete/$1'
    );

    // Impression
    $routes->get(
        'cotations/(:num)/print',
        'CotationController::print/$1'
    );

    // Génération PDF
    $routes->get(
        'cotations/(:num)/pdf',
        'CotationController::pdf/$1'
    );

    // Dupliquer une ligne
    $routes->post(
        'cotations/(:num)/lignes/(:num)/duplicate',
        'CotationController::duplicateLigne/$1/$2'
    );

    // Envoyer au client
    $routes->post(
        'cotations/(:num)/send',
        'CotationController::send/$1'
    );

    // Modifier le statut
    $routes->post(
        'cotations/(:num)/statut',
        'CotationController::changeStatut/$1'
    );

    // Ajouter une ligne de prestation
    $routes->post(
        'cotations/(:num)/lignes',
        'CotationController::addLigne/$1'
    );

    // Modifier une ligne
    $routes->post(
        'cotations/(:num)/lignes/(:num)',
        'CotationController::updateLigne/$1/$2'
    );

    // Supprimer une ligne
    $routes->post(
        'cotations/(:num)/lignes/(:num)/delete',
        'CotationController::deleteLigne/$1/$2'
    );

    // Recalculer les montants
    $routes->post(
        'cotations/(:num)/recalculer',
        'CotationController::recalculer/$1'
    );

    // Convertir une cotation acceptée en réservation
    $routes->post(
        'cotations/(:num)/convert-to-reservation',
        'CotationController::convertToReservation/$1'
    );


    // -------------------------------------------------------------
    // RÉSERVATIONS
    // -------------------------------------------------------------

    $routes->get(
        'reservations',
        'ReservationController::index'
    );

    $routes->get(
        'reservations/new',
        'ReservationController::create'
    );

    $routes->post(
        'reservations',
        'ReservationController::store'
    );

    $routes->get(
        'reservations/(:num)',
        'ReservationController::show/$1'
    );

    $routes->get(
        'reservations/(:num)/edit',
        'ReservationController::edit/$1'
    );

    $routes->post(
        'reservations/(:num)',
        'ReservationController::update/$1'
    );

    $routes->get(
        'reservations/(:num)/delete',
        'ReservationController::delete/$1'
    );

    // Modifier statut réservation
    $routes->post(
        'reservations/(:num)/statut',
        'ReservationController::changeStatut/$1'
    );

    // Confirmer une réservation
    $routes->post(
        'reservations/(:num)/confirm',
        'ReservationController::confirm/$1'
    );

    // Annuler une réservation
    $routes->post(
        'reservations/(:num)/cancel',
        'ReservationController::cancel/$1'
    );


    // -------------------------------------------------------------
    // DÉPARTS / PLANNING
    // -------------------------------------------------------------

    $routes->get('plannings-depart', 'PlanningDepartController::index');
    $routes->get('plannings-depart/new', 'PlanningDepartController::new');
    $routes->post('plannings-depart', 'PlanningDepartController::create');

    $routes->get('plannings-depart/(:num)', 'PlanningDepartController::show/$1');
    $routes->get('plannings-depart/(:num)/edit', 'PlanningDepartController::edit/$1');
    $routes->post('plannings-depart/(:num)', 'PlanningDepartController::update/$1');

    $routes->post('plannings-depart/(:num)/delete', 'PlanningDepartController::delete/$1');
    $routes->get(
        'plannings-depart/available',
        'PlanningDepartController::available'
    );

    $routes->get(
        'reservations/planning/(:num)',
        'ReservationController::planning/$1'
    );

    // =============================================================
    // CLIENTS & PARTENAIRES
    // =============================================================


    // -------------------------------------------------------------
    // CLIENTS
    // -------------------------------------------------------------

    $routes->get('clients', 'ClientController::index');
    $routes->get('clients/new', 'ClientController::create');
    $routes->post('clients', 'ClientController::store');

    $routes->get(
        'clients/(:num)',
        'ClientController::show/$1'
    );

    $routes->get(
        'clients/(:num)/edit',
        'ClientController::edit/$1'
    );

    $routes->post(
        'clients/(:num)',
        'ClientController::update/$1'
    );

    $routes->get(
        'clients/(:num)/delete',
        'ClientController::delete/$1'
    );


    // -------------------------------------------------------------
    // FOURNISSEURS
    // -------------------------------------------------------------

    $routes->get(
        'fournisseurs',
        'FournisseurController::index'
    );

    $routes->get(
        'fournisseurs/new',
        'FournisseurController::create'
    );

    $routes->post(
        'fournisseurs',
        'FournisseurController::store'
    );

    $routes->get(
        'fournisseurs/(:num)',
        'FournisseurController::show/$1'
    );

    $routes->get(
        'fournisseurs/(:num)/edit',
        'FournisseurController::edit/$1'
    );

    $routes->post(
        'fournisseurs/(:num)',
        'FournisseurController::update/$1'
    );

    $routes->get(
        'fournisseurs/(:num)/delete',
        'FournisseurController::delete/$1'
    );


    // =============================================================
    // CATALOGUE TOURISTIQUE
    // =============================================================

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

        // Liste
        $routes->get(
            $uri,
            $controller . '::index'
        );

        // Formulaire création
        $routes->get(
            $uri . '/new',
            $controller . '::create'
        );

        // Enregistrement
        $routes->post(
            $uri,
            $controller . '::store'
        );

        // Détails
        $routes->get(
            $uri . '/(:num)',
            $controller . '::show/$1'
        );

        // Formulaire modification
        $routes->get(
            $uri . '/(:num)/edit',
            $controller . '::edit/$1'
        );

        // Mise à jour
        $routes->post(
            $uri . '/(:num)',
            $controller . '::update/$1'
        );

        // Suppression
        $routes->get(
            $uri . '/(:num)/delete',
            $controller . '::delete/$1'
        );

    }


    // -------------------------------------------------------------
    // FACTURES
    // -------------------------------------------------------------
    // Factures
$routes->get('factures', 'FactureController::index');
$routes->get('factures/create', 'FactureController::create');
$routes->post('factures', 'FactureController::store');
$routes->get('factures/(:num)', 'FactureController::show/$1');
$routes->get('factures/(:num)/edit', 'FactureController::edit/$1');
$routes->post('factures/(:num)', 'FactureController::update/$1');
$routes->post('factures/(:num)/delete', 'FactureController::delete/$1');
$routes->post('factures/(:num)/lignes', 'FactureController::addLigne/$1');
$routes->post('factures/(:num)/lignes/(:num)/delete', 'FactureController::deleteLigne/$1/$2');
$routes->get('factures/(:num)/print', 'FactureController::print/$1');
$routes->get('reservations/(:num)/facture', 'FactureController::fromReservation/$1');

// Paiements
$routes->get('paiements', 'PaiementController::index');
$routes->post('paiements', 'PaiementController::store');
$routes->post('paiements/(:num)/annuler', 'PaiementController::annuler/$1');

// Rapports


    // -------------------------------------------------------------
    // RAPPORTS & STATISTIQUES
    // -------------------------------------------------------------
    $routes->get('rapports', 'RapportController::index');


    // Statistiques générales
    $routes->get(
        'rapports/dashboard',
        'RapportController::dashboard'
    );

    // Cotations
    $routes->get(
        'rapports/cotations',
        'RapportController::cotations'
    );

    // Réservations
    $routes->get(
        'rapports/reservations',
        'RapportController::reservations'
    );

    // Clients
    $routes->get(
        'rapports/clients',
        'RapportController::clients'
    );

    // Chiffre d'affaires
    $routes->get(
        'rapports/chiffre-affaires',
        'RapportController::chiffreAffaires'
    );

    // Marges et bénéfices
    $routes->get(
        'rapports/marges',
        'RapportController::marges'
    );


    // -------------------------------------------------------------
    // UTILISATEURS
    // -------------------------------------------------------------

    $routes->group('utilisateurs', ['filter' => 'auth'], static function ($routes) {
        $routes->get('/',             'UtilisateurController::index');
        $routes->get('new',           'UtilisateurController::create');
        $routes->post('/',            'UtilisateurController::store');
        $routes->get('(:num)/edit',   'UtilisateurController::edit/$1');
        $routes->post('(:num)',       'UtilisateurController::update/$1');
        $routes->get('(:num)/delete', 'UtilisateurController::delete/$1');
    });

    // =====================================================
    // DEVISES
    // =====================================================
    $routes->group('devises', ['filter' => 'auth'], function($routes) {
        $routes->get('/',                    'DeviseController::index');
        $routes->get('new',                  'DeviseController::new');
        $routes->post('/',                   'DeviseController::create');
        $routes->get('(:num)/edit',          'DeviseController::edit/$1');
        $routes->post('(:num)',              'DeviseController::update/$1');
        $routes->post('(:num)/delete',       'DeviseController::delete/$1');
        $routes->post('(:num)/set-default',  'DeviseController::setDefault/$1');
    });

    // -------------------------------------------------------------
    // TAXES
    // -------------------------------------------------------------

    $routes->get(
        'parametres/taxes',
        'ParametresController::taxes'
    );

    $routes->post(
        'parametres/taxes',
        'ParametresController::storeTaxe'
    );


    // -------------------------------------------------------------
    // PARAMÈTRES DES COTATIONS
    // -------------------------------------------------------------

    $routes->get(
        'parametres/cotations',
        'ParametresController::cotations'
    );

    $routes->post(
        'parametres/cotations',
        'ParametresController::updateCotations'
    );


    // -------------------------------------------------------------
    // CONDITIONS COMMERCIALES
    // -------------------------------------------------------------

    $routes->get(
        'parametres/conditions',
        'ParametresController::conditions'
    );

    $routes->post(
        'parametres/conditions',
        'ParametresController::updateConditions'
    );

    $routes->group('parametres', ['filter' => 'auth'], static function ($routes) {
        $routes->get('/',     'ParametresController::index');
        $routes->post('/',    'ParametresController::update');
    });

    $routes->group('restaurants', ['filter' => 'auth'], static function ($routes) {
        $routes->get('/',                'RestaurantController::index');
        $routes->get('new',              'RestaurantController::create');
        $routes->post('/',               'RestaurantController::store');
        $routes->get('(:num)/edit',      'RestaurantController::edit/$1');
        $routes->post('(:num)',          'RestaurantController::update/$1');
        $routes->get('(:num)/delete',    'RestaurantController::delete/$1');
    });


    $routes->get('assurances', 'AssuranceController::index');

    $routes->get('assurances/create', 'AssuranceController::create');
    $routes->post('assurances', 'AssuranceController::store');
    $routes->get('assurances/(:num)', 'AssuranceController::show/$1');
    $routes->get('assurances/(:num)/edit', 'AssuranceController::edit/$1');
    $routes->post('assurances/(:num)', 'AssuranceController::update/$1');
    $routes->post('assurances/(:num)/delete', 'AssuranceController::delete/$1');

    $routes->post('clients/quick-store', 'ClientController::quickStore');
    $routes->post('destinations/quick-store', 'DestinationController::quickStore');

});