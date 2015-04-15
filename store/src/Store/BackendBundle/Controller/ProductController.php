<?php

// L'endroit où je déclare ma class ProductController
namespace Store\BackendBundle\Controller;

// J'inclus la class Controller de Symfony pour pouvoir hériter de cette class
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Store\BackendBundle\Form\ProductType;
use Store\BackendBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;


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
     * Je récupère l'objet Request qui contient toutes mes données en GET, POST ...
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request) {

        // Je crée un nouvel objet entité Product
        // À chaque fois que je crée un objet d'une classe, je dois user la classe
        $product = new Product();

        $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine
        $jeweler = $em->getRepository('StoreBackendBundle:Jeweler')->find(1); // Je récupère le jeweler numéro 1
        $product->setJeweler($jeweler); // J'associe mon jeweler à mon produit

        // J'initialise la quantité et le prix de mon produit sauf si l'initialisation se fait dans le constructeur
        // $product->setQuantity(0);
        // $product->setPrice(0);

        // Je crée un formulaire de produit en l'associant avec mon produit
        $form = $this->createForm(new ProductType(), $product, array(
            'attr' => array(
                'method' => 'post',
                'novalidate' => 'novalidate', //(pour virer la validation HTML5)
                'action' => $this->generateUrl('store_backend_product_new') // l'URL de la route new
                // action de mon formulaire pointe vers cette même action de contrôleur
            )
        ));

        // Je fusionne ma requête avec mon formulaire
        $form->handleRequest($request);

        // Si la totalité de mon formulaire est valide
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine
            $em->persist($product); // J'enregistre mon objet product dans Doctrine
            $em->flush(); // J'envoie ma requête d'insert à ma table product

            return $this->redirectToRoute('store_backend_product_list'); // redirection selon la route
        }

        // createView() est toujours la méthode utilisée pour renvoyer la vue d'un formulaire
        return $this->render('StoreBackendBundle:Product:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

}