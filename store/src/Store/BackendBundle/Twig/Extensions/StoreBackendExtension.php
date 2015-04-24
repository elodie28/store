<?php

namespace Store\BackendBundle\Twig\Extensions;


/**
 * Class StoreBackendExtension
 * @package Store\BackendBundle\Twig\Extension
 */
class StoreBackendExtension extends \Twig_Extension {



    /**
     * Fonction qui me retourne tous mes filtres créés
     * @return array
     */
    public function getFilters() {

        // Retourne un tableau de filtres créés
        return array(
            // Twig_SimpleFilter :
            // - 1er argument est le nom du filtre en TWIG
            // - 2ème argument est le nom de la fonction que je vais créer
            new \Twig_SimpleFilter('state', array($this, 'state')),
            new \Twig_SimpleFilter('active', array($this, 'active'))

        );
    }



    /**
     * State helper
     * @param $state
     * @return string
     */
    public function state($state) {

        if($state == 3) {

            $badge = "<span class='label label-danger'> Annulée </span>";

        } elseif($state == 2) {

            $badge = "<span class='label label-success'> Envoyée </span>";


        } elseif($state == 1) {

            $badge = "<span class='label label-info'> En cours </span>";

        } else {

            $badge = "<span class='label label-warning'> En attente de paiement </span>";
        }

        return $badge;

    }



    /**
     * Active helper
     * @param $active
     * @return string
     */
    public function active($active) {

        if($active == 1) {

            $badge = "<span class='fa fa-check btn btn-success'> </span>";

        } else {

            $badge = "<span class='fa fa-times btn btn-danger'> </span>";

        }

        return $badge;

    }



    /**
     * Get name of my extension
     * @return string
     */
    public function getName() {

        return 'store_backend_extension';
    }

}