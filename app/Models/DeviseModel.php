<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Table de référence des devises
 * (peut être globale ou par tenant selon ton choix)
 */
class DeviseModel extends Model
{
    protected $table         = 'devises';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'code',
        'nom',
        'symbole',
        'taux_change',
        'is_default',
        'tenant_id',
        'actif',
    ];

    public function findByCode(string $code, ?int $tenantId = null): ?array
    {
        $builder = $this->where('code', strtoupper($code));

        if ($tenantId !== null) {
            $builder->where('tenant_id', $tenantId);
        }

        return $builder->first();
    }

    public function getDefault(?int $tenantId = null): ?array
    {
        $builder = $this->where('is_default', 1);

        if ($tenantId !== null) {
            $builder->where('tenant_id', $tenantId);
        }

        return $builder->first();
    }

    public function getTaux(string $code, ?int $tenantId = null): float
    {
        $devise = $this->findByCode($code, $tenantId);

        if (!$devise) {
            return 1.0;
        }

        if (!empty($devise['is_default'])) {
            return 1.0;
        }

        return (float) ($devise['taux_change'] ?? 1.0);
    }

    public function convertir(
        float $montant,
        string $de,
        string $vers,
        ?int $tenantId = null
    ): float {
        $de   = strtoupper(trim($de));
        $vers = strtoupper(trim($vers));

        if ($montant == 0.0 || $de === $vers) {
            return round($montant, 2);
        }

        $tauxDe   = $this->getTaux($de, $tenantId);
        $tauxVers = $this->getTaux($vers, $tenantId);

        $enBase = $montant * $tauxDe;

        $resultat = ($tauxVers > 0) ? ($enBase / $tauxVers) : $enBase;

        return round($resultat, 2);
    }

    public function getActives(?int $tenantId = null): array
    {
        $builder = $this->where('actif', 1);

        if ($tenantId !== null) {
            $builder->where('tenant_id', $tenantId);
        }

        return $builder
            ->orderBy('is_default', 'DESC')
            ->orderBy('code', 'ASC')
            ->findAll();
    }

    public function getSymbole(string $code, ?int $tenantId = null): string
    {
        $devise = $this->findByCode($code, $tenantId);

        return $devise['symbole'] ?? $code;
    }
}