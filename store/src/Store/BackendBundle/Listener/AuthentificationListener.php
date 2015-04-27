<?php

namespace Store\BackendBundle\Listener;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Store\BackendBundle\Notification\Notification;


/**
 * Class AuthentificationListener
 * @package Store\BackendBundle\Listener
 */
class AuthentificationListener {


    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em;


    /**
     * @var \Symfony\Component\Security\Core\SecurityContextInterface
     */
    protected $securityContext;


    /**
     * @var null|Notification
     */
    protected $notification;



    /**
     * Le constructeur de ma classe avec 2 arguments : l'Entity Manager et le  Contexte de Sécurité
     * @param EntityManager $em
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(EntityManager $em, SecurityContextInterface $securityContext, Notification $notification) {

        // Je stocke dans 2 attributs les services récupérés
        $this->em = $em;
        $this->securityContext = $securityContext;
        $this->notification = $notification;
    }



    /**
     * Méthode qui est déclenchée après l'événement InteractiveLogin qui est l'action de login dans la sécurité
     * @param InteractiveLoginEvent $event
     */
    public function onAuthentificationSuccess(InteractiveLoginEvent $event) {

        $now = new \DateTime('now');

        // Récupère l'utilisateur courant connecté
        $user = $this->securityContext->getToken()->getUser();

        // Je récupère tous les produits qui ont une quantité inférieure à 5 via le repository ProductRepository
        // et via la méthode getProductsQuantityIsLower()
        $products = $this->em->getRepository('StoreBackendBundle:Product')->getProductsQuantityIsLower($user);


        // Pour chaque produit
        foreach($products as $product) {
            // Si la quantité du produit est égale à 1
            if($product->getQuantity() == 1) {
                $this->notification->notify($product->getId(),
                    "Il ne vous reste plus qu'un seul exemplaire de votre produit " . $product->getTitle() . ".",
                    'product',
                    'danger'
                );

            } else {
                $this->notification->notify($product->getId(),
                    "Attention, votre produit " . $product->getTitle() . " est bientôt épuisé.",
                    "product",
                    "warning");
            }
        }


        // Met à jour la date de connexion de l'utilisateur
        $user->setDateAuth($now);

        // Enregistre mon utilisateur avec sa date modifiée
        $this->em->persist($user);
        $this->em->flush();
    }

}