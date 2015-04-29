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

//        $query = $this->getEntityManager()
//            /* alias */
//            /* nom du bundle : nom de l'entité  alias */
//            /* alias . nom de l'attribut de l'entité du FROM = : nom d'une variable nommée (nom arbitraire) */
//            ->createQuery(
//            " SELECT p
//              FROM StoreBackendBundle:Product p
//              WHERE p.jeweler = :user"
//            )
//            ->setParameter('user', $user); /* 'valeur de la variable nommée :user (nom arbitraire)', $valeur du paramètre  */

        /**
         * J'appelle la méthode getProductByUserBuilder()
         * qui me retourne un objet QueryBuilder
         * Je le transforme ensuite en objet Query
         */
        $query = $this->getProductByUserBuilder($user)->getQuery();

        // Je retourne une requête et non pas un résultat de requête pour le tri
        return $query;
    }


    /**
     * DQL Syntax with Form
     * @param $user
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getProductByUserBuilder($user) {

        /**
         * Le formulaire SliderType attend un objet createQueryBuilder()
         * et non pas l'objet createQuery()
         */
        $queryBuilder = $this->createQueryBuilder('p') // 'p' est le paramètre directement sous forme d'alias
            ->where('p.jeweler = :user')
            ->orderBy('p.id', 'ASC')
            ->setParameter('user', $user);

        return $queryBuilder;
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
            " SELECT COUNT(p) AS nb
              FROM StoreBackendBundle:Product p
              WHERE p.jeweler = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();
    }


    /**
     * SELECT COUNT( id )
     * FROM  `likes`
     * INNER JOIN product ON product_id = product.id
     * WHERE jeweler_id =1
     * @param null $user
     * @return mixed
     */
    public function getCountLikesByUser($user = null) {

        // Compte le nombre de Likes pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
            "SELECT COUNT(p) AS nb
             FROM StoreBackendBundle:Product p
             JOIN p.user u
             WHERE p.jeweler = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();
    }



    /**
     * DASHBOARD GRAPHIQUE CIRCULAIRE N°1
     *
     * SELECT COUNT(id)
     * FROM `product`
     * WHERE summary IS NOT NULL
     * AND description IS NOT NULL
     * AND composition IS NOT NULL
     * AND jeweler_id = 1
     *
     * @param null $user
     */
    public function getCountProductCompletedByUser($user = null) {

        // Compte le nombre de produits qui a les 3 champs summary, description et composition de rempli
        // pour 1 bijoutier (graphique dashboard n°1)
        $query = $this->getEntityManager()

            ->createQuery(
                "SELECT COUNT(p) AS nb
                 FROM StoreBackendBundle:Product p
                 WHERE p.summary IS NOT NULL
                 AND p.description IS NOT NULL
                 AND p.composition IS NOT NULL
                 AND p.jeweler = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();

    }



    /**
     * DASHBOARD GRAPHIQUE CIRCULAIRE N°2
     *
     * SELECT COUNT(product_meta.id)
     * FROM `product_meta`
     * INNER JOIN product
     * ON product_id = product.id
     * WHERE meta_keyword IS NOT NULL
     * AND meta_description IS NOT NULL
     * AND meta_title IS NOT NULL
     * AND jeweler_id = 1
     *
     * @param null $user
     */
    public function getCountProductReferencedByUser($user = null) {

        // Compte le nombre de produits qui a les 3 champs meta keyword, description et title de rempli
        // pour 1 bijoutier (graphique dashboard n°2)
        $query = $this->getEntityManager()

            ->createQuery(
                "SELECT COUNT(pm) AS nb
                 FROM StoreBackendBundle:ProductMeta pm
                 JOIN pm.product p
                 WHERE pm.metaKeyword IS NOT NULL
                 AND pm.metaDescription IS NOT NULL
                 AND pm.metaTitle IS NOT NULL
                 AND p.jeweler = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();

    }



    /**
     * DASHBOARD GRAPHIQUE CIRCULAIRE N°3
     *
     * SELECT COUNT(product.id)
     * FROM `product`
     * INNER JOIN product_cms
     * ON product.id = product_cms.product_id
     * INNER JOIN cms
     * ON product_cms.cms_id = cms.id
     * WHERE cms.id = 1
     *
     * @param null $user
     */
    public function getCountProductCmsByUser($user = null) {

        // Compte le nombre de produits (bijoux) liés à une page CMS pour 1 bijoutier (graphique dashboard n°3)

        $query = $this->getEntityManager()

            ->createQuery(
                " SELECT COUNT(p) AS nb
                  FROM StoreBackendBundle:Product p
                  JOIN p.cms cms
                  WHERE cms.jeweler = :user
                  AND p.jeweler = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();
    }



    /**
     * @param null $user
     * @return array
     */
    public function getProductsQuantityIsLower($user = null) {

        // Récupère tous les produits dont la quantité est inférieure à 5 pour 1 bijoutier

        $query = $this->getEntityManager()

            ->createQuery(
                " SELECT p
                  FROM StoreBackendBundle:Product p
                  WHERE p.jeweler = :jeweler
                  AND p.quantity < 5"
            )
            ->setParameter('jeweler', $user);

        return $query->getResult();
    }


}


