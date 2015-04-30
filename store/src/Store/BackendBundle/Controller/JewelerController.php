<?php

// L'endroit où je déclare ma class JewelerController
namespace Store\BackendBundle\Controller;


use Store\BackendBundle\Entity\Jeweler;
use Store\BackendBundle\Form\JewelerType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


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
    public function myaccountAction(Request $request) {


        // Je récupère le manager de doctrine : le conteneur d'objets de Doctrine
        $em = $this->getDoctrine()->getManager();


        // récupérer l'utilisateur courant connecté
        $user = $this->getUser();


        // Je crée un nouvel objet entité Category
        // À chaque fois que je crée un objet d'une classe, je dois user la classe
        $jeweler = new Jeweler();



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





        // Je crée un formulaire de jeweler en l'associant avec mon jeweler
        $form = $this->createForm(new JewelerType($user), $jeweler, array(
            'attr' => array(
                'method' => 'post',
                'novalidate' => 'novalidate', //(pour virer la validation HTML5)
                'action' => $this->generateUrl('store_backend_jeweler_myaccount') // l'URL de la route new
                // action de mon formulaire pointe vers cette même action de contrôleur
            )
        ));

        // Je récupère le bouton submit du formulaire dans le fichier CategoryType et je le place dans le contrôleur pour pouvoir le personnaliser
        // J'utilise $form au lieu de $builder et j'ajoute un label pour personnaliser le texte du bouton
        // Je n'aurai pas pu le personnaliser sans le mettre dans le contrôleur car tout le formulaire est dans une vue centrale (partielle)
        $form->add('envoyer', 'submit', array(
            'label' => 'Enregistrer mes modifications',
            'attr'  => array(
                'class' => 'btn btn-primary btn-sm'
            )
        ));

        // Je fusionne ma requête avec mon formulaire
        $form->handleRequest($request);

        // Si la totalité de mon formulaire est valide
        if($form->isValid()) {

            //J'upload mon fichier en faisant appel à la méthode upload si mon formulaire est valide
            $jeweler->upload();

            $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine
            $em->persist($jeweler); // J'enregistre mon objet jeweler dans Doctrine
            $em->flush(); // J'envoie ma requête d'insert à ma table jeweler

            // Permet d'écrire un message flash avec pour clef "success" et un message de confirmation
            $this->get('session')->getFlashBag()->add(
                'success',
                'Vos informations ont bien été modifiées et enregistrées'
            );

            // Je retourne la vue myaccount contenue dans le dossier Jeweler de mon bundle StorebackendBundle
            return $this->render('StoreBackendBundle:Jeweler:myaccount.html.twig', array(

            ));
        }

        // createView() est toujours la méthode utilisée pour renvoyer la vue d'un formulaire
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
            'allmess' => $allmess,
            'form' => $form->createView()
        ));

    }

}
