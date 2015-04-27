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
     * Arguments :
     *  + $id : l'id de mon objet
     *  + $message : le message de notre notification
     *  + $nature : product | catégorie | CMS | fournisseurs
     *  + criticity : success - danger - warning - info
     * nature : 0 mon compte, 1 product, 2 categories, 3 cms, 4 fournisseurs
     */
    public function notify($id, $message,
                           $nature = 'product',
                           $criticity = "success") {

        // 1. Nous récupérons dans une variable $tabsession le tableau de notification par sa nature
        // $this->session->get('nature') permet de récupérer une information par sa clef
        // Le 2ème argument à la fonction get() permet d'initialiser un tableau vide si ma clef en session n'existe pas
        $tabsession = $this->session->get($nature, array());

        // 2. Nous stockons dans ce tableau la notification avec un message, avec une criticité et avec une date
        $tabsession[$id] = array(
            'message' => $message,
            'criticity' => $criticity,
            'date' => new \Datetime("now")
        );

        // 3. Nous modifions le tableau des notifications en session
        $this->session->set($nature, $tabsession);
    }


}