<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// ---------------------------------------------------------------------
// Authentification (public)
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
// Espace connecté (filtre "auth")
// ---------------------------------------------------------------------
$routes->group('', ['filter' => 'auth'], static function (RouteCollection $routes) {

    $routes->get('/', 'DashboardController::index');
    $routes->get('dashboard', 'DashboardController::index');

    // Clients
    $routes->get('clients', 'ClientController::index');
    $routes->get('clients/new', 'ClientController::create');
    $routes->post('clients', 'ClientController::store');
    $routes->get('clients/(:num)/edit', 'ClientController::edit/$1');
    $routes->post('clients/(:num)', 'ClientController::update/$1');
    $routes->get('clients/(:num)/delete', 'ClientController::delete/$1');

    // Cotations
    $routes->get('cotations', 'CotationController::index');
    $routes->get('cotations/new', 'CotationController::create');
    $routes->post('cotations', 'CotationController::store');
    $routes->get('cotations/(:num)', 'CotationController::show/$1');
    $routes->get('cotations/(:num)/print', 'CotationController::print/$1');
    $routes->get('cotations/(:num)/edit', 'CotationController::edit/$1');
    $routes->post('cotations/(:num)', 'CotationController::update/$1');
    $routes->get('cotations/(:num)/delete', 'CotationController::delete/$1');
    $routes->post('cotations/(:num)/lignes', 'CotationController::addLigne/$1');
    $routes->get('cotations/(:num)/lignes/(:num)/delete', 'CotationController::deleteLigne/$1/$2');
    $routes->post('cotations/(:num)/statut', 'CotationController::changeStatut/$1');

    // Modules catalogue : Destinations, Hôtels, Vols, Excursions,
    // Transferts, Croisières, Forfaits, Circuits
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
        $routes->get($uri, $controller . '::index');
        $routes->get($uri . '/new', $controller . '::create');
        $routes->post($uri, $controller . '::store');
        $routes->get($uri . '/(:num)/edit', $controller . '::edit/$1');
        $routes->post($uri . '/(:num)', $controller . '::update/$1');
        $routes->get($uri . '/(:num)/delete', $controller . '::delete/$1');
    }

    // Paramètres (compte, utilisateurs de l'agence)
    $routes->get('parametres', 'ParametreController::index');
    $routes->get('parametres/utilisateurs', 'ParametreController::utilisateurs');
    $routes->post('parametres/utilisateurs', 'ParametreController::storeUtilisateur');
    
});
