<?php

namespace Store\BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;


/**
 * Class SliderRepository
 * @package Store\BackendBundle\Repository
 */
class SliderRepository extends EntityRepository {


    /**
     * Get all slides of an user
     *
     * SELECT *
     * FROM `slider`
     * INNER JOIN product
     * ON slider.product_id = product.id
     * INNER JOIN jeweler
     * ON product.jeweler_id = jeweler.id
     * WHERE jeweler.id = 1
     *
     * @param null $user
     * @return array
     */
    public function getSliderByUser($user = null) { // (paramètre facultatif)

        $query = $this->getEntityManager()
            /* alias */
            /* nom du bundle : nom de l'entité  alias */
            /* alias . nom de l'attribut de l'entité du FROM = : nom d'une variable nommée (nom arbitraire) */
            ->createQuery(
                " SELECT sl
                  FROM StoreBackendBundle:Slider sl
                  JOIN sl.product p
                  WHERE p.jeweler = :user"
            )
            ->setParameter('user', $user); /* 'valeur de la variable nommée :user (nom arbitraire)', $valeur du paramètre  */

        return $query->getResult();
    }

}