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

        return $query->getResult();
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
            ->orderBy('p.title', 'ASC')
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
     * SELECT COUNT(product.id)
     * FROM `product`
     * INNER JOIN product_cms
     * ON product.id = product_cms.product_id
     * INNER JOIN cms
     * ON product_cms.cms_id = cms.id
     * WHERE cms.id = 1
     * @param null $user
     */
    public function getCountProductCmsByUser($user = null) {

        // Compte le nombre de produits (bijoux) liés à une page CMS pour 1 bijoutier (graphique dashboard)
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


}


