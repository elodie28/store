<?php

// L'endroit où je déclare ma class SliderController
namespace Store\BackendBundle\Controller;


// J'inclus la class Controller de Symfony pour pouvoir hériter de cette class
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class SliderController
 * @package Store\BackendBundle\Controller
 */
class SliderController extends Controller {


    /**
     * Page liste des slides
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listAction() {

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Je récupère l'utilisateur jeweler courant connecté (à la place du 1 dans getSliderByUser(1))
        $user = $this->getUser();

        // Je récupère tous les slides du jeweler connecté
        $slides = $em->getRepository('StoreBackendBundle:Slider')->getSliderByUser($user); // NomduBundle:Nomdel'entité

        // Je retourne la vue List contenue dans le dossier Slider de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:Slider:list.html.twig', array(
            'slides' => $slides
        ));
    }

}