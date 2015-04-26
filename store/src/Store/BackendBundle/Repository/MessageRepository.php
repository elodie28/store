<?php

namespace Store\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class MessageRepository
 * @package Store\BackendBundle\Repository
 */
class MessageRepository extends EntityRepository {


    /**
     * SELECT *
     * FROM message
     * WHERE jeweler_id = 1
     * ORDER BY message.date_created DESC
     * LIMIT 5
     * @param null $user
     * @return mixed
     */
    public function getLastMessByUser($user = null) {

        // Donne les 5 derniers messages pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                "SELECT m
                 FROM StoreBackendBundle:Message m
                 WHERE m.jeweler = :user
                 ORDER BY m.dateCreated DESC"
            )
            ->setParameter('user', $user)
            ->setMaxResults(5);

        return $query->getResult();
    }



    /**
     * Count All Messages
     * SELECT COUNT(message.id)
     * FROM `message`
     * WHERE jeweler_id = 1
     * @param null $user
     * @return mixed
     */
    public function getCountByUser($user = null) {

        // Compte le nombre de messages pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                " SELECT COUNT(m) AS nb
                  FROM StoreBackendBundle:Message m
                  WHERE m.jeweler = :user"
            )
            ->setParameter('user', $user);

        // retourne 1 résultat ou null (correspond au row dans CodeIgniter)
        return $query->getOneOrNullResult();
    }



    /**
     * SELECT content
     * FROM message
     * INNER JOIN user
     * ON user_id = user.id
     * WHERE jeweler_id = 1
     * AND state = 1
     * OR state = 0
     * ORDER BY message.date_created DESC
     * @param null $user
     * @return mixed
     */
    public function getAllDetailsMessByUser($user = null) {

        // Donne tous les messages LUS et NON LUS pour 1 bijoutier
        $query = $this->getEntityManager()

            ->createQuery(
                "SELECT m
                 FROM StoreBackendBundle:Message m
                 JOIN m.user u
                 WHERE m.jeweler = :user
                 AND m.state = 1
                 OR m.state = 0
                 ORDER BY m.dateCreated DESC"
            )
            ->setParameter('user', $user);

        return $query->getResult();
    }

}