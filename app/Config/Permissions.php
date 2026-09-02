<?php

namespace App\Config;

/**
 * Catalogue centralisé des permissions de l'application.
 *
 * Format :
 * module.action
 *
 * Exemple :
 * demandes.view
 * demandes.create
 * demandes.edit
 * demandes.delete
 */
class Permissions
{
    /**
     * Retourne toutes les permissions disponibles.
     */
    public static function all(): array
    {
        return [

            /*
            |--------------------------------------------------------------------------
            | TABLEAU DE BORD
            |--------------------------------------------------------------------------
            */
            [
                'code'        => 'dashboard.view',
                'module'      => 'dashboard',
                'action'      => 'view',
                'libelle'     => 'Voir le tableau de bord',
                'description' => 'Permet d\'accéder au tableau de bord.',
            ],


            /*
            |--------------------------------------------------------------------------
            | DEMANDES
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'demandes',
                'Demandes',
                [
                    'view'   => 'Voir les demandes',
                    'create' => 'Créer une demande',
                    'edit'   => 'Modifier une demande',
                    'delete' => 'Supprimer une demande',
                    'print'  => 'Imprimer une demande',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | COTATIONS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'cotations',
                'Cotations',
                [
                    'view'   => 'Voir les cotations',
                    'create' => 'Créer une cotation',
                    'edit'   => 'Modifier une cotation',
                    'delete' => 'Supprimer une cotation',
                    'print'  => 'Imprimer une cotation',
                    'send'   => 'Envoyer une cotation',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | RÉSERVATIONS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'reservations',
                'Réservations',
                [
                    'view'   => 'Voir les réservations',
                    'create' => 'Créer une réservation',
                    'edit'   => 'Modifier une réservation',
                    'delete' => 'Supprimer une réservation',
                    'print'  => 'Imprimer une réservation',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | PLANNINGS / DÉPARTS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'plannings',
                'Plannings',
                [
                    'view'   => 'Voir les plannings',
                    'create' => 'Créer un planning',
                    'edit'   => 'Modifier un planning',
                    'delete' => 'Supprimer un planning',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | CLIENTS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'clients',
                'Clients',
                [
                    'view'   => 'Voir les clients',
                    'create' => 'Créer un client',
                    'edit'   => 'Modifier un client',
                    'delete' => 'Supprimer un client',
                    'print'  => 'Imprimer les informations d\'un client',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | FOURNISSEURS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'fournisseurs',
                'Fournisseurs',
                [
                    'view'   => 'Voir les fournisseurs',
                    'create' => 'Créer un fournisseur',
                    'edit'   => 'Modifier un fournisseur',
                    'delete' => 'Supprimer un fournisseur',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | DESTINATIONS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'destinations',
                'Destinations',
                [
                    'view'   => 'Voir les destinations',
                    'create' => 'Créer une destination',
                    'edit'   => 'Modifier une destination',
                    'delete' => 'Supprimer une destination',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | HÔTELS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'hotels',
                'Hôtels',
                [
                    'view'   => 'Voir les hôtels',
                    'create' => 'Créer un hôtel',
                    'edit'   => 'Modifier un hôtel',
                    'delete' => 'Supprimer un hôtel',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | VOLS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'vols',
                'Vols',
                [
                    'view'   => 'Voir les vols',
                    'create' => 'Créer un vol',
                    'edit'   => 'Modifier un vol',
                    'delete' => 'Supprimer un vol',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | EXCURSIONS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'excursions',
                'Excursions',
                [
                    'view'   => 'Voir les excursions',
                    'create' => 'Créer une excursion',
                    'edit'   => 'Modifier une excursion',
                    'delete' => 'Supprimer une excursion',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | TRANSFERTS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'transferts',
                'Transferts',
                [
                    'view'   => 'Voir les transferts',
                    'create' => 'Créer un transfert',
                    'edit'   => 'Modifier un transfert',
                    'delete' => 'Supprimer un transfert',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | CROISIÈRES
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'croisieres',
                'Croisières',
                [
                    'view'   => 'Voir les croisières',
                    'create' => 'Créer une croisière',
                    'edit'   => 'Modifier une croisière',
                    'delete' => 'Supprimer une croisière',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | RESTAURANTS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'restaurants',
                'Restaurants',
                [
                    'view'   => 'Voir les restaurants',
                    'create' => 'Créer un restaurant',
                    'edit'   => 'Modifier un restaurant',
                    'delete' => 'Supprimer un restaurant',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | FORFAITS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'forfaits',
                'Forfaits',
                [
                    'view'   => 'Voir les forfaits',
                    'create' => 'Créer un forfait',
                    'edit'   => 'Modifier un forfait',
                    'delete' => 'Supprimer un forfait',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | CIRCUITS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'circuits',
                'Circuits',
                [
                    'view'   => 'Voir les circuits',
                    'create' => 'Créer un circuit',
                    'edit'   => 'Modifier un circuit',
                    'delete' => 'Supprimer un circuit',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | ASSURANCES
            |--------------------------------------------------------------------------
            */
            // ...self::module(
            //     'assurances',
            //     'Assurances',
            //     [
            //         'view'   => 'Voir les assurances',
            //         'create' => 'Créer une assurance',
            //         'edit'   => 'Modifier une assurance',
            //         'delete' => 'Supprimer une assurance',
            //     ]
            // ),


            /*
            |--------------------------------------------------------------------------
            | FACTURES
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'factures',
                'Factures',
                [
                    'view'   => 'Voir les factures',
                    'create' => 'Créer une facture',
                    'edit'   => 'Modifier une facture',
                    'delete' => 'Supprimer une facture',
                    'print'  => 'Imprimer une facture',
                    'export' => 'Exporter les factures',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | PAIEMENTS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'paiements',
                'Paiements',
                [
                    'view'   => 'Voir les paiements',
                    'create' => 'Enregistrer un paiement',
                    'edit'   => 'Modifier un paiement',
                    'delete' => 'Supprimer ou annuler un paiement',
                    'print'  => 'Imprimer un paiement',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | RAPPORTS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'rapports',
                'Rapports',
                [
                    'view'   => 'Voir les rapports',
                    'export' => 'Exporter les rapports',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | UTILISATEURS
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'utilisateurs',
                'Utilisateurs',
                [
                    'view'        => 'Voir les utilisateurs',
                    'create'      => 'Créer un utilisateur',
                    'edit'        => 'Modifier un utilisateur',
                    'delete'      => 'Supprimer un utilisateur',
                    'permissions' => 'Gérer les permissions',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | DEVISES
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'devises',
                'Devises',
                [
                    'view'        => 'Voir les devises',
                    'create'      => 'Créer une devise',
                    'edit'        => 'Modifier une devise',
                    'delete'      => 'Supprimer une devise',
                    'default'     => 'Définir la devise par défaut',
                ]
            ),


            /*
            |--------------------------------------------------------------------------
            | PARAMÈTRES
            |--------------------------------------------------------------------------
            */
            ...self::module(
                'parametres',
                'Paramètres',
                [
                    'view'   => 'Voir les paramètres',
                    'edit'   => 'Modifier les paramètres',
                ]
            ),
        ];
    }


    /**
     * Génère automatiquement les permissions d'un module.
     */
    private static function module(
        string $module,
        string $moduleLabel,
        array $actions
    ): array {

        $permissions = [];

        foreach ($actions as $action => $libelle) {

            $permissions[] = [
                'code'        => $module . '.' . $action,
                'module'      => $module,
                'action'      => $action,
                'libelle'     => $libelle,
                'description' => $libelle . '.',
            ];
        }

        return $permissions;
    }
}