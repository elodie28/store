<?php

namespace Store\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class SupplierRepository
 * @package Store\BackendBundle\Repository
 */
class SupplierRepository extends EntityRepository {

    /**
     * Get all suppliers of an user
     * @param null $user
     * @return array
     */
    public function getSupplierByUser($user = null) { // (paramètre facultatif)

        /**
         * Récupère les fournisseurs des produits
         * où la boutique des produits est égal à mon paramètre
         */
        $query =$this->getEntityManager()
            /* alias */
            /* nom du bundle : nom de l'entité  alias */
            /* alias . nom de l'attribut de l'entité du FROM = : nom d'une variable nommée (nom arbitraire) */
            ->createQuery(
                " SELECT s
                  FROM StoreBackendBundle:Supplier s
                  JOIN s.product p
                  WHERE p.jeweler = :user
                  GROUP BY p.jeweler"
            )
            ->setParameter('user', $user); /* 'valeur de la variable nommée :user (nom arbitraire)', $valeur du paramètre  */

        return $query->getResult();
    }


    /**
     * Count All Suppliers
     * SELECT COUNT(supplier.id)
     * FROM `supplier`
     * INNER JOIN product_supplier
     * ON supplier.id = product_supplier.supplier_id
     * INNER JOIN product
     * ON product_supplier.product_id = product.id
     * WHERE jeweler_id = 1
     * @param null $user
     * @return mixed
     */
    public function getCountByUser($user = null) {

        // Compte le nombre de fournisseurs pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                " SELECT COUNT(DISTINCT s) AS nb
                  FROM StoreBackendBundle:Supplier s
                  JOIN s.product p
                  WHERE p.jeweler = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();
    }

}