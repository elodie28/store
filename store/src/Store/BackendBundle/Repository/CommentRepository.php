<?php

namespace Store\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class CommentRepository
 * @package Store\BackendBundle\Repository
 */
class CommentRepository extends EntityRepository {


    /**
     * Count All Comments
     * SELECT COUNT(comment.id)
     * FROM `comment`
     * INNER JOIN product
     * ON comment.product_id = product.id
     * WHERE jeweler_id = 1
     * @param null $user
     * @return mixed
     */
    public function getCountByUser($user = null) {

        // Compte le nombre de commentaires pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                " SELECT COUNT(com) AS nb
                  FROM StoreBackendBundle:Comment com
                  JOIN com.product p
                  WHERE p.jeweler = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();
    }


    /**
     * SELECT content
     * FROM comment
     * INNER JOIN product
     * ON product_id = product.id
     * WHERE jeweler_id = 1
     * AND state = 2
     * ORDER BY comment.date_created DESC
     * LIMIT 5
     * @param null $user
     * @return mixed
     */
    public function getLastCommentsActByUser($user = null) {

        // Donne les 5 derniers commentaires ACTIFS pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                "SELECT com
                 FROM StoreBackendBundle:Comment com
                 JOIN com.product p
                 WHERE p.jeweler = :user
                 AND com.state = 2
                 ORDER BY com.dateCreated DESC"
            )
            ->setParameter('user', $user)
            ->setMaxResults(5);

        return $query->getResult();
    }


    /**
     * SELECT content
     * FROM comment
     * INNER JOIN product
     * ON product_id = product.id
     * WHERE jeweler_id = 1
     * AND state = 1
     * ORDER BY comment.date_created DESC
     * LIMIT 5
     * @param null $user
     * @return mixed
     */
    public function getLastCommentsValByUser($user = null) {

        // Donne les 5 derniers commentaires EN COURS DE VALIDATION pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                "SELECT com
                 FROM StoreBackendBundle:Comment com
                 JOIN com.product p
                 WHERE p.jeweler = :user
                 AND com.state = 1
                 ORDER BY com.dateCreated DESC"
            )
            ->setParameter('user', $user)
            ->setMaxResults(5);

        return $query->getResult();
    }

}