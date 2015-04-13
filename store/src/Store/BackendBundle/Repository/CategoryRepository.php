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


    /**
     * Count All Categories
     * SELECT COUNT(id)
     * FROM `category`
     * WHERE jeweler_id = 1
     * @param null $user
     * @return mixed
     */
    public function getCountByUser($user = null) {

        // Compte le nombre de catégories pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                " SELECT count(c) AS nb
                  FROM StoreBackendBundle:Category c
                  WHERE c.jeweler = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();
    }


    /**
     * Affiche les catégories avec le nombre de produits du jeweler
     * @param $user
     * @return array
     */
    public function getCategoryWithProductsByUser($user) {

        $query=$this->getEntityManager()

            ->createQuery(
                "SELECT c
                 FROM StoreBackendBundle:Category c
                 JOIN c.product p
                 WHERE p.jeweler = :user
                 GROUP BY p.jeweler"
            )
            ->setParameter('user', $user);

        return $query->getResult();

    }

}