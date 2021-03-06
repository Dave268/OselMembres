<?php

namespace OSEL\ScoreBundle\Repository;

/**
 * MusicsheetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ScoreRepository extends \Doctrine\ORM\EntityRepository
{
    public function getScoresByComposer($idComposer)
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->leftJoin('a.composer', 'c')
            ->addSelect('c')
        ;

        $qb->where($qb->expr()->in('c.id', $idComposer));
        $qb->orderBy('a.title');

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

    public function getScoresByComposerActive($idComposer)
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->leftJoin('a.composer', 'c')
            ->addSelect('c')
            ->where('c.id = :id')
            ->setParameter('id', $idComposer)
            ->andWhere('a.actif = :actif')
            ->setParameter('actif', true)
            ->orderBy('a.title');
        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
    public function findSearch($text)
    {
        $qb = $this
            ->createQueryBuilder('a')
            ->where('a.title LIKE :title')
            ->setParameter('title', "%" . $text . "%")
            ->orderBy('a.title')
            ->setFirstResult( 0 )
            ->setMaxResults( 10 );
        ;

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }

}
