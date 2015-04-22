<?php

namespace Store\BackendBundle\Controller;

use Store\BackendBundle\Entity\Jeweler;
use Store\BackendBundle\Form\JewelerSubscribeType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;


/**
 * Class SecurityController
 * @package Store\BackendBundle\Controller
 */
class SecurityController extends Controller {


    /**
     * Page Login
     * @param Request $request
     * @return mixed
     */
    public function loginAction(Request $request) {

        /**
         * On interroge le mécanisme de sécurité de Symfony 2
         * qui vérifie le bon login et le bon mot de passe en sécurité
         */
        $session = $request->getSession();

        // Get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        // Je retourne la vue login de mon dossier Security
        return $this->render('StoreBackendBundle:Security:login.html.twig', array(
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error' => $error
            )
        );
    }


    /**
     * Subscribe Page for a Jeweler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function subscribeAction(Request $request) {

        $jeweler = new Jeweler();

        // Je crée mon formulaire d'inscription pour un jeweler
        $form = $this->createForm(new JewelerSubscribeType(), $jeweler, array(
            'validation_groups' => 'subscribe',
            'attr' => array(
                'method' => 'post',
                'novalidate' => 'novalidate', //(pour enlever la validation HTML5)
                'action' => $this->generateUrl('store_backend_security_subscribe') // l'URL de la route subscribe
            )
        ));

        // Je lie le formulaire à la requête
        $form->handleRequest($request);

        // Si la totalité de mon formulaire est valide
        if($form->isValid()) {

            // Je récupère la valeur de mon champ password
            $password = $form['password']->getData();

            // [ÉTAPE 1] Je récupère le service d'encodage de la sécurité de Symfony 2
            $factory = $this->get('security.encoder_factory');

            // [ÉTAPE 2] Je récupère l'encoder de mon jeweler (sha512)
            $encoder = $factory->getEncoder($jeweler); // récupère l'encoder de l'entité jeweler

            // [ÉTAPE 3] Avec l'encoder de sécurité, j'encode mon mot de passe et j'y ajoute le salt
            $password = $encoder->encodePassword($password, $jeweler->getSalt()); // récupère le mot de passe

            // [ÉTAPE 4] Je renseigne le mot de passe encodé de mon jeweler
            $jeweler->setPassword($password); // modifier le mot de passe encodé avec l'encodage

            $em = $this->getDoctrine()->getManager(); // Je récupère le manager de Doctrine

            // [ÉTAPE 5] Je récupère le rôle ROLE_JEWELER par les ROLES
            $group = $em->getRepository('StoreBackendBundle:Groups')->find(1); // find(1) correspond à la récupération du 1er groupe/rôle ROLE_JEWELER

            // J'associe mon jeweler au rôle ROLE_JEWELER
            $jeweler->addGroup($group);

            $em->persist($jeweler); // // J'enregistre mon objet jeweler dans Doctrine
            $em->flush(); // J'envoie ma requête d'insert à ma table jeweler

            // Permet d'écrire un message flash avec pour clef "success" et "info" et un message de confirmation
            $this->get('session')->getFlashBag()->add(
                'success',
                'Votre compte a bien été créé'
            );
            $this->get('session')->getFlashBag()->add(
                'info',
                'Vous pouvez maintenant vous connecter sur le back-office'
            );

            return $this->redirectToRoute('store_backend_security_login'); // redirection selon la route
        }

        return $this->render('StoreBackendBundle:Security:subscribe.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
