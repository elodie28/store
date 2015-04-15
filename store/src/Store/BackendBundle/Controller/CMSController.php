<?php

// L'endroit où je déclare ma class CMSController
namespace Store\BackendBundle\Controller;

// J'inclus la class Controller de Symfony pour pouvoir hériter de cette classe
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Store\BackendBundle\Form\CmsType;
use Store\BackendBundle\Entity\Cms;
use Symfony\Component\HttpFoundation\Request;


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


    /**
     * Page création d'un CMS
     * Je récupère l'objet Request qui contient toutes mes données en GET, POST ...
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request) {

        // Je crée un nouvel objet entité Cms
        // À chaque fois que je crée un objet d'une classe, je dois user la classe
        $cms = new Cms();

        $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine
        $jeweler = $em->getRepository('StoreBackendBundle:Jeweler')->find(1); // Je récupère le jeweler numéro 1
        $cms->setJeweler($jeweler); // J'associe mon jeweler à mon CMS

        // Je crée un formulaire de CMS en l'associant avec mon CMS
        $form = $this->createForm(new CmsType(), $cms, array(
            'attr' => array(
                'method' => 'post',
                'novalidate' => 'novalidate', //(pour virer la validation HTML5)
                'action' => $this->generateUrl('store_backend_cms_new') // l'URL de la route new
                // action de mon formulaire pointe vers cette même action de contrôleur
            )
        ));

        // Je fusionne ma requête avec mon formulaire
        $form->handleRequest($request);

        // Si la totalité de mon formulaire est valide
        if($form->isValid()) {
            $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine
            $em->persist($cms); // J'enregistre mon objet cms dans Doctrine
            $em->flush(); // J'envoie ma requête d'insert à ma table cms

            return $this->redirectToRoute('store_backend_cms_list'); // redirection selon la route
        }

        // createView() est toujours la méthode utilisée pour renvoyer la vue d'un formulaire
        return $this->render('StoreBackendBundle:CMS:new.html.twig', array(
            'form' => $form->createView()
        ));
    }

}