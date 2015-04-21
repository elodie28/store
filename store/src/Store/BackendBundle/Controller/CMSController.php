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
            'validation_groups' => 'new',
            'attr' => array(
                'method' => 'post',
                'novalidate' => 'novalidate', //(pour virer la validation HTML5)
                'action' => $this->generateUrl('store_backend_cms_new') // l'URL de la route new
                // action de mon formulaire pointe vers cette même action de contrôleur
            )
        ));

        // Je récupère le bouton submit du formulaire dans le fichier CmsType et je le place dans le contrôleur pour pouvoir le personnaliser
        // J'utilise $form au lieu de $builder et j'ajoute un label pour personnaliser le texte du bouton
        // Je n'aurai pas pu le personnaliser sans le mettre dans le contrôleur car tout le formulaire est dans une vue centrale (partielle)
        $form->add('envoyer', 'submit', array(
            'label' => 'Ajouter une nouvelle page CMS',
            'attr'  => array(
                'class' => 'btn btn-primary btn-sm'
            )
        ));

        // Je fusionne ma requête avec mon formulaire
        $form->handleRequest($request);

        // Si la totalité de mon formulaire est valide
        if($form->isValid()) {

            //J'upload mon fichier en faisant appel à la méthode upload si mon formulaire est valide
            $cms->upload();

            $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine
            $em->persist($cms); // J'enregistre mon objet cms dans Doctrine
            $em->flush(); // J'envoie ma requête d'insert à ma table cms

            // Permet d'écrire un message flash avec pour clef "success" et un message de confirmation
            $this->get('session')->getFlashBag()->add(
                'success',
                'Votre page CMS a bien été créée'
            );

            return $this->redirectToRoute('store_backend_cms_list'); // redirection selon la route
        }

        // createView() est toujours la méthode utilisée pour renvoyer la vue d'un formulaire
        return $this->render('StoreBackendBundle:CMS:new.html.twig', array(
            'form' => $form->createView()
        ));
    }



    /**
     * Page Édition d'une page CMS
     * Je récupère l'objet Request qui contient toutes mes données en GET, POST ...
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $cms = $em->getRepository('StoreBackendBundle:Cms')->find($id);

        // Je crée un formulaire de page CMS en l'associant avec ma page CMS
        $form = $this->createForm(new CmsType(1), $cms, array(
            'validation_groups' => 'edit',
            'attr' => array(
                'method' => 'post',
                'novalidate' => 'novalidate',
                'action' => $this->generateUrl('store_backend_cms_edit', array(
                        'id' => $id
                    ))
            )
        ));

        // Je récupère le bouton submit du formulaire dans le fichier CmsType et je le place dans le contrôleur pour pouvoir le personnaliser
        // J'utilise $form au lieu de $builder et j'ajoute un label pour personnaliser le texte du bouton
        // Je n'aurai pas pu le personnaliser sans le mettre dans le contrôleur car tout le formulaire est dans une vue centrale (partielle)
        $form->add('envoyer', 'submit', array(
            'label' => 'Éditer la page CMS',
            'attr'  => array(
                'class' => 'btn btn-primary btn-sm'
            )
        ));

        $form->handleRequest($request);

        if($form->isValid()) {

            //J'upload mon fichier en faisant appel à la méthode upload si mon formulaire est valide
            $cms->upload();

            $em = $this->getDoctrine()->getManager();
            $em->persist($cms);
            $em->flush();

            // Permet d'écrire un message flash avec pour clef "success" et un message de confirmation
            $this->get('session')->getFlashBag()->add(
                'success',
                'Votre page CMS a bien été mise à jour'
            );

            return $this->redirectToRoute('store_backend_cms_list');
        }

        // createView() est toujours la méthode utilisée pour renvoyer la vue d'un formulaire
        return $this->render('StoreBackendBundle:CMS:edit.html.twig', array(
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

        // Je récupère 1 CMS avec la méthode find()
        $cms = $em->getRepository('StoreBackendBundle:Cms')->find($id); // NomduBundle:Nomdel'entité

        $em->remove($cms); // supprime le CMS
        $em->flush(); // la fonction flush permet d'envoyer la requête en BDD

        // Permet d'écrire un message flash avec pour clef "success" et un message de confirmation
        $this->get('session')->getFlashBag()->add(
            'success',
            'Votre page CMS a bien été supprimée'
        );

        return $this->redirectToRoute('store_backend_cms_list'); // redirection vers la liste des CMS
    }



    /**
     * Action d'activation d'une page CMS dans la page liste des CMS
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function activateAction(Cms $id, $action){

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        $id->setActive($action); // J'associe l'action activate à l'id de ma page CMS
        $em->persist($id); // J'enregistre l'id de la page CMS dans Doctrine
        $em->flush(); // J'envoie ma requête  à ma table cms

        // Permet d'écrire un message flash avec pour clef "info" et un message de confirmation
        $this->get('session')->getFlashBag()->add(
            'info',
            'Votre page CMS a bien été désactivée'
        );

        return $this->redirectToRoute('store_backend_cms_list'); // redirection vers la liste des pages CMS
    }

    /**
     * Action de désactivation d'une page CMS dans la page liste des CMS
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function desactivateAction(Cms $id, $action){

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        $id->setActive($action); // J'associe l'action desactivate à l'id de ma page CMS
        $em->persist($id); // J'enregistre l'id de la page CMS dans Doctrine
        $em->flush(); // J'envoie ma requête  à ma table cms

        // Permet d'écrire un message flash avec pour clef "info" et un message de confirmation
        $this->get('session')->getFlashBag()->add(
            'info',
            'Votre page CMS a bien été activée'
        );

        return $this->redirectToRoute('store_backend_cms_list'); // redirection vers la liste des pages CMS
    }


    /**
     * Action de changement d'état (lue, en cours, non lue) d'un page CMS dans la page liste des CMS
     * @param Cms $id
     * @param $action
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function stateAction(Cms $id, $action){

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        $id->setState($action); // J'associe l'action state à l'id de ma page CMS
        $em->persist($id); // J'enregistre l'id de la page CMS dans Doctrine
        $em->flush(); // J'envoie ma requête  à ma table cms

        // Permet d'écrire un message flash avec pour clef "info" et un message de confirmation
        $this->get('session')->getFlashBag()->add(
            'info',
            'Le statut de votre page CMS a bien été modifiée'
        );

        return $this->redirectToRoute('store_backend_cms_list'); // redirection vers la liste des pages CMS
    }

}