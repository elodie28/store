<?php

namespace Store\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * Class JewelerRepository
 * @package Store\BackendBundle\Repository
 */
class JewelerRepository extends EntityRepository {


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


}