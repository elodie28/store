<?php

// L'endroit où je déclare ma class CategoryController
namespace Store\BackendBundle\Controller;

// J'inclus la class Controller de Symfony pour pouvoir hériter de cette class
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class CategoryController
 * @package Store\BackendBundle\Controller
 */
class CategoryController extends Controller {

    /**
     * Page liste des catégories
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {

        // Je retourne la vue List contenue dans le dossier Category de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:Category:list.html.twig');
    }


    /**
     * Page view d'une seule catégorie
     * @param $id
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($id, $name) {

        // Je retourne la vue View contenue dans le dossier Category de mon bundle StorebackendBundle
        // où je transmets l'id en vue
        return $this->render('StoreBackendBundle:Category:view.html.twig',
            array( // = tableau associatif = le transporteur
                'id' => $id, // Le nom de ma clef sera le nom de ma variable en vue
                'name' => $name
            )
        );
    }

}