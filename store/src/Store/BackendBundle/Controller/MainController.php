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

       return $this->render('StoreBackendBundle:Main:index.html.twig');
    }
}


