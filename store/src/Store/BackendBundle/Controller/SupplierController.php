<?php

// L'endroit où je déclare ma class SupplierController
namespace Store\BackendBundle\Controller;

// J'inclus la class Controller de Symfony pour pouvoir hériter de cette class
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Store\BackendBundle\Entity\Supplier;


/**
 * Class SupplierController
 * @package Store\BackendBundle\Controller
 */
class SupplierController extends Controller {

    /**
     * Page liste des fournisseurs
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // récupérer l'utilisateur courant connecté (à la place du 1 dans getSupplierByUser(1))
        $user = $this->getUser();

        // Je récupère tous les fournisseurs des produits du jeweler connecté
        $suppliers = $em->getRepository('StoreBackendBundle:Supplier')->getSupplierByUser($user); // NomduBundle:Nomdel'entité

        // Je retourne la vue List contenue dans le dossier Supplier de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:Supplier:list.html.twig', array(
            'suppliers' => $suppliers
        ));
    }

    /**
     * Page view d'un seul fournisseur
     * @param $id
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($id, $name) {

        // Je retourne la vue View contenue dans le dossier Supplier de mon bundle StorebackendBundle
        // où je transmets l'id en vue
        return $this->render('StoreBackendBundle:Supplier:view.html.twig',
            array( // = tableau associatif = le transporteur
                'id' => $id, // Le nom de ma clef sera le nom de ma variable en vue
                'name' => $name
            )
        );
    }



    public function removeAction($id) {

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Je récupère 1 fournisseur avec la méthode find()
        $supplier = $em->getRepository('StoreBackendBundle:Supplier')->find($id); // NomduBundle:Nomdel'entité

        $em->remove($supplier); // supprime le fournisseur
        $em->flush(); // la fonction flush permet d'envoyer la requête en BDD

        return $this->redirectToRoute('store_backend_supplier_list'); // redirection vers la liste des fournisseurs
    }



    /**
     * Action d'activation d'un fournisseur dans la page liste des fournisseurs
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activateAction(Supplier $id, $action){

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        $id->setActive($action); // J'associe l'action activate à l'id de mon fournisseur
        $em->persist($id); // J'enregistre l'id du fournisseur dans Doctrine
        $em->flush(); // J'envoie ma requête  à ma table supplier

        // Permet d'écrire un message flash avec pour clef "info" et un message de confirmation
        $this->get('session')->getFlashBag()->add(
            'info',
            'Votre fournisseur a bien été désactivé'
        );

        return $this->redirectToRoute('store_backend_supplier_list'); // redirection vers la liste des fournisseurs
    }

    /**
     * Action de désactivation d'un fournisseur dans la page liste des fournisseurs
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function desactivateAction(Supplier $id, $action){

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        $id->setActive($action); // J'associe l'action desactivate à l'id de mon fournisseur
        $em->persist($id); // J'enregistre l'id du dournisseur dans Doctrine
        $em->flush(); // J'envoie ma requête  à ma table supplier

        // Permet d'écrire un message flash avec pour clef "info" et un message de confirmation
        $this->get('session')->getFlashBag()->add(
            'info',
            'Votre fournisseur a bien été activé'
        );

        return $this->redirectToRoute('store_backend_supplier_list'); // redirection vers la liste des fournisseurs
    }
}