<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class PermissionFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $permission = $arguments[0] ?? null;

        if (! $permission) {
            return;
        }

        helper('permission');

        if (can($permission)) {
            return;
        }

        $db = db_connect();

        // Récupérer les informations de la permission refusée
        $permissionData = $db
            ->table('permissions')
            ->select('code, libelle, module, action')
            ->where('code', $permission)
            ->get()
            ->getRowArray();

        /*
        |--------------------------------------------------------------------------
        | Pages auxquelles l'utilisateur a accès
        |--------------------------------------------------------------------------
        */

        $accessiblePages = [];

        $tenantId = (int) session()->get('tenant_id');
        $roleId   = (int) session()->get('user_role');

        if ($tenantId > 0 && $roleId > 0) {

            $accessiblePages = $db
                ->table('role_permissions rp')
                ->select(
                    'p.code,
                     p.libelle,
                     p.module,
                     p.action'
                )
                ->join(
                    'permissions p',
                    'p.id = rp.permission_id'
                )
                ->join(
                    'roles r',
                    'r.id = rp.role_id'
                )
                ->where('rp.role_id', $roleId)
                ->where('r.tenant_id', $tenantId)
                ->where('p.action', 'view')
                ->orderBy('p.module', 'ASC')
                ->get()
                ->getResultArray();
        }

        /*
        |--------------------------------------------------------------------------
        | Construire les liens accessibles
        |--------------------------------------------------------------------------
        */

        $accessibleLinks = $this->buildAccessibleLinks(
            $accessiblePages
        );

        return service('response')
            ->setStatusCode(403)
            ->setBody(
                view('errors/403', [
                    'message'          => 'Vous ne disposez pas des droits nécessaires pour accéder à cette fonctionnalité.',
                    'requiredPermission' => $permissionData,
                    'accessibleLinks'  => $accessibleLinks,
                ])
            );
    }


    /**
     * Construire les pages accessibles
     */
    protected function buildAccessibleLinks(array $permissions): array
    {
        $routes = [
            'dashboard.view'     => [
                'label' => 'Tableau de bord',
                'url'   => site_url('dashboard'),
                'icon'  => 'bi-speedometer2',
            ],

            'demandes.view'      => [
                'label' => 'Demandes',
                'url'   => site_url('demandes'),
                'icon'  => 'bi-inbox',
            ],

            'cotations.view'     => [
                'label' => 'Cotations',
                'url'   => site_url('cotations'),
                'icon'  => 'bi-file-earmark-text',
            ],

            'reservations.view'  => [
                'label' => 'Réservations',
                'url'   => site_url('reservations'),
                'icon'  => 'bi-calendar-check',
            ],

            'plannings.view'     => [
                'label' => 'Plannings',
                'url'   => site_url('plannings'),
                'icon'  => 'bi-calendar3',
            ],

            'clients.view'       => [
                'label' => 'Clients',
                'url'   => site_url('clients'),
                'icon'  => 'bi-people',
            ],

            'fournisseurs.view'  => [
                'label' => 'Fournisseurs',
                'url'   => site_url('fournisseurs'),
                'icon'  => 'bi-building',
            ],

            'destinations.view'  => [
                'label' => 'Destinations',
                'url'   => site_url('destinations'),
                'icon'  => 'bi-geo-alt',
            ],

            'hotels.view'        => [
                'label' => 'Hôtels',
                'url'   => site_url('hotels'),
                'icon'  => 'bi-building',
            ],

            'vols.view'          => [
                'label' => 'Vols',
                'url'   => site_url('vols'),
                'icon'  => 'bi-airplane',
            ],

            'excursions.view'    => [
                'label' => 'Excursions',
                'url'   => site_url('excursions'),
                'icon'  => 'bi-map',
            ],

            'transferts.view'    => [
                'label' => 'Transferts',
                'url'   => site_url('transferts'),
                'icon'  => 'bi-car-front',
            ],

            'restaurants.view'   => [
                'label' => 'Restaurants',
                'url'   => site_url('restaurants'),
                'icon'  => 'bi-cup-hot',
            ],

            'forfaits.view'      => [
                'label' => 'Forfaits',
                'url'   => site_url('forfaits'),
                'icon'  => 'bi-box-seam',
            ],

            'circuits.view'      => [
                'label' => 'Circuits',
                'url'   => site_url('circuits'),
                'icon'  => 'bi-signpost-2',
            ],

            'assurances.view'    => [
                'label' => 'Assurances',
                'url'   => site_url('assurances'),
                'icon'  => 'bi-shield-check',
            ],

            'factures.view'      => [
                'label' => 'Factures',
                'url'   => site_url('factures'),
                'icon'  => 'bi-receipt',
            ],

            'paiements.view'     => [
                'label' => 'Paiements',
                'url'   => site_url('paiements'),
                'icon'  => 'bi-credit-card',
            ],

            'rapports.view'      => [
                'label' => 'Rapports',
                'url'   => site_url('rapports'),
                'icon'  => 'bi-bar-chart',
            ],

            'utilisateurs.view'  => [
                'label' => 'Utilisateurs',
                'url'   => site_url('utilisateurs'),
                'icon'  => 'bi-person-gear',
            ],

            'devises.view'       => [
                'label' => 'Devises',
                'url'   => site_url('devises'),
                'icon'  => 'bi-currency-exchange',
            ],

            'parametres.view'    => [
                'label' => 'Paramètres',
                'url'   => site_url('parametres'),
                'icon'  => 'bi-gear',
            ],
        ];

        $links = [];

        foreach ($permissions as $permission) {

            $code = $permission['code'];

            if (isset($routes[$code])) {
                $links[] = $routes[$code];
            }
        }

        return $links;
    }


    public function after(
        RequestInterface $request,
        ResponseInterface $response,
        $arguments = null
    ) {
    }
}