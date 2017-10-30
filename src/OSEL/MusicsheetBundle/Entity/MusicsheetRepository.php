<?php

namespace OSEL\MusicsheetBundle\Entity;

/**
 * MusicsheetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MusicsheetRepository extends \Doctrine\ORM\EntityRepository
{
    public function getMusicsheetsByComposer($idComposer)
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->leftJoin('a.composer', 'c')
            ->addSelect('c')
        ;

        $qb->where($qb->expr()->in('c.id', $idComposer));

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

    public function getMusicsheetsByComposerActive($idComposer)
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->leftJoin('a.composer', 'c')
            ->addSelect('c')
            ->where('c.id = :id')
            ->setParameter('id', $idComposer)
            ->andWhere('a.actif = :actif')
            ->setParameter('actif', true)
        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
}
