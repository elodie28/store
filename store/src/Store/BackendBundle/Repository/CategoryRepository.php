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

    //  $query =$this->getEntityManager()
            /* alias */
            /* nom du bundle : nom de l'entité  alias */
            /* alias . nom de l'attribut de l'entité du FROM = : nom d'une variable nommée */
//            ->createQuery(
//                " SELECT c
//                  FROM StoreBackendBundle:Category c
//                  WHERE c.jeweler = :user"
//            )
//            ->setParameter('user', $user); /* 'valeur de la variable nommée :user', $valeur du paramètre  */

        /**
         * J'appelle la méthode getCategoryByUserBuilder()
         * qui me retourne un objet QueryBuilder
         * Je le transforme ensuite en objet Query
         */
        $query = $this->getCategoryByUserBuilder($user)->getQuery();

        return $query->getResult();
    }


    /**
     * DQL Syntax with Form
     * @param $user
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getCategoryByUserBuilder($user) {

        /**
         * Le formulaire ProductType attend un objet createQueryBuilder()
         * et non pas l'objet createQuery()
         */
        $queryBuilder = $this->createQueryBuilder('c') // 'c' est le paramètre directement sous forme d'alias
            ->where('c.jeweler = :user')
            ->orderBy('c.title', 'ASC')
            ->setParameter('user', $user);

        return $queryBuilder;
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
                " SELECT COUNT(c) AS nb
                  FROM StoreBackendBundle:Category c
                  WHERE c.jeweler = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();
    }


    /**
     * SELECT category.title, COUNT(product.id)
     * FROM `category`
     * INNER JOIN product_category
     * ON category.id = product_category.category_id
     * INNER JOIN product
     * ON product_category.product_id = product.id
     * WHERE product.jeweler_id = 1
     * GROUP BY category.id
     * @param $user
     * @return array
     */
    public function getCategoryWithProductsByUser($user = null) {

        // Affiche le nom des catégories et le nombre de produits par categorie pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                "SELECT c
                 FROM StoreBackendBundle:Category c
                 JOIN c.product p
                 WHERE p.jeweler = :user"
            )
            ->setParameter('user', $user);

        return $query->getResult();
    }

}