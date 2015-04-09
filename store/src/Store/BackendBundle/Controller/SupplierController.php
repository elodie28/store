<?php

// L'endroit où je déclare ma class SupplierController
namespace Store\BackendBundle\Controller;

// J'inclus la class Controller de Symfony pour pouvoir hériter de cette class
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


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

        // Je récupère tous les fournisseurs de ma BDD avec la méthode findAll()
        $suppliers = $em->getRepository('StoreBackendBundle:Supplier')->findAll(); // NomduBundle:Nomdel'entité

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
}