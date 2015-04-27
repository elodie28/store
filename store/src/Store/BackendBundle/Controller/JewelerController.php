<?php

// L'endroit où je déclare ma class JewelerController
namespace Store\BackendBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * Class JewelerController
 * @package Store\BackendBundle\Controller
 */
class JewelerController extends Controller {



    /**
     * My Account
     * Page de la boutique d'un bijoutier
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function myaccountAction() {


        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();


        // récupérer l'utilisateur courant connecté
        $user = $this->getUser();


        $detailsjeweler = $em->getRepository('StoreBackendBundle:Jeweler')->getAllDetailsByUser($user);
        $nblikes = $em->getRepository('StoreBackendBundle:Product')->getCountLikesByUser($user);
        $nbcom = $em->getRepository('StoreBackendBundle:Comment')->getCountByUser($user);
        $nborder = $em->getRepository('StoreBackendBundle:Orders')->getCountByUser($user);
        $nbmess = $em->getRepository('StoreBackendBundle:Message')->getCountByUser($user);
        $totalorder = $em->getRepository('StoreBackendBundle:Orders')->getTotalByUser($user);
        $nbprod = $em->getRepository('StoreBackendBundle:Product')->getCountByUser($user);
        $nbpage = $em->getRepository('StoreBackendBundle:Cms')->getCountByUser($user);
        $allorder = $em->getRepository('StoreBackendBundle:Orders')->getAllDetailsOrdersByUser($user);
        $allcom = $em->getRepository('StoreBackendBundle:Comment')->getAllDetailsCommentsByUser($user);
        $allmess = $em->getRepository('StoreBackendBundle:Message')->getAllDetailsMessByUser($user);


        // Je retourne la vue myaccount contenue dans le dossier Jeweler de mon bundle StorebackendBundle
        return $this->render('StoreBackendBundle:Jeweler:myaccount.html.twig', array(
            'detailsjeweler' => $detailsjeweler,
            'nblikes' => $nblikes,
            'nbcom' => $nbcom,
            'nborder' => $nborder,
            'nbmess' => $nbmess,
            'totalorder' => $totalorder,
            'nbprod' => $nbprod,
            'nbpage' => $nbpage,
            'allorder' => $allorder,
            'allcom' => $allcom,
            'allmess' => $allmess
        ));
    }

}
