<?php

namespace Store\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class ProductRepository
 * @package Store\BackendBundle\Repository
 */
class ProductRepository extends EntityRepository {

    /**
     * Get all products of an user
     * @param null $user
     */
    public function getProductByUser($user = null) { // (paramètre facultatif)

        $query =$this->getEntityManager()
            /* alias */
            /* nom du bundle : nom de l'entité  alias */
            /* alias . nom de l'attribut de l'entité du FROM = : nom d'une variable nommée */
            ->createQuery(
            " SELECT p
              FROM StoreBackendBundle:Product p
              WHERE p.jeweler = :user"
            )
            ->setParameter('user', $user); /* 'valeur de la variable nommée :user', $valeur du paramètre  */

        return $query->getResult();
    }

}


