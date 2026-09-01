<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\CotationModel;
use App\Models\ReservationModel;
use App\Models\FactureModel;
use App\Models\PaiementModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $cotationModel    = new CotationModel();
        $clientModel      = new ClientModel();
        $reservationModel = new ReservationModel();
        $factureModel     = new FactureModel();
        $paiementModel    = new PaiementModel();

        /*
        |--------------------------------------------------------------------------
        | Périodes
        |--------------------------------------------------------------------------
        */

        $debutMois = date('Y-m-01 00:00:00');
        $finMois   = date('Y-m-t 23:59:59');

        $debutMoisPrecedent = date('Y-m-01 00:00:00', strtotime('first day of last month'));
        $finMoisPrecedent   = date('Y-m-t 23:59:59', strtotime('last day of last month'));


        /*
        |--------------------------------------------------------------------------
        | Cotations
        |--------------------------------------------------------------------------
        */

        $nbCotations = $cotationModel->countAllResults();

        $nbCotationsMois = $cotationModel
            ->where('created_at >=', $debutMois)
            ->where('created_at <=', $finMois)
            ->countAllResults();

        $nbCotationsMoisPrecedent = $cotationModel
            ->where('created_at >=', $debutMoisPrecedent)
            ->where('created_at <=', $finMoisPrecedent)
            ->countAllResults();

        $nbCotationsAcceptees = $cotationModel
            ->where('statut', 'acceptee')
            ->countAllResults();


        /*
        |--------------------------------------------------------------------------
        | Cotations par statut
        |--------------------------------------------------------------------------
        */

        $statutLabels = [
            'brouillon',
            'envoyee',
            'acceptee',
            'refusee',
            'expiree',
        ];

        $cotationsParStatut = [];

        foreach ($statutLabels as $statut) {

            $cotationsParStatut[$statut] = $cotationModel
                ->where('statut', $statut)
                ->countAllResults();
        }


        /*
        |--------------------------------------------------------------------------
        | Réservations
        |--------------------------------------------------------------------------
        */

        $nbReservations = $reservationModel->countAllResults();

        $nbReservationsMois = $reservationModel
            ->where('created_at >=', $debutMois)
            ->where('created_at <=', $finMois)
            ->countAllResults();

        $statutsReservations = [
            'en_attente',
            'confirmee',
            'annulee',
            'terminee',
        ];

        $reservationsParStatut = [];

        foreach ($statutsReservations as $statut) {

            $reservationsParStatut[$statut] = $reservationModel
                ->where('statut', $statut)
                ->countAllResults();
        }

        $nbReservationsConfirmees = $reservationModel
            ->where('statut', 'confirmee')
            ->countAllResults();


        /*
        |--------------------------------------------------------------------------
        | Clients
        |--------------------------------------------------------------------------
        */

        $nbClients = $clientModel->countAllResults();

        $nbNouveauxClientsMois = $clientModel
            ->where('created_at >=', $debutMois)
            ->where('created_at <=', $finMois)
            ->countAllResults();


        /*
        |--------------------------------------------------------------------------
        | Chiffre d'affaires
        |--------------------------------------------------------------------------
        */

        $chiffreAffaires = $reservationModel
            ->selectSum('montant_total')
            ->where('statut !=', 'annulee')
            ->first();

        $chiffreAffaires = (float) ($chiffreAffaires['montant_total'] ?? 0);


        /*
        |--------------------------------------------------------------------------
        | Chiffre d'affaires du mois
        |--------------------------------------------------------------------------
        */

        $caMois = $reservationModel
            ->selectSum('montant_total')
            ->where('statut !=', 'annulee')
            ->where('created_at >=', $debutMois)
            ->where('created_at <=', $finMois)
            ->first();

        $caMois = (float) ($caMois['montant_total'] ?? 0);


        /*
        |--------------------------------------------------------------------------
        | Factures
        |--------------------------------------------------------------------------
        */

        $facturesImpayees = $factureModel
            ->whereIn('statut', [
                'envoyee',
                'partiellement_payee',
            ])
            ->countAllResults();


        /*
        |--------------------------------------------------------------------------
        | Montant impayé
        |--------------------------------------------------------------------------
        */

        $montantImpayes = $factureModel
            ->selectSum('montant_paye')
            ->whereIn('statut', [
                'envoyee',
                'partiellement_payee',
            ])
            ->first();

        $montantImpayes = (float) ($montantImpayes['montant_paye'] ?? 0);


        /*
        |--------------------------------------------------------------------------
        | Paiements encaissés
        |--------------------------------------------------------------------------
        */

        $montantEncaisse = $paiementModel
            ->selectSum('montant')
            ->first();

        $montantEncaisse = (float) ($montantEncaisse['montant'] ?? 0);


        $montantEncaisseMois = $paiementModel
            ->selectSum('montant')
            ->where('date_paiement >=', date('Y-m-01'))
            ->where('date_paiement <=', date('Y-m-t'))
            ->first();

        $montantEncaisseMois = (float) ($montantEncaisseMois['montant'] ?? 0);


        /*
        |--------------------------------------------------------------------------
        | Montant des cotations acceptées
        |--------------------------------------------------------------------------
        */

        $montantAccepte = $cotationModel
            ->selectSum('prix_total')
            ->where('statut', 'acceptee')
            ->first();

        $montantAccepte = (float) ($montantAccepte['prix_total'] ?? 0);


        /*
        |--------------------------------------------------------------------------
        | Taux de conversion Cotation → Réservation
        |--------------------------------------------------------------------------
        */

        $tauxConversion = $nbCotationsAcceptees > 0
            ? round(($nbReservations / $nbCotationsAcceptees) * 100, 1)
            : 0;


        /*
        |--------------------------------------------------------------------------
        | Evolution des 6 derniers mois
        |--------------------------------------------------------------------------
        */

        $labelsGraphique = [];
        $donneesCotations = [];
        $donneesReservations = [];

        for ($i = 5; $i >= 0; $i--) {

            $timestamp = strtotime("-{$i} months");

            $debut = date('Y-m-01 00:00:00', $timestamp);
            $fin   = date('Y-m-t 23:59:59', $timestamp);

            $labelsGraphique[] = date('M Y', $timestamp);

            $donneesCotations[] = $cotationModel
                ->where('created_at >=', $debut)
                ->where('created_at <=', $fin)
                ->countAllResults();

            $donneesReservations[] = $reservationModel
                ->where('created_at >=', $debut)
                ->where('created_at <=', $fin)
                ->countAllResults();
        }


        /*
        |--------------------------------------------------------------------------
        | Dernières cotations
        |--------------------------------------------------------------------------
        */

        $dernieresCotations = $cotationModel
            ->select('
                cotations.*,
                clients.nom AS client_nom,
                destinations.nom AS destination_nom
            ')
            ->join(
                'clients',
                'clients.id = cotations.client_id',
                'left'
            )
            ->join(
                'destinations',
                'destinations.id = cotations.destination_id',
                'left'
            )
            ->orderBy('cotations.created_at', 'DESC')
            ->findAll(6);


        /*
        |--------------------------------------------------------------------------
        | Dernières réservations
        |--------------------------------------------------------------------------
        */

        $dernieresReservations = $reservationModel
            ->select('
                reservations.*,
                clients.nom AS client_nom
            ')
            ->join(
                'clients',
                'clients.id = reservations.client_id',
                'left'
            )
            ->orderBy('reservations.created_at', 'DESC')
            ->findAll(5);


        /*
        |--------------------------------------------------------------------------
        | Evolution cotations par rapport au mois précédent
        |--------------------------------------------------------------------------
        */

        if ($nbCotationsMoisPrecedent > 0) {

            $evolutionCotations = round(
                (
                    ($nbCotationsMois - $nbCotationsMoisPrecedent)
                    / $nbCotationsMoisPrecedent
                ) * 100,
                1
            );

        } else {

            $evolutionCotations = $nbCotationsMois > 0 ? 100 : 0;
        }


        /*
        |--------------------------------------------------------------------------
        | Données envoyées à la vue
        |--------------------------------------------------------------------------
        */

        $data = [

            'title' => 'Tableau de bord',

            // Cotations
            'nbCotations'             => $nbCotations,
            'nbCotationsMois'         => $nbCotationsMois,
            'nbCotationsAcceptees'    => $nbCotationsAcceptees,
            'cotationsParStatut'      => $cotationsParStatut,
            'montantAccepte'          => $montantAccepte,

            // Réservations
            'nbReservations'          => $nbReservations,
            'nbReservationsMois'      => $nbReservationsMois,
            'nbReservationsConfirmees'=> $nbReservationsConfirmees,
            'reservationsParStatut'   => $reservationsParStatut,

            // Clients
            'nbClients'               => $nbClients,
            'nbNouveauxClientsMois'   => $nbNouveauxClientsMois,

            // Finances
            'chiffreAffaires'         => $chiffreAffaires,
            'caMois'                  => $caMois,
            'facturesImpayees'        => $facturesImpayees,
            'montantImpayes'          => $montantImpayes,
            'montantEncaisse'         => $montantEncaisse,
            'montantEncaisseMois'     => $montantEncaisseMois,

            // Performances
            'tauxConversion'          => $tauxConversion,
            'evolutionCotations'      => $evolutionCotations,

            // Graphique
            'labelsGraphique'         => $labelsGraphique,
            'donneesCotations'        => $donneesCotations,
            'donneesReservations'     => $donneesReservations,

            // Listes
            'dernieresCotations'      => is_array($dernieresCotations)
                ? $dernieresCotations
                : [],

            'dernieresReservations'   => is_array($dernieresReservations)
                ? $dernieresReservations
                : [],
        ];

        return view('dashboard/index', $data);
    }
}