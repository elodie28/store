<?php

// L'endroit où je déclare ma class ProductController
namespace Store\BackendBundle\Controller;

// J'inclus la class Controller de Symfony pour pouvoir hériter de cette class
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Store\BackendBundle\Form\ProductType;
use Store\BackendBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

// pour restreindre l'accès de cette page pour le rôle ROLE_COMMERCIAL (à lier avec l'annotation @Security pour la function listAction)
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Class ProductController
 * @package Store\BackendBundle\Controller
 */
class ProductController extends Controller {


    /**
     * Page liste des produits
     * @return \Symfony\Component\HttpFoundation\Response
     * @Security("has_role('ROLE_COMMERCIAL')")
     */
    public function listAction() {

//        // Méthode n° 1 : si on veut restreindre l'accès au niveau de la méthode de contrôleur
//        if (false === $this->get('security.context')->isGranted('ROLE_COMMERCIAL')) {
//            throw new AccessDeniedException("Accès interdit pour ce type de contenu");
//        }

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // récupérer l'utilisateur courant connecté (à la place du 1 dans getProductByUser(1))
        $user = $this->getUser();

        // Je récupère tous les produits du jeweler connecté
        $products = $em->getRepository('StoreBackendBundle:Product')->getProductByUser($user); // NomduBundle:Nomdel'entité
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


        // récupérer l'utilisateur courant connecté (à la place du 1 dans find(1))
        $user = $this->getUser();

//        $jeweler = $em->getRepository('StoreBackendBundle:Jeweler')->find($user); // Je récupère le jeweler connecté

        $product->setJeweler($user); // J'associe mon produit à l'utilisateur jeweler connecté

        // J'initialise la quantité et le prix de mon produit sauf si l'initialisation se fait dans le constructeur
        // $product->setQuantity(0);
        // $product->setPrice(0);

        // Je crée un formulaire de produit en l'associant avec mon produit
        $form = $this->createForm(new ProductType($user), $product, array(
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

            $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine

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

            // Si ma quantité de produits est égale à 1
            if($product->getQuantity() == 1) {

                $this->get('store.backend.notification')
                    ->notify($product->getId(),
                        "Il ne vous reste plus qu'un seul exemplaire de votre produit " . $product->getTitle() . ".",
                        'product',
                        'danger'
                    );

                // ou si ma quantité de produits est inférieure à 5
            } elseif($product->getQuantity() < 5) {

                // $this->get() => accède au conteneur de services
                // La méthode notify() sera exécutée avec un message d'alerte concernant la quantité restante de produits
                $this->get('store.backend.notification')
                    ->notify($product->getId(),
                        "Attention, votre produit " . $product->getTitle() . " est bientôt épuisé.",
                        "product",
                        "warning");
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

            $product->upload();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();


            // Création d'un écouteur ProductListener.php

//            // Si ma quantité de produits est égale à 1
//            if($product->getQuantity() == 1) {
//
//                $this->get('store.backend.notification')
//                    ->notify($product->getId(),
//                        "Il ne vous reste plus qu'un seul exemplaire de votre produit " . $product->getTitle() . ".",
//                        'product',
//                        'danger'
//                    );
//
//            // ou si ma quantité de produits est inférieure à 5
//            } elseif($product->getQuantity() < 5) {
//
//                // $this->get() => accède au conteneur de services
//                // La méthode notify() sera exécutée avec un message d'alerte concernant la quantité restante de produits
//                $this->get('store.backend.notification')
//                    ->notify($product->getId(),
//                        "Attention, votre produit " . $product->getTitle() . " est bientôt épuisé.",
//                        "product",
//                        "warning");
//            }



            // Permet d'écrire un message flash avec pour clef "success" et un message de confirmation
            $this->get('session')->getFlashBag()->add(
                'success',
                'Votre produit a bien été mis à jour'
            );

            return $this->redirectToRoute('store_backend_product_list');
        }

        // createView() est toujours la méthode utilisée pour renvoyer la vue d'un formulaire
        return $this->render('StoreBackendBundle:Product:edit.html.twig', array(
            'form' => $form->createView(),
            'product' => $product
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



    /**
     * Action d'activation d'un produit dans la page liste des produits
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activateAction(Product $id, $action){

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        $id->setActive($action); // J'associe l'action activate à l'id de mon produit
        $em->persist($id); // J'enregistre l'id du produit dans Doctrine
        $em->flush(); // J'envoie ma requête  à ma table product

        // Permet d'écrire un message flash avec pour clef "info" et un message de confirmation
        $this->get('session')->getFlashBag()->add(
            'info',
            'Votre produit a bien été désactivé'
        );

        return $this->redirectToRoute('store_backend_product_list'); // redirection vers la liste des produits
    }



    /**
     * Action de désactivation d'un produit dans la page liste des produits
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function desactivateAction(Product $id, $action){

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        $id->setActive($action); // J'associe l'action desactivate à l'id de mon produit
        $em->persist($id); // J'enregistre l'id du produit dans Doctrine
        $em->flush(); // J'envoie ma requête  à ma table product

        // Permet d'écrire un message flash avec pour clef "info" et un message de confirmation
        $this->get('session')->getFlashBag()->add(
            'info',
            'Votre produit a bien été activé'
        );

        return $this->redirectToRoute('store_backend_product_list'); // redirection vers la liste des produits
    }



    /**
     * Action de mettre un produit en couverture dans la page liste des produits
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function coverAction(Product $id, $action){

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        $id->setCover($action); // J'associe l'action cover à l'id de mon produit
        $em->persist($id); // J'enregistre l'id du produit dans Doctrine
        $em->flush(); // J'envoie ma requête  à ma table product

        // Permet d'écrire un message flash avec pour clef "info" et un message de confirmation
        $this->get('session')->getFlashBag()->add(
            'info',
            'Vous avez bien retiré la mise en avant de votre produit'
        );

        return $this->redirectToRoute('store_backend_product_list'); // redirection vers la liste des produits
    }


    /**
     * Action de retirer un produit mis en couverture dans la page liste des produits
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function nocoverAction(Product $id, $action){

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        $id->setCover($action); // J'associe l'action nocover à l'id de mon produit
        $em->persist($id); // J'enregistre l'id du produit dans Doctrine
        $em->flush(); // J'envoie ma requête  à ma table product

        // Permet d'écrire un message flash avec pour clef "info" et un message de confirmation
        $this->get('session')->getFlashBag()->add(
            'info',
            'Vous avez bien mis en avant votre produit'
        );

        return $this->redirectToRoute('store_backend_product_list'); // redirection vers la liste des produits
    }

}