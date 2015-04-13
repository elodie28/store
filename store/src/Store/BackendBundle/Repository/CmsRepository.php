<?php

namespace Store\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class CmsRepository
 * @package Store\BackendBundle\Repository
 */
class CmsRepository extends EntityRepository {

    /**
     * Get all cms of an user
     * @param null $user
     * @return array
     */
    public function getCmsByUser($user = null) { // (paramètre facultatif)

        $query =$this->getEntityManager()
            /* alias */
            /* nom du bundle : nom de l'entité  alias */
            /* alias . nom de l'attribut de l'entité du FROM = : nom d'une variable nommée */
            ->createQuery(
                " SELECT cms
                  FROM StoreBackendBundle:Cms cms
                  WHERE cms.jeweler = :user"
            )
            ->setParameter('user', $user); /* 'valeur de la variable nommée :user', $valeur du paramètre  */

        return $query->getResult();
    }


    /**
     * Count All Cms
     * SELECT COUNT(id)
     * FROM `cms`
     * WHERE jeweler_id = 1
     * @param null $user
     * @return mixed
     */
    public function getCountByUser($user = null) {

        // Compte le nombre de pages cms pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                " SELECT count(cms) AS nb
                  FROM StoreBackendBundle:Cms cms
                  WHERE cms.jeweler = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();
    }

}