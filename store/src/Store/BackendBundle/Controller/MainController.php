<?php

// L'endroit où je déclare ma class MainController
namespace Store\BackendBundle\Controller;

// J'inclus la class Controller de Symfony pour pouvoir hériter de cette class
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Class MainController
 * @package Store\BackendBundle\Controller
 */
class MainController extends Controller {

    /**
     * Dashboard on Backend
     */
    public function indexAction() {

        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();

        // Je récupère le nombre de produits de mon bijoutier numéro 1
        // Je fais appel à mon repository ProductRepository et à la fonction getCountByUser(1)
        $nbprod = $em->getRepository('StoreBackendBundle:Product')->getCountByUser(1); // NomduBundle:Nomdel'entité

        $nbcat = $em->getRepository('StoreBackendBundle:Category')->getCountByUser(1);

        $nbpage = $em->getRepository('StoreBackendBundle:Cms')->getCountByUser(1);

        $nbcom = $em->getRepository('StoreBackendBundle:Comment')->getCountByUser(1);

        $nbsup = $em->getRepository('StoreBackendBundle:Supplier')->getCountByUser(1);

        $nborder = $em->getRepository('StoreBackendBundle:Orders')->getCountByUser(1);

        $totalorder = $em->getRepository('StoreBackendBundle:Orders')->getTotalByUser(1);

        $nbcomact = $em->getRepository('StoreBackendBundle:Comment')->getCountActByUser(1);

        $nbcomval = $em->getRepository('StoreBackendBundle:Comment')->getCountValByUser(1);

        $nbcominact = $em->getRepository('StoreBackendBundle:Comment')->getCountInactByUser(1);

        $nblikes = $em->getRepository('StoreBackendBundle:Product')->getCountLikesByUser(1);

        $lastcomact = $em->getRepository('StoreBackendBundle:Comment')->getLastCommentsActByUser(1);

        $lastcomval = $em->getRepository('StoreBackendBundle:Comment')->getLastCommentsValByUser(1);

        $lastcominact = $em->getRepository('StoreBackendBundle:Comment')->getLastCommentsInactByUser(1);

        $lastorders = $em->getRepository('StoreBackendBundle:Orders')->getLastOrdersByUser(1);

        $catpop = $em->getRepository('StoreBackendBundle:Category')->getCategoryWithProductsByUser(1);

        // Je retourne la vue index contenue dans le dossier Main de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:Main:index.html.twig', array(
            'nbprod' => $nbprod,
            'nbcat' => $nbcat,
            'nbpage' => $nbpage,
            'nbcom' => $nbcom,
            'nbsup' => $nbsup,
            'nborder' => $nborder,
            'totalorder' => $totalorder,
            'nbcomact' => $nbcomact,
            'nbcomval' => $nbcomval,
            'nbcominact' => $nbcominact,
            'nblikes' => $nblikes,
            'lastcomact' => $lastcomact,
            'lastcomval' => $lastcomval,
            'lastcominact' => $lastcominact,
            'lastorders' => $lastorders,
            'catpop' => $catpop
        ));
    }
}


