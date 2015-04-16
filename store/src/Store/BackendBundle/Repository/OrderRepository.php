<?php

namespace Store\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class OrderRepository
 * @package Store\BackendBundle\Repository
 */
class OrderRepository extends EntityRepository {


    /**
     * Count All Orders
     * SELECT COUNT(id)
     * FROM `orders`
     * WHERE jeweler_id = 1
     * @param null $user
     * @return mixed
     */
    public function getCountByUser($user = null) {

        // Compte le nombre de commandes pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                " SELECT count(o) AS nb
                  FROM StoreBackendBundle:Orders o
                  WHERE o.jeweler = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();
    }



    /**
     * SELECT ROUND(SUM(total),2)
     * FROM `orders`
     * WHERE jeweler_id = 1
     * @param null $user
     * @return mixed
     */
    public function getTotalByUser($user = null) {

        // Fait la somme du chiffre total des commandes pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                " SELECT SUM(o.total) AS total
                  FROM StoreBackendBundle:Orders o
                  WHERE o.jeweler = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();

    }


    /**
     * @param null $user
     * @return mixed
     */
    public function getLastOrdersByUser($user = null) {

        // Donne les 5 dernières commandes pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                "SELECT o
                 FROM StoreBackendBundle:Orders o
                 WHERE o.jeweler = :user
                 ORDER BY o.id DESC"
            )
            ->setParameter('user', $user)
            ->setMaxResults(5);

        return $query->getResult();
    }

}