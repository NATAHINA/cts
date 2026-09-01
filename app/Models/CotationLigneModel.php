<?php

namespace App\Models;

class CotationLigneModel extends TenantModel
{
    protected $table = 'cotation_lignes';

    protected $primaryKey = 'id';

    protected $useAutoIncrement = true;

    protected $returnType = 'array';

    protected $useTimestamps = true;

    protected $allowedFields = [
        'tenant_id',

        'cotation_id',

        'type_prestation',
        'prestation_id',
        'fournisseur_id',

        'designation',
        'description',

        'quantite',

        'cout_unitaire',
        'cout_total',

        'marge_pourcentage',
        'marge_montant',

        'prix_unitaire',
        'prix_total',

        'devise',

        'ordre',
    ];
}