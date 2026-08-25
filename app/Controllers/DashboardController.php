<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\CotationModel;

class DashboardController extends BaseController
{
    public function index()
    {
        $cotationModel = new CotationModel();
        $clientModel   = new ClientModel();

        $debutMois = date('Y-m-01 00:00:00');
        $finMois   = date('Y-m-t 23:59:59');

        $statutLabels = ['brouillon', 'envoyee', 'acceptee', 'refusee', 'expiree'];
        $cotationsParStatut = [];
        foreach ($statutLabels as $statut) {
            $cotationsParStatut[$statut] = $cotationModel->where('statut', $statut)->countAllResults();
        }

        $dernieresCotations = $cotationModel
            ->select('cotations.*, clients.nom as client_nom')
            ->join('clients', 'clients.id = cotations.client_id', 'left')
            ->orderBy('cotations.created_at', 'DESC')
            ->findAll(6);

        $data = [
            'title'              => 'Tableau de bord',
            'nbCotations'        => $cotationModel->countAllResults(),
            'nbCotationsMois'    => $cotationModel->where('created_at >=', $debutMois)->where('created_at <=', $finMois)->countAllResults(),
            'nbClients'          => $clientModel->countAllResults(),
            'montantAccepte'     => $cotationModel->where('statut', 'acceptee')->selectSum('montant_total')->first()['montant_total'] ?? 0,
            'cotationsParStatut' => $cotationsParStatut,
            'dernieresCotations' => is_array($dernieresCotations) ? $dernieresCotations : [],
        ];

        return view('dashboard/index', $data);
    }
}
