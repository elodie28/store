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

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Je récupère toutes les catégories du jeweler numéro 1
        $categories = $em->getRepository('StoreBackendBundle:Category')->getCategoryByUser(1); // NomduBundle:Nomdel'entité

        // Je retourne la vue List contenue dans le dossier Category de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:Category:list.html.twig', array(
            'categories' => $categories
        ));
    }


    /**
     * Page view d'une seule catégorie
     * @param $id
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($id, $name) {

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Je récupère 1 catégorie de ma BDD avec la méthode find()
        $category = $em->getRepository('StoreBackendBundle:Category')->find($id); // NomduBundle:Nomdel'entité

        // Je retourne la vue View contenue dans le dossier Category de mon bundle StorebackendBundle
        // où je transmets l'id en vue
        return $this->render('StoreBackendBundle:Category:view.html.twig',
            array( // = tableau associatif = le transporteur
                'category' => $category, // Le nom de ma clef sera le nom de ma variable en vue
            )
        );
    }


    /**
     * Action de suppression
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction($id) {

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Je récupère 1 catégorie avec la méthode find()
        $category = $em->getRepository('StoreBackendBundle:Category')->find($id); // NomduBundle:Nomdel'entité

        $em->remove($category); // supprime la catégorie
        $em->flush(); // la fonction flush permet d'envoyer la requête en BDD

        return $this->redirectToRoute('store_backend_category_list'); // redirection vers la liste des catégories

    }

}