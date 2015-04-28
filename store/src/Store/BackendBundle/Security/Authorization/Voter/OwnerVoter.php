<?php

namespace Store\BackendBundle\Security\Authorization\Voter;


use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\User\UserInterface;



class OwnerVoter implements VoterInterface {


    /**
     * Get Attribute of User
     * Cette méthode me permet de récupérer le ou les attribut(s) envoyé(s) depuis mon contrôleur
     * @param string $attribute
     * @return bool|void
     */
    public function supportsAttribute($attribute)
    {
        return true;
    }


    /**
     * Cette méthode me permet de faire des restrictions sur l'utilisation de ce voteur
     * @param string $class
     * @return bool|void
     */
    public function supportsClass($class)
    {
        return true;
    }


    /**
     * LE PLUS IMPORTANT
     * Cette méthode me permet de voter.
     * Mécanisme que l'on implémente pour voter les droits et permissions de l'utilisateur
     * @param TokenInterface $token
     * @param null|object $object
     * @param array $attributes
     * @return int|void
     */
    public function vote(TokenInterface $token, $object, array $attributes)
    {
        /**
         * VoterInterface::ACCESS_DENIED : Accès non permis (erreur 403)
         * VoterInterface::ACCESS_GRANTED : Accès autorisé
         * VoterInterface::ACCESS_ABSTAIN : S'abstenir de voter sur le mécanisme  d'autorisation
         */

        // Je récupère l'utilisateur qui est connecté
        $user = $token->getUser();

        // Si l'utilisateur n'est pas connecté
        if(!$user instanceof UserInterface) {
            return VoterInterface::ACCESS_DENIED;
        }

        // Si le jeweler id est égal à l'id de l'utilisateur
        if(method_exists($object, 'getJeweler')) {

            if($object->getJeweler()->getId() == $user->getId()) {

                return VoterInterface::ACCESS_GRANTED; // accès permis
            }
        }

        return VoterInterface::ACCESS_DENIED;
    }


}