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
     * Requête qui me sort le nombre de commandes sur les 6 derniers mois pour 1 bijoutier
     * SELECT COUNT(id)
     * FROM `orders`
     * WHERE date
     * BETWEEN DATE_SUB(NOW(), INTERVAL 6 MONTH)
     * AND NOW()
     * GROUP BY MONTH(date)
     * DateBegin sera un Datetime
     */
    public function getOrderGraphByUser($user, $dateBegin) {

        // Compter le nombre de commandes pour un bijoutier précis et pour une année et un mois précis
        // À appeler 6 fois pour sortir le nombre de commandes sur les 6 derniers mois
        $query = $this->getEntityManager()

            ->createQuery(
                "SELECT COUNT(o) AS nb, DATE_FORMAT(:dateBegin, '%Y-%m') AS d
                 FROM StoreBackendBundle:Orders o
                 WHERE o.jeweler = :user
                 AND MONTH(o.dateCreated) = :month
                 AND YEAR(o.dateCreated) = :year"
            )

            ->setParameters(array(
                'user' =>$user,
                'dateBegin' => $dateBegin->format('Y-m-d'),
                'month' => $dateBegin->format('m'),
                'year' => $dateBegin->format('Y')
            ));

        // Retourne un seul résultat
        return $query->getSingleResult();

    }



    /**
     * $limit = null revient à aucune limite
     * @param null $user
     * @return mixed
     */
    public function getLastOrdersByUser($user = null, $limit = null) {

        // Donne les 5 dernières commandes pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                "SELECT o
                 FROM StoreBackendBundle:Orders o
                 WHERE o.jeweler = :user
                 ORDER BY o.id DESC"
            )
            ->setParameter('user', $user)
            ->setMaxResults($limit);

        return $query->getResult();
    }



    /**
     * @param null $user
     * @return array
     */
    public function getAllDetailsOrdersByUser($user = null) {

        // Donne le détail de toutes les commandes pour 1 bijoutier (page "ma boutique" onglet "commandes")
        $query = $this->getEntityManager()

            ->createQuery(
                "SELECT o
                 FROM StoreBackendBundle:Orders o
                 WHERE o.jeweler = :user"
            )
            ->setParameter('user', $user);

        return $query->getResult();
    }


}