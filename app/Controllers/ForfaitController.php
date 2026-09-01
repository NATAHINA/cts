<?php

namespace App\Controllers;

use App\Models\ForfaitModel;
use App\Models\DestinationModel;
use App\Models\FournisseurModel;
use App\Models\DeviseModel;

class ForfaitController extends BaseController
{
    protected ForfaitModel $forfaitModel;
    protected DestinationModel $destinationModel;
    protected FournisseurModel $fournisseurModel;
    protected DeviseModel $deviseModel;

    public function __construct()
    {
        $this->forfaitModel = new ForfaitModel();
        $this->destinationModel = new DestinationModel();
        $this->fournisseurModel = new FournisseurModel();
        $this->deviseModel = new DeviseModel();
    }

    /**
     * Liste des forfaits
     */
    public function index()
    {
        $items = $this->forfaitModel
            ->select('
                forfaits.*,
                destinations.nom AS destination_nom,
                fournisseurs.nom AS fournisseur_nom,
                devises.code AS devise_code
            ')
            ->join(
                'destinations',
                'destinations.id = forfaits.destination_id',
                'left'
            )
            ->join(
                'fournisseurs',
                'fournisseurs.id = forfaits.fournisseur_id',
                'left'
            )
            ->join(
                'devises',
                'devises.id = forfaits.devise_id',
                'left'
            )
            ->orderBy('forfaits.created_at', 'DESC')
            ->findAll();

        return view('forfaits/index', [
            'title' => 'Forfaits',
            'items' => $items,
        ]);
    }


    private function generateCode(): string
    {
        $tenantId = (int) session('tenant_id');

        // Nombre de forfaits de cette agence
        $total = $this->forfaitModel
            ->where('tenant_id', $tenantId)
            ->countAllResults();

        // Prochain numéro
        $numero = $total + 1;

        return 'FOR-' . str_pad(
            (string) $numero,
            5,
            '0',
            STR_PAD_LEFT
        );
    }

    /**
     * Formulaire d'ajout
     */
    public function create()
    {
        return view('forfaits/form', [
            'title'        => 'Ajouter un forfait',
            'item'         => null,
            'destinations' => $this->destinationModel
                ->orderBy('nom', 'ASC')
                ->findAll(),
            'fournisseurs' => $this->fournisseurModel
                ->orderBy('nom', 'ASC')
                ->findAll(),
            'devises'      => $this->deviseModel
                ->orderBy('code', 'ASC')
                ->findAll(),
        ]);
    }

    /**
     * Enregistrement
     */
    public function store()
    {
        $data = $this->getFormData();

        $data['code'] = $this->generateCode();

        if (! $this->forfaitModel->insert($data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'errors',
                    $this->forfaitModel->errors()
                );
        }

        return redirect()
            ->to(site_url('forfaits'))
            ->with(
                'success',
                'Forfait enregistré avec succès.'
            );
    }

    /**
     * Formulaire de modification
     */
    public function edit($id)
    {
        $item = $this->forfaitModel->find($id);

        if (! $item) {
            return redirect()
                ->to(site_url('forfaits'))
                ->with(
                    'error',
                    'Forfait introuvable.'
                );
        }

        return view('forfaits/form', [
            'title'        => 'Modifier le forfait',
            'item'         => $item,
            'destinations' => $this->destinationModel
                ->orderBy('nom', 'ASC')
                ->findAll(),
            'fournisseurs' => $this->fournisseurModel
                ->orderBy('nom', 'ASC')
                ->findAll(),
            'devises'      => $this->deviseModel
                ->orderBy('code', 'ASC')
                ->findAll(),
        ]);
    }

    /**
     * Mise à jour
     */
    public function update($id)
    {
        $item = $this->forfaitModel->find($id);

        if (! $item) {
            return redirect()
                ->to(site_url('forfaits'))
                ->with(
                    'error',
                    'Forfait introuvable.'
                );
        }

        $data = $this->getFormData();

        // tenant_id ne doit pas être modifié
        unset($data['tenant_id']);

        if (! $this->forfaitModel->update($id, $data)) {
            return redirect()
                ->back()
                ->withInput()
                ->with(
                    'errors',
                    $this->forfaitModel->errors()
                );
        }

        return redirect()
            ->to(site_url('forfaits'))
            ->with(
                'success',
                'Forfait modifié avec succès.'
            );
    }

    /**
     * Suppression
     */
    public function delete($id)
    {
        $item = $this->forfaitModel->find($id);

        if (! $item) {
            return redirect()
                ->to(site_url('forfaits'))
                ->with(
                    'error',
                    'Forfait introuvable.'
                );
        }

        if (! $this->forfaitModel->delete($id)) {
            return redirect()
                ->to(site_url('forfaits'))
                ->with(
                    'error',
                    'Impossible de supprimer ce forfait.'
                );
        }

        return redirect()
            ->to(site_url('forfaits'))
            ->with(
                'success',
                'Forfait supprimé avec succès.'
            );
    }

    /**
     * Préparation des données du formulaire
     */
    private function getFormData(): array
    {
        return [
            'tenant_id' => (int) session('tenant_id'),

            // Informations générales
            'destination_id' => $this->nullableInt(
                $this->request->getPost('destination_id')
            ),

            'fournisseur_id' => $this->nullableInt(
                $this->request->getPost('fournisseur_id')
            ),

            'code' => trim(
                (string) $this->request->getPost('code')
            ) ?: null,

            'nom' => trim(
                (string) $this->request->getPost('nom')
            ),

            'description' => trim(
                (string) $this->request->getPost('description')
            ) ?: null,

            // Durée
            'duree_jours' => $this->nullableInt(
                $this->request->getPost('duree_jours')
            ),

            'duree_nuits' => $this->nullableInt(
                $this->request->getPost('duree_nuits')
            ),

            // Prix principal
            'prix' => $this->nullableDecimal(
                $this->request->getPost('prix')
            ),

            // Tarification détaillée
            'prix_adulte' => $this->nullableDecimal(
                $this->request->getPost('prix_adulte')
            ),

            'prix_enfant' => $this->nullableDecimal(
                $this->request->getPost('prix_enfant')
            ),

            'prix_groupe' => $this->nullableDecimal(
                $this->request->getPost('prix_groupe')
            ),

            'devise_id' => $this->nullableInt(
                $this->request->getPost('devise_id')
            ),

            // Commercial
            'commission_pourcentage' => $this->nullableDecimal(
                $this->request->getPost('commission_pourcentage')
            ),

            'disponibilite' => $this->request->getPost(
                'disponibilite'
            ) ?: 'disponible',

            'statut' => $this->request->getPost(
                'statut'
            ) ?: 'actif',
        ];
    }

    /**
     * Convertit une valeur en entier ou null
     */
    private function nullableInt($value): ?int
    {
        return ($value !== null && $value !== '')
            ? (int) $value
            : null;
    }

    /**
     * Convertit une valeur en nombre décimal ou null
     */
    private function nullableDecimal($value): ?float
    {
        return ($value !== null && $value !== '')
            ? (float) $value
            : null;
    }
}