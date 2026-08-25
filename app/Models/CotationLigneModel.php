<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Pas de colonne tenant_id sur cette table : l'isolation se fait via
 * cotation_id, dont le tenant est déjà vérifié par CotationController
 * (qui charge la cotation via CotationModel — donc tenant-scopée —
 * avant toute opération sur ses lignes).
 */
class CotationLigneModel extends Model
{
    protected $table         = 'cotation_lignes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $allowedFields = [
        'cotation_id', 'type_service', 'service_id', 'libelle',
        'date_debut', 'date_fin', 'quantite', 'prix_unitaire', 'montant', 'ordre',
    ];
}
