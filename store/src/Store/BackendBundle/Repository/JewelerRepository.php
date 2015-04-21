<?php

namespace Store\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\ORM\NoresultException;



/**
 * Class JewelerRepository
 * @package Store\BackendBundle\Repository
 */
class JewelerRepository extends EntityRepository implements UserProviderInterface {


    /**
     * Get all details of a Jeweler
     * @param null $user
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAllDetailsByUser($user = null) {

        // Donne tous les détails pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                "SELECT j
                 FROM StoreBackendBundle:Jeweler j
                 WHERE j = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();
    }


    /**
     * Load Active User by Username or Email
     * Méthode de chargement de l'utilisateur : par son email ou username
     * @param string $username
     * @return \Symfony\Component\Security\Core\User\UserInterface|void
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username) {

        $q = $this
            ->createQueryBuilder('j')
            ->select('j, g')
            ->join('j.groups', 'g') // j correspond à l'entité Jeweler et g correspond à l'attribut groups
            ->where('j.username = :username OR j.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery();

        /**
         * Essayer de récupérer un utilisateur avec try{} et catch{}
         */
        try {
            // La méthode Query::getSingleResult() lance une exception NoResultException
            // s'il n'y a pas d'entrée correspondante aux critères

            // Retourne qu'un seul résultat avec la méthode getSingleResult()
            $user = $q->getSingleResult();

        } catch (NoResultException $e) {
            // s'il n'y a aucun résultat, alors on ne retourne aucun utilisateur
            return null;
        }

        return $user;
    }


    /**
     * Rafraîchi l'utilisateur par son token
     * Appeler pour rafraîchir l'utilisateur en session par son token à chaque requête
     * À chaque requête, le rafraichissement de la session se fait par le token
     * @param UserInterface $user
     * @return null|object|\Symfony\Component\Security\Core\User\UserInterface
     * @throws \Symfony\Component\Security\Core\Exception\UnsupportedUserException
     */
    public function refreshUser(UserInterface $user) {

        $class = get_class($user);

        if (!$this->supportsClass($class)) { // vérifie si l'entité liée est supportée par
            throw new UnsupportedUserException(
                sprintf(
                    'Instances of "%s" are not supported.',
                    $class
                )
            );
        }

        // Utilise la méthode find() pour retrouver l'utilisateur depuis son ID
        return $this->find($user->getId());
    }


    /**
     * Get User Class for recognize Authentification Class
     * Méthode qui permet de déclarer cette classe Repository
     * comme un Provider au mécanisme de sécurité, de faire reconnaître cette classe comme EntityProvider
     * @param string $class
     * @return bool
     */
    public function supportsClass($class) {

        return $this->getEntityName() === $class || is_subclass_of($class, $this->getEntityName());
    }

}