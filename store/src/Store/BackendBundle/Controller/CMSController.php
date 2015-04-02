<?php

// L'endroit où je déclare ma class CMSController
namespace Store\BackendBundle\Controller;

// J'inclus la class Controller de Symfony pour pouvoir hériter de cette class
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class CMSController
 * @package Store\BackendBundle\Controller
 */
class CMSController extends Controller {

    /**
     * Page liste des CMS
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {

        // Je retourne la vue List contenue dans le dossier CMS de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:CMS:list.html.twig');
    }

    /**
     * Page view d'un seul CMS
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($id, $name) {

        // Je retourne la vue View contenue dans le dossier CMS de mon bundle StorebackendBundle
        // où je transmets l'id en vue
        return $this->render('StoreBackendBundle:CMS:view.html.twig',
            array( // = tableau associatif = le transporteur
                'id' => $id, // Le nom de ma clef sera le nom de ma variable en vue
                'name' => $name
            )
        );
    }
}