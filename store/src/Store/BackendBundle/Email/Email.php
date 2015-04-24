<?php

namespace Store\BackendBundle\Email;


/**
 * Email Service Class
 * @package Store\BackendBundle\Email
 */
class Email {



    /**
     * @var \Swift_Mailer Swift Mailer
     */
    protected $mailer;

    /**
     * Twig Template Engine
     * @var \Twig_Environment
     */
    protected $twig;



    /**
     * Constructeur de ma classe Email
     * J'ai besoin du service swift mailer et du service Twig
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig) {

        $this->mailer = $mailer;
        $this->twig = $twig;

    }



    /**
     * Fonction qui envoie un e-mail à un utilisateur
     */
    public function send() {

        // Je crée un message d'e-mail
        $message = \Swift_Message::newInstance()
            ->setSubject('Test Email') // Le sujet
            ->setFrom('elodie.perrotton@gmail.com') // L'expéditeur
            ->setTo('elodie.perrotton@gmail.com') // Le destinataire
            ->setContentType('text/html') // Précise que le mail sera au format html
            ->setBody( // Le corps du mail qui sera une vue en Twig
                $this->twig->render('StoreBackendBundle:Email:email.html.twig'));

        $this->mailer->send($message); // Utilise le service mailer et envoie mon e-mail avec la méthode send()
    }

}

