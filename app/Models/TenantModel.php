<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Modèle de base pour toutes les tables rattachées à un tenant
 * (hotels, vols, excursions, transferts, croisieres, forfaits,
 * circuits, destinations, clients, cotations...).
 *
 * Toute requête SELECT / UPDATE / DELETE passant par ->builder()
 * est automatiquement filtrée sur tenant_id = session('tenant_id'),
 * ce qui empêche par construction qu'une agence voie les données
 * d'une autre agence.
 *
 * IMPORTANT : lors d'un insert(), pensez à toujours renseigner
 * explicitement 'tenant_id' => session('tenant_id') dans le tableau
 * de données (voir les contrôleurs fournis en exemple).
 */
class TenantModel extends Model
{
    public function builder($table = null)
    {
        $builder = parent::builder($table);

        $tenantId = session('tenant_id');
        if ($tenantId !== null) {
            $builder->where(($table ?? $this->table) . '.tenant_id', $tenantId);
        }

        return $builder;
    }
}
