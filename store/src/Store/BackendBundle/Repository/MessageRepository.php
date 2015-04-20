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

}