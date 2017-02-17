<?php

namespace RestaurantBundle\Repository;

/**
 * UserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository
{
    public function findPossibleReviewerList()
    {
        $qb = $this
            ->createQueryBuilder('u')
            ->select('u.email')
            ->where('u.roles LIKE :reviewer OR u.roles LIKE :chief')
            ->setParameter('reviewer', '%ROLE_REVIEWER%')
            ->setParameter('chief', '%ROLE_CHIEF%')
            ->getQuery()
        ;

        $result = $qb->getResult();

        $possibleReviewerList = array();

        foreach ($result as $user) {
            array_push($possibleReviewerList, $user['email']);
        }

        if (sizeof($possibleReviewerList) > 0)
            return $possibleReviewerList;

        return 'taverne.licorne.fringante@gmail.com';
    }

    public function findPossibleWaiterList()
    {
        $qb = $this
            ->createQueryBuilder('u')
            ->select('u.email')
            ->where('u.roles LIKE :waiter')
            ->setParameter('waiter', '%ROLE_WAITER%')
            ->getQuery()
        ;

        $result = $qb->getResult();

        $possibleWaiterList = array();

        foreach ($result as $user) {
            array_push($possibleWaiterList, $user['email']);
        }

        if (sizeof($possibleWaiterList) > 0)
            return $possibleWaiterList;

        return 'taverne.licorne.fringante@gmail.com';
    }
}
