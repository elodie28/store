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
     * Page Création d'un produit
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
        $form = $this->createForm(new ProductType(1), $product, array(
            'validation_groups' => 'new',
            'attr' => array(
                'method' => 'post',
                'novalidate' => 'novalidate', //(pour enlever la validation HTML5)
                'action' => $this->generateUrl('store_backend_product_new') // l'URL de la route new
                // action de mon formulaire pointe vers cette même action de contrôleur
            )
        ));

        // Je récupère le bouton submit du formulaire dans le fichier ProductType et je le place dans le contrôleur pour pouvoir le personnaliser
        // J'utilise $form au lieu de $builder et j'ajoute un label pour personnaliser le texte du bouton
        // Je n'aurai pas pu le personnaliser sans le mettre dans le contrôleur car tout le formulaire est dans une vue centrale (partielle)
        $form->add('envoyer', 'submit', array(
            'label' => 'Ajouter un nouveau produit',
            'attr'  => array(
                'class' => 'btn btn-primary btn-sm'
            )
        ));

        // Je fusionne ma requête avec mon formulaire
        $form->handleRequest($request); // le formulaire lis la requête

        // Si la totalité de mon formulaire est valide
        if($form->isValid()) {

            //J'upload mon fichier en faisant appel à la méthode upload si mon formulaire est valide
            $product->upload();

            $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine
            $em->persist($product); // J'enregistre mon objet product dans Doctrine
            $em->flush(); // J'envoie ma requête d'insert à ma table product

            // Permet d'écrire un message flash avec pour clef "success" et un message de confirmation
            $this->get('session')->getFlashBag()->add(
                'success',
                'Votre produit a bien été créé'
            );

            // Je récupère la quantité du produit enregistré
            $quantity = $product->getQuantity();

            if($quantity == 1) {
                $this->get('session')->getFlashBag()->add(
                    'warning',
                    "Ce bijou n'existe qu'en un seul exemplaire"
                );
            }

            return $this->redirectToRoute('store_backend_product_list'); // redirection selon la route
        }

        // createView() est toujours la méthode utilisée pour renvoyer la vue d'un formulaire
        return $this->render('StoreBackendBundle:Product:new.html.twig', array(
            'form' => $form->createView()
        ));
    }



    /**
     * Page Édition d'un produit
     * Je récupère l'objet Request qui contient toutes mes données en GET, POST ...
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('StoreBackendBundle:Product')->find($id);

        // Je crée un formulaire de produit en l'associant avec mon produit
        $form = $this->createForm(new ProductType(1), $product, array(
            'validation_groups' => 'edit',
            'attr' => array(
                'method' => 'post',
                'novalidate' => 'novalidate',
                'action' => $this->generateUrl('store_backend_product_edit', array(
                        'id' => $id
                    ))
            )
        ));

        // Je récupère le bouton submit du formulaire dans le fichier ProductType et je le place dans le contrôleur pour pouvoir le personnaliser
        // J'utilise $form au lieu de $builder et j'ajoute un label pour personnaliser le texte du bouton
        // Je n'aurai pas pu le personnaliser sans le mettre dans le contrôleur car tout le formulaire est dans une vue centrale (partielle)
        $form->add('envoyer', 'submit', array(
            'label' => 'Éditer le produit',
            'attr'  => array(
                'class' => 'btn btn-primary btn-sm'
            )
        ));

        $form->handleRequest($request);

        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            // Permet d'écrire un message flash avec pour clef "success" et un message de confirmation
            $this->get('session')->getFlashBag()->add(
                'success',
                'Votre produit a bien été mis à jour'
            );

            return $this->redirectToRoute('store_backend_product_list');
        }

        // createView() est toujours la méthode utilisée pour renvoyer la vue d'un formulaire
        return $this->render('StoreBackendBundle:Product:edit.html.twig', array(
            'form' => $form->createView()
        ));
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

        // Permet d'écrire un message flash avec pour clef "success" et un message de confirmation
        $this->get('session')->getFlashBag()->add(
            'success',
            'Votre produit a bien été supprimé'
        );

        return $this->redirectToRoute('store_backend_product_list'); // redirection vers la liste des produits

    }

}