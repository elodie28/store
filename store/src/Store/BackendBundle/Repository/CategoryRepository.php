<?php

namespace Store\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class CategoryRepository
 * @package Store\BackendBundle\Repository
 */
class CategoryRepository extends EntityRepository {

    /**
     * Get all categories of an user
     * @param null $user
     * @return array
     */
    public function getCategoryByUser($user = null) { // (paramètre facultatif)

        $query =$this->getEntityManager()
            /* alias */
            /* nom du bundle : nom de l'entité  alias */
            /* alias . nom de l'attribut de l'entité du FROM = : nom d'une variable nommée */
            ->createQuery(
                " SELECT c
                  FROM StoreBackendBundle:Category c
                  WHERE c.jeweler = :user"
            )
            ->setParameter('user', $user); /* 'valeur de la variable nommée :user', $valeur du paramètre  */

        return $query->getResult();
    }

}