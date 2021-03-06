<?php

// L'endroit où je déclare ma class CategoryController
namespace Store\BackendBundle\Controller;

// J'inclus la class Controller de Symfony pour pouvoir hériter de cette classe
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Store\BackendBundle\Form\CategoryType;
use Store\BackendBundle\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


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

        // récupérer l'utilisateur courant connecté (à la place du 1 dans getCategoryByUser(1))
        $user = $this->getUser();

        // Je récupère toutes les catégories du jeweler connecté
        $categories = $em->getRepository('StoreBackendBundle:Category')->getCategoryByUser($user); // NomduBundle:Nomdel'entité

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
     * Page création d'une catégorie
     * Je récupère l'objet Request qui contient toutes mes données en GET, POST ...
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request) {

        // Je crée un nouvel objet entité Category
        // À chaque fois que je crée un objet d'une classe, je dois user la classe
        $category = new Category();

        // récupérer l'utilisateur courant connecté (à la place du 1 dans find(1))
        $user = $this->getUser();

//        $jeweler = $em->getRepository('StoreBackendBundle:Jeweler')->find(1); // Je récupère le jeweler numéro 1

        $category->setJeweler($user); // J'associe ma catégorie à l'utilisateur jeweler connecté

        // Je crée un formulaire de catégorie en l'associant avec ma catégorie
        $form = $this->createForm(new CategoryType($user), $category, array(
            'validation_groups' => 'new',
            'attr' => array(
                'method' => 'post',
                'novalidate' => 'novalidate', //(pour virer la validation HTML5)
                'action' => $this->generateUrl('store_backend_category_new') // l'URL de la route new
                // action de mon formulaire pointe vers cette même action de contrôleur
            )
        ));

        // Je récupère le bouton submit du formulaire dans le fichier CategoryType et je le place dans le contrôleur pour pouvoir le personnaliser
        // J'utilise $form au lieu de $builder et j'ajoute un label pour personnaliser le texte du bouton
        // Je n'aurai pas pu le personnaliser sans le mettre dans le contrôleur car tout le formulaire est dans une vue centrale (partielle)
        $form->add('envoyer', 'submit', array(
            'label' => 'Ajouter une nouvelle catégorie',
            'attr'  => array(
                'class' => 'btn btn-primary btn-sm'
            )
        ));

        // Je fusionne ma requête avec mon formulaire
        $form->handleRequest($request);

        // Si la totalité de mon formulaire est valide
        if($form->isValid()) {

            $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine

            //J'upload mon fichier en faisant appel à la méthode upload si mon formulaire est valide
            $category->upload();

            $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine
            $em->persist($category); // J'enregistre mon objet product dans Doctrine
            $em->flush(); // J'envoie ma requête d'insert à ma table category

            // Permet d'écrire un message flash avec pour clef "success" et un message de confirmation
            $this->get('session')->getFlashBag()->add(
                'success',
                'Votre catégorie a bien été créée'
            );

            return $this->redirectToRoute('store_backend_category_list'); // redirection selon la route
        }

        // createView() est toujours la méthode utilisée pour renvoyer la vue d'un formulaire
        return $this->render('StoreBackendBundle:Category:new.html.twig', array(
            'form' => $form->createView()
        ));
    }



    /**
     * Page Édition d'une catégorie
     * Je récupère l'objet Request qui contient toutes mes données en GET, POST ...
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * is_granted
     * 1er argument : attribut à vide
     * 2ème argument objet : produit
     * @Security("is_granted('', id)")
     */
    public function editAction(Request $request, Category $id) {

        // Je crée un formulaire de category en l'associant avec ma category
        $form = $this->createForm(new CategoryType(1), $id, array(
            'validation_groups' => 'edit',
            'attr' => array(
                'method' => 'post',
                'novalidate' => 'novalidate',
                'action' => $this->generateUrl('store_backend_category_edit', array(
                        'id' => $id
                    ))
            )
        ));

        // Je récupère le bouton submit du formulaire dans le fichier CategoryType et je le place dans le contrôleur pour pouvoir le personnaliser
        // J'utilise $form au lieu de $builder et j'ajoute un label pour personnaliser le texte du bouton
        // Je n'aurai pas pu le personnaliser sans le mettre dans le contrôleur car tout le formulaire est dans une vue centrale (partielle)
        $form->add('envoyer', 'submit', array(
            'label' => 'Éditer la catégorie',
            'attr'  => array(
                'class' => 'btn btn-primary btn-sm'
            )
        ));

        $form->handleRequest($request); // Je lie mon formulaire à ma requête et j'hydrate mon formulaire des données que j'ai saisies

        if($form->isValid()) {

            //J'upload mon fichier en faisant appel à la méthode upload si mon formulaire est valide
            $id->upload();

            $em = $this->getDoctrine()->getManager();
            $em->persist($id);
            $em->flush();

            // Permet d'écrire un message flash avec pour clef "success" et un message de confirmation
            $this->get('session')->getFlashBag()->add(
                'success',
                'Votre catégorie a bien été mise à jour'
            );

            return $this->redirectToRoute('store_backend_category_list');
        }

        // createView() est toujours la méthode utilisée pour renvoyer la vue d'un formulaire
        return $this->render('StoreBackendBundle:Category:edit.html.twig', array(
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

        // Je récupère 1 catégorie avec la méthode find()
        $category = $em->getRepository('StoreBackendBundle:Category')->find($id); // NomduBundle:Nomdel'entité

        $em->remove($category); // supprime la catégorie
        $em->flush(); // la fonction flush permet d'envoyer la requête en BDD

        // Permet d'écrire un message flash avec pour clef "success" et un message de confirmation
        $this->get('session')->getFlashBag()->add(
            'success',
            'Votre catégorie a bien été supprimée'
        );

        return $this->redirectToRoute('store_backend_category_list'); // redirection vers la liste des catégories
    }



    /**
     * Action d'activation d'une catégorie dans la page liste des catégories
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activateAction(Category $id, $action){

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        $id->setActive($action); // J'associe l'action activate à l'id de ma catégorie
        $em->persist($id); // J'enregistre l'id de la catégorie dans Doctrine
        $em->flush(); // J'envoie ma requête  à ma table category

        // Permet d'écrire un message flash avec pour clef "info" et un message de confirmation
        $this->get('session')->getFlashBag()->add(
            'info',
            'Votre catégorie a bien été désactivée'
        );

        return $this->redirectToRoute('store_backend_category_list'); // redirection vers la liste des catégories
    }

    /**
     * Action de désactivation d'une catégorie dans la page liste des catégories
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function desactivateAction(Category $id, $action){

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        $id->setActive($action); // J'associe l'action desactivate à l'id de ma catégorie
        $em->persist($id); // J'enregistre l'id de la catégorie dans Doctrine
        $em->flush(); // J'envoie ma requête  à ma table product

        // Permet d'écrire un message flash avec pour clef "info" et un message de confirmation
        $this->get('session')->getFlashBag()->add(
            'info',
            'Votre catégorie a bien été activée'
        );

        return $this->redirectToRoute('store_backend_category_list'); // redirection vers la liste des catégories
    }

}