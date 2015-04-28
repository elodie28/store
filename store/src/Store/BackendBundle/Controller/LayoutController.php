<?php

// L'endroit où je déclare ma classe LayoutController
namespace Store\BackendBundle\Controller;

// J'inclus la classe Controller de Symfony pour pouvoir hériter de cette classe
use Symfony\Bundle\FrameworkBundle\Controller\Controller;



/**
 * Class LayoutController
 * Ce contrôleur spécial contiendra mon action rendue par Twig
 * @package Store\BackendBundle\Controller
 */
class LayoutController extends Controller {



    /**
     * Me retourne la liste des mes messages dans l'onglet de la barre de navigation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function mymessagesAction() {

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Je récupère l'utilisateur courant connecté
        $user = $this->getUser();

        // Je récupère mes messages depuis ma requête
        $messages = $em->getRepository('StoreBackendBundle:Message')
            ->getLastMessByUser($user, 15);

        return $this->render('StoreBackendBundle:Partial:mymessages.html.twig', array(
            'messages' => $messages
        ));

    }


    /**
     * Me retourne la liste des mes commandes dans l'onglet de la barre de navigation
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myordersAction() {

        // Je récupère l'entité Manager
        $em = $this->getDoctrine()->getManager();

        // Je récupère l'utilisateur courant connecté
        $user = $this->getUser();

        // Je récupère mes commandes depuis ma requête
        $orders = $em->getRepository('StoreBackendBundle:Orders')
            ->getLastOrdersByUser($user, 15);

        return $this->render('StoreBackendBundle:Partial:myorders.html.twig', array(
            'orders' => $orders
        ));

    }

}