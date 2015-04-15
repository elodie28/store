<?php

// L'endroit où je déclare ma class CategoryController
namespace Store\BackendBundle\Controller;

// J'inclus la class Controller de Symfony pour pouvoir hériter de cette classe
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Store\BackendBundle\Form\CategoryType;
use Store\BackendBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;


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


    /**
     * Page création d'une catégorie
     * Je récupère l'objet Request qui contient toutes mes données en GET, POST ...
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request) {

        // Je crée un nouvel objet entité Category
        // À chaque fois que je crée un objet d'une classe, je dois user la classe
        $category = new Category();

        $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine
        $jeweler = $em->getRepository('StoreBackendBundle:Jeweler')->find(1); // Je récupère le jeweler numéro 1
        $category->setJeweler($jeweler); // J'associe mon jeweler à ma catégorie

        // Je crée un formulaire de catégorie en l'associant avec ma catégorie
        $form = $this->createForm(new CategoryType(), $category, array(
            'attr' => array(
                'method' => 'post',
                'novalidate' => 'novalidate', //(pour virer la validation HTML5)
                'action' => $this->generateUrl('store_backend_category_new') // l'URL de la route new
                // action de mon formulaire pointe vers cette même action de contrôleur
            )
        ));

        // Je fusionne ma requête avec mon formulaire
        $form->handleRequest($request);

        // Si la totalité de mon formulaire est valide
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine
            $em->persist($category); // J'enregistre mon objet product dans Doctrine
            $em->flush(); // J'envoie ma requête d'insert à ma table category

            return $this->redirectToRoute('store_backend_category_list'); // redirection selon la route
        }

        // createView() est toujours la méthode utilisée pour renvoyer la vue d'un formulaire
        return $this->render('StoreBackendBundle:Category:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

}