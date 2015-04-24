<?php

namespace Store\BackendBundle\Notification;

use Symfony\Component\HttpFoundation\Session\Session;


/**
 * Service de notification
 * Class Notification
 * @package Store\BackendBundle\Notification
 */
class Notification {


    /**
     * @var session
     */
    protected $session;



    /**
     * Constructeur qui recevra mon service session
     */
    public function __construct(Session $session) {

        $this->session = $session;

    }



    /**
     * Méthode qui va notifier une action
     * criticity : success - danger - warning - info
     */
    public function notify($message, $criticity = "success") {

        // La fonction set va me mettre en session le message avec la clef alert
        $this->session->set('alert', array(
            'message' => $message,
            'criticity' => $criticity,
        ));
    }

}