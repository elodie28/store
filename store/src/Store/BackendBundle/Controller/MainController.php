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

        // récupérer l'utilisateur courant connecté (à la place du 1 dans (1))
        $user = $this->getUser();

        // Je récupère le nombre de produits de mon bijoutier numéro 1
        // Je fais appel à mon repository ProductRepository et à la fonction getCountByUser(1)
        $nbprod = $em->getRepository('StoreBackendBundle:Product')->getCountByUser($user); // NomduBundle:Nomdel'entité

        $nbcat = $em->getRepository('StoreBackendBundle:Category')->getCountByUser($user);

        $nbpage = $em->getRepository('StoreBackendBundle:Cms')->getCountByUser($user);

        $nbcom = $em->getRepository('StoreBackendBundle:Comment')->getCountByUser($user);

        $nbsup = $em->getRepository('StoreBackendBundle:Supplier')->getCountByUser($user);

        $nborder = $em->getRepository('StoreBackendBundle:Orders')->getCountByUser($user);

        $totalorder = $em->getRepository('StoreBackendBundle:Orders')->getTotalByUser($user);

        $nbcomact = $em->getRepository('StoreBackendBundle:Comment')->getCountActByUser($user);

        $nbcomval = $em->getRepository('StoreBackendBundle:Comment')->getCountValByUser($user);

        $nbcominact = $em->getRepository('StoreBackendBundle:Comment')->getCountInactByUser($user);

        $nblikes = $em->getRepository('StoreBackendBundle:Product')->getCountLikesByUser($user);

        $lastcomact = $em->getRepository('StoreBackendBundle:Comment')->getLastCommentsActByUser($user);

        $lastcomval = $em->getRepository('StoreBackendBundle:Comment')->getLastCommentsValByUser($user);

        $lastcominact = $em->getRepository('StoreBackendBundle:Comment')->getLastCommentsInactByUser($user);

        $lastorders = $em->getRepository('StoreBackendBundle:Orders')->getLastOrdersByUser($user);

        $catpop = $em->getRepository('StoreBackendBundle:Category')->getCategoryWithProductsByUser($user);

        $lastmess = $em->getRepository('StoreBackendBundle:Message')->getLastMessByUser($user);

        $lastbusiness = $em->getRepository('StoreBackendBundle:Business')->getLastBusinessByUser($user);

        $detailsjeweler = $em->getRepository('StoreBackendBundle:Jeweler')->getAllDetailsByUser($user);

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
            'catpop' => $catpop,
            'lastmess' => $lastmess,
            'lastbusiness' => $lastbusiness,
            'detailsjeweler' => $detailsjeweler
        ));
    }
}


