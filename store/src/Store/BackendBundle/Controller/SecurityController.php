<?php

namespace Store\BackendBundle\Controller;

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
     * Subscribe Page for Jeweler
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function subscribeAction() {

        // Je crée mon formulaire d'inscription pour un jeweler
        $form = $this->createForm(new JewelerSubscribeType());

        return $this->render('StoreBackendBundle:Security:subscribe.html.twig', array(
            'form' => $form->createView()
        ));
    }

}
