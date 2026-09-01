<?php

namespace App\Controllers;

use App\Models\CotationModel;
use App\Models\DemandeModel;
use App\Models\FactureModel;
use App\Models\PaiementModel;
use App\Models\ReservationModel;

class RapportController extends BaseController
{
    protected function tenantId(): int
    {
        return (int) session('tenant_id');
    }

    public function index()
    {
        $tenantId = $this->tenantId();
        $annee    = (int) ($this->request->getGet('annee') ?: date('Y'));
        $mois     = $this->request->getGet('mois'); // optionnel 01-12

        $db = \Config\Database::connect();

        // ---- KPI globaux année ----
        $factureModel   = new FactureModel();
        $paiementModel  = new PaiementModel();
        $demandeModel   = new DemandeModel();
        $cotationModel  = new CotationModel();
        $reservationModel = new ReservationModel();

        $caFacture = $factureModel
            ->selectSum('montant_ttc', 'total')
            ->where('tenant_id', $tenantId)
            ->where('YEAR(date_facture)', $annee)
            ->whereNotIn('statut', ['annulee', 'brouillon'])
            ->first();

        $caEncaisse = $paiementModel
            ->selectSum('montant', 'total')
            ->where('tenant_id', $tenantId)
            ->where('statut', 'valide')
            ->where('YEAR(date_paiement)', $annee)
            ->first();

        $impayes = $factureModel
            ->selectSum('montant_restant', 'total')
            ->where('tenant_id', $tenantId)
            ->whereIn('statut', ['emise', 'partiellement_payee', 'en_retard'])
            ->first();

        $nbDemandes = $demandeModel
            ->where('tenant_id', $tenantId)
            ->where('YEAR(created_at)', $annee)
            ->countAllResults();

        $nbCotations = $cotationModel
            ->where('tenant_id', $tenantId)
            ->where('YEAR(created_at)', $annee)
            ->countAllResults();

        $nbReservations = $reservationModel
            ->where('tenant_id', $tenantId)
            ->where('YEAR(created_at)', $annee)
            ->countAllResults();

        // ---- CA par mois ----
        $caParMois = $db->query("
            SELECT MONTH(date_paiement) AS mois, SUM(montant) AS total
            FROM paiements
            WHERE tenant_id = ?
              AND statut = 'valide'
              AND YEAR(date_paiement) = ?
            GROUP BY MONTH(date_paiement)
            ORDER BY mois
        ", [$tenantId, $annee])->getResultArray();

        $caMensuel = array_fill(1, 12, 0);
        foreach ($caParMois as $row) {
            $caMensuel[(int) $row['mois']] = (float) $row['total'];
        }

        // ---- Factures par statut ----
        $facturesParStatut = $db->query("
            SELECT statut, COUNT(*) AS nb, SUM(montant_ttc) AS total
            FROM factures
            WHERE tenant_id = ?
              AND YEAR(date_facture) = ?
            GROUP BY statut
        ", [$tenantId, $annee])->getResultArray();

        // ---- Top clients ----
        $topClients = $db->query("
            SELECT
                c.id,
                c.nom,
                c.prenom,
                c.entreprise,
                SUM(p.montant) AS total_paye,
                COUNT(DISTINCT p.facture_id) AS nb_factures
            FROM paiements p
            JOIN clients c ON c.id = p.client_id
            WHERE p.tenant_id = ?
              AND p.statut = 'valide'
              AND YEAR(p.date_paiement) = ?
            GROUP BY c.id, c.nom, c.prenom, c.entreprise
            ORDER BY total_paye DESC
            LIMIT 10
        ", [$tenantId, $annee])->getResultArray();

        // ---- Entonnoir commercial ----
        $entonnoir = [
            'demandes'     => $nbDemandes,
            'cotations'    => $nbCotations,
            'reservations' => $nbReservations,
            'factures'     => $factureModel
                ->where('tenant_id', $tenantId)
                ->where('YEAR(date_facture)', $annee)
                ->whereNotIn('statut', ['annulee', 'brouillon'])
                ->countAllResults(),
        ];

        return view('rapports/index', [
            'title'             => 'Rapports & statistiques',
            'annee'             => $annee,
            'mois'              => $mois,
            'caFacture'         => (float) ($caFacture['total'] ?? 0),
            'caEncaisse'        => (float) ($caEncaisse['total'] ?? 0),
            'impayes'           => (float) ($impayes['total'] ?? 0),
            'nbDemandes'        => $nbDemandes,
            'nbCotations'       => $nbCotations,
            'nbReservations'    => $nbReservations,
            'caMensuel'         => $caMensuel,
            'facturesParStatut' => $facturesParStatut,
            'topClients'        => $topClients,
            'entonnoir'         => $entonnoir,
        ]);
    }
}