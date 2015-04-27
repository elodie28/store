<?php

namespace Store\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;


class BusinessRepository extends EntityRepository {

    /**
     * SELECT *
     * FROM business
     * INNER JOIN business_product
     * ON business.id = business_product.business_id
     * INNER JOIN product
     * ON product_id = product.id
     * WHERE jeweler_id = 1
     * ORDER BY business.date_created DESC
     * LIMIT 4
     * @param null $user
     * @return mixed
     */
    public function getLastBusinessByUser($user = null) {

        // Donne les 4 dernières promotions et réductions pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                "SELECT b
                 FROM StoreBackendBundle:Business b
                 JOIN b.product p
                 WHERE p.jeweler = :user
                 ORDER BY b.dateCreated DESC"
            )
            ->setParameter('user', $user)
            ->setMaxResults(5);

        return $query->getResult();
    }
}