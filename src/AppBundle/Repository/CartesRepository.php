<?php

namespace AppBundle\Repository;

/**
 * CartesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CartesRepository extends \Doctrine\ORM\EntityRepository
{
    public function findByCategorie($cartecategorie)
    {
        return $this->createQueryBuilder('cartes')
            ->leftJoin("modeles.id","i")
            ->where("modeles.modelesCategorie = $cartecategorie")
            ->getQuery()
            ->getSingleResult();
    }
}
