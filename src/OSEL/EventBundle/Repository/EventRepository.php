<?php

namespace OSEL\EventBundle\Repository;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * EventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EventRepository extends \Doctrine\ORM\EntityRepository
{
    public function getPlaces($page, $nbPerPage, $criteria, $desc)
    {
        $qb = $this->createQueryBuilder('a');

        if($desc)
        {
            $qb->orderBy('a.'.$criteria, 'DESC')
            ;
        }
        else
        {
            $qb->orderBy('a.'.$criteria);
        }


        $qb->setFirstResult(($page-1) * $nbPerPage)
            ->setMaxResults($nbPerPage)
        ;

        return new Paginator($qb, true);

    }
}
