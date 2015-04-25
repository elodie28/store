<?php

// L'endroit où je déclare ma class JewelerController
namespace Store\BackendBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class JewelerController
 * @package Store\BackendBundle\Controller
 */
class JewelerController extends Controller {



    /**
     * My Account
     * Page de la boutique d'un bijoutier
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myaccountAction() {

        // Je retourne la vue myaccount contenue dans le dossier Jeweler de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:Jeweler:myaccount.html.twig');
    }
}
