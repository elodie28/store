<?php

// L'endroit où je déclare ma class ProductController
namespace Store\BackendBundle\Controller;

// J'inclus la class Controller de Symfony pour pouvoir hériter de cette class
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Store\BackendBundle\Form\ProductType;


/**
 * Class ProductController
 * @package Store\BackendBundle\Controller
 */
class ProductController extends Controller {

    /**
     * Page liste des produits
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Je récupère tous les produits du jeweler numéro 1
        $products = $em->getRepository('StoreBackendBundle:Product')->getProductByUser(1); // NomduBundle:Nomdel'entité
        // = requête : SELECT * FROM product

        // Je retourne la vue List contenue dans le dossier Product de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:Product:list.html.twig', array(
            'products' => $products
        ));
    }


    /**
     * Page view d'un seul produit
     * @param $id
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($id, $name) {

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Je récupère 1 produit avec la méthode find()
        $product = $em->getRepository('StoreBackendBundle:Product')->find($id); // NomduBundle:Nomdel'entité

        // Je retourne la vue View contenue dans le dossier Product de mon bundle StorebackendBundle
        // où je transmets l'id en vue
        return $this->render('StoreBackendBundle:Product:view.html.twig',
            array( // = tableau associatif = le transporteur
                'product' => $product // Le nom de ma clef sera le nom de ma variable en vue
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

        // Je récupère 1 produit avec la méthode find()
        $product = $em->getRepository('StoreBackendBundle:Product')->find($id); // NomduBundle:Nomdel'entité

        $em->remove($product); // supprime le produit
        $em->flush(); // la fonction flush permet d'envoyer la requête en BDD

        return $this->redirectToRoute('store_backend_product_list'); // redirection vers la liste des produits

    }


    /**
     * Page création d'un produit
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction() {

        // Je crée un formulaire de produit
        $form = $this->createForm(new ProductType());

        return $this->render('StoreBackendBundle:Product:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

}