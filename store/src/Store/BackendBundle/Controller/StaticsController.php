<?php

// L'endroit où je déclare ma class StaticsController
namespace Store\BackendBundle\Controller;

// J'inclus la class Controller de Symfony pour pouvoir hériter de cette class
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class StaticsController
 * @package Store\BackendBundle\Controller
 */
class StaticsController extends Controller {

    /**
     * Page statique Contact
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactAction() {

       // Je retourne la vue Contact contenue dans le dossier Statics de mon bundle StorebackendBundle
       return $this->render('StoreBackendBundle:Statics:contact.html.twig');
    }

    /**
     * Page statique About
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function aboutAction() {

        // Je retourne la vue About contenue dans le dossier Statics de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:Statics:about.html.twig');
    }

    /**
     * Page statique Terms
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function termsAction() {

        // Je retourne la vue Terms contenue dans le dossier Statics de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:Statics:terms.html.twig');
    }

    /**
     * Page statique Concept
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function conceptAction() { // mentionLegalesAction()

        // Je retourne la vue Concept contenue dans le dossier Statics de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:Statics:concept.html.twig');
    }


}


