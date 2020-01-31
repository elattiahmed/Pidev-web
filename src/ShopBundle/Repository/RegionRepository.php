<?php

namespace ShopBundle\Repository;

/**
 * PanierRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RegionRepository extends \Doctrine\ORM\EntityRepository
{
    public function RegionRecherche($nom)
    {
        return $this->getEntityManager()
            ->createQuery("SELECT e FROM ShopBundle:Region e
              
              WHERE
                e.region like :nom")
            ->setParameter('nom', $nom . '%')
            ->getResult();

    }


}
