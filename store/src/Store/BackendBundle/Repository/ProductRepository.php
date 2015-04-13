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

        $query = $this->getEntityManager()
            /* alias */
            /* nom du bundle : nom de l'entité  alias */
            /* alias . nom de l'attribut de l'entité du FROM = : nom d'une variable nommée (nom arbitraire) */
            ->createQuery(
            " SELECT p
              FROM StoreBackendBundle:Product p
              WHERE p.jeweler = :user"
            )
            ->setParameter('user', $user); /* 'valeur de la variable nommée :user (nom arbitraire)', $valeur du paramètre  */

        return $query->getResult();
    }


    /**
     * Count All Product
     * SELECT COUNT(id)
     * FROM `product`
     * WHERE jeweler_id = 1
     * @param null $user
     * @return mixed
     */
    public function getCountByUser($user = null) {

        // Compte le nombre de produits pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
            " SELECT count(p) AS nb
              FROM StoreBackendBundle:Product p
              WHERE p.jeweler = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();
    }


}


