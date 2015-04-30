<?php
/**
 * Created by PhpStorm.
 * User: wac24
 * Date: 30/04/15
 * Time: 14:55
 */

// L'endroit où je déclare ma class OrderController
namespace Store\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class OrderController
 * @package Store\BackendBundle\Controller
 */
class OrderController extends Controller {

    public function listAction() {

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // récupérer l'utilisateur courant connecté
        $user = $this->getUser();

        // Je récupère toutes les commandes du jeweler connecté
        $orders = $em->getRepository('StoreBackendBundle:Orders')->getAllDetailsOrdersByUser($user); // NomduBundle:Nomdel'entité

        // Je retourne la vue List contenue dans le dossier Orders de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:Orders:list.html.twig', array(
            'orders' => $orders
        ));

    }

} 