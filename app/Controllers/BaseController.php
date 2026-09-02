<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\TenantAccountModel;

abstract class BaseController extends Controller
{

    protected TenantAccountModel $tenantAccountModel;

    public function initController(
        RequestInterface $request,
        ResponseInterface $response,
        LoggerInterface $logger
    ) {
        parent::initController($request, $response, $logger);

        $this->tenantAccountModel = new TenantAccountModel();
    }

    /**
     * ID du tenant connecté
     */
    protected function tenantId(): int
    {
        return (int) session()->get('tenant_id');
    }

    /**
     * Informations de l'agence connectée
     */
    protected function agence(): ?array
    {
        $tenantId = $this->tenantId();

        if ($tenantId <= 0) {
            log_message('error', 'Aucun tenant_id dans la session.');
            return null;
        }

        $agence = $this->tenantAccountModel
            ->where('id', $tenantId)
            ->first();

        if (!$agence) {
            log_message(
                'error',
                'Agence introuvable pour tenant_id = ' . $tenantId
            );
        }

        return $agence;
    }

    /**
     * Données communes aux documents
     */
    protected function documentData(): array
    {
        return [
            'agence'   => $this->agence(),
            'tenant_id' => $this->tenantId(),
        ];
    }
}