<?php

// L'endroit où je déclare ma class CMSController
namespace Store\BackendBundle\Controller;

// J'inclus la class Controller de Symfony pour pouvoir hériter de cette class
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class CMSController
 * @package Store\BackendBundle\Controller
 */
class CMSController extends Controller {

    /**
     * Page liste des CMS
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Je récupère tous les CMS du jeweler numéro 1
        $cms = $em->getRepository('StoreBackendBundle:Cms')->getCmsByUser(1); // NomduBundle:Nomdel'entité

        // Je retourne la vue List contenue dans le dossier CMS de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:CMS:list.html.twig', array(
            'cms' => $cms
        ));
    }

    /**
     * * Page view d'un seul CMS
     * @param $id
     * @param $name
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewAction($id, $name) {

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Je récupère 1 CMS de ma BDD avec la méthode find()
        $cms = $em->getRepository('StoreBackendBundle:Cms')->find($id); // NomduBundle:Nomdel'entité

        // Je retourne la vue View contenue dans le dossier CMS de mon bundle StorebackendBundle
        // où je transmets l'id en vue
        return $this->render('StoreBackendBundle:CMS:view.html.twig',
            array( // = tableau associatif = le transporteur
                'cms' => $cms, // Le nom de ma clef sera le nom de ma variable en vue
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

        // Je récupère 1 CMS avec la méthode find()
        $cms = $em->getRepository('StoreBackendBundle:Cms')->find($id); // NomduBundle:Nomdel'entité

        $em->remove($cms); // supprime le CMS
        $em->flush(); // la fonction flush permet d'envoyer la requête en BDD

        return $this->redirectToRoute('store_backend_cms_list'); // redirection vers la liste des CMS

    }
}