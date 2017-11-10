<?php

namespace OSEL\ScoreBundle\Entity;

/**
 * ComposerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ComposerRepository extends \Doctrine\ORM\EntityRepository
{
    public function findActive()
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->leftJoin('a.musicsheets', 'c')
            ->addSelect('c')
            ->where('c.actif = :actif')
            ->setParameter('actif', true)
            ->orderBy('a.composer')
        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

    public function myFindAll()
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->orderBy('a.composer')
        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
}