<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

use DGIModule\Entity\User;

class InboxRepository extends EntityRepository
{
    
    public function getUserPagedReceivedMessages(User $user, $offset = 0, $limit = 10, $filter) {
        
        $q = $this->createQueryBuilder('i')
                  ->where('i.toUsr = :user')
                  ->andWhere('i.ibxToTrashDate IS NULL')
                  ->andWhere('i.ibxToDeletedDate IS NULL')
                  ->orderBy('i.ibxId', 'desc')
                  ->setFirstResult($offset)
                  ->setParameter('user', $user);

        if ($filter>=0) {
            $q->andWhere(($filter)?'i.ibxType=:ibxType':'i.ibxType > :ibxType')->setParameter('ibxType', $filter);
        }
        else {
            $q->andWhere('i.ibxViewed=0');
        }
            
        if ($limit!='all') {
              $q->setMaxResults($limit);
        }
    
        $query = $q->getQuery();

        $paginator = new Paginator( $query );
    
        return $paginator;
    }
    
    public function getUserPagedSentMessages(User $user, $offset = 0, $limit = 10, $filter) {
    
        $q = $this->createQueryBuilder('i')
                ->where('i.fromUsr = :user')
                ->andWhere(($filter)?'i.ibxType=:ibxType':'i.ibxType > :ibxType')
                ->andWhere('i.ibxFromTrashDate IS NULL')
                ->andWhere('i.ibxFromDeletedDate IS NULL')
                ->orderBy('i.ibxId', 'desc')
                ->groupBy('i.ibxGroup')
                ->setFirstResult($offset)
                ->setParameter('user', $user)
                ->setParameter('ibxType', $filter);
    
        if ($limit!='all') {
            $q->setMaxResults($limit);
        }
    
        $query = $q->getQuery();

        $paginator = new Paginator( $query );
    
        return $paginator;
    }
    
    public function getUserPagedTrashMessages(User $user, $offset = 0, $limit = 10, $filter) {
    
        $q = $this->createQueryBuilder('i')
                    ->where('(i.fromUsr = :user AND i.ibxFromTrashDate IS NOT NULL AND i.ibxFromDeletedDate IS NULL) OR'.
                        '(i.toUsr = :user AND i.ibxToTrashDate IS NOT NULL AND i.ibxToDeletedDate IS NULL)')
                    ->andWhere(($filter)?'i.ibxType=:ibxType':'i.ibxType > :ibxType')
                    ->orderBy('i.ibxId', 'desc')
                    ->setFirstResult($offset)
                    ->setParameter('user', $user)
                    ->setParameter('ibxType', $filter);
    
        if ($limit!='all') {
            $q->setMaxResults($limit);
        }
    
        $query = $q->getQuery();

        $paginator = new Paginator( $query );
    
        return $paginator;
    }
    
    public function getUserPagedSearchMessages($user, $sk, $sr, $ss, $st, $sm, $offset = 0, $limit = 10, $filter) {
    
        $q = $this->createQueryBuilder('i')
                    ->leftJoin('i.toUsr', 't')
                    ->leftJoin('i.fromUsr', 'f')
                    ->where('(i.toUsr = :user AND i.ibxToDeletedDate IS NULL) OR (i.fromUsr = :user AND i.ibxFromDeletedDate IS NULL)') // I'm the receiver or the sender
                    ->andWhere(($filter)?'i.ibxType=:ibxType':'i.ibxType > :ibxType')
                    ->orderBy('i.ibxId', 'desc')
                    ->groupBy('i.ibxGroup')
                    ->setMaxResults($limit)
                    ->setFirstResult($offset)
                    ->setParameter('user', $user)
                    ->setParameter('ibxType', $filter);
    
        $searchArray = [];
                    
        if ($sr) {
            $searchArray[] = 'REGEXP(t.usrName, :sk) = true';
        }
        
        if ($ss) {
            $searchArray[] = 'REGEXP(f.usrName, :sk) = true';
        }
        
        if ($st) {
            $searchArray[] = 'REGEXP(i.ibxTitle, :sk) = true';
        }
        
        if ($sm) {
            $searchArray[] = 'REGEXP(i.ibxText, :sk) = true';
        }
        
        if (count($searchArray)) {
            $q->andWhere(implode(' OR ', $searchArray))
             ->setParameter('sk', $sk);
        }

        $query = $q->getQuery();

        $paginator = new Paginator( $query );
    
        return $paginator;
    }

    public function getInboxByPeriod(User $user,\DateTime $startDate, \DateTime $endDate, $offset = 0, $limit = 'all') {

        $q = $this->createQueryBuilder('i')
            ->where('i.toUsr = :user')
            ->andWhere('i.ibxToTrashDate IS NULL')
            ->andWhere('i.ibxToDeletedDate IS NULL')
            ->andWhere('i.ibxViewed=0')
            ->andWhere('i.ibxCreatedDate >= :startDate')
            ->andWhere('i.ibxCreatedDate < :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('i.ibxId', 'desc')
            ->setFirstResult($offset)
            ->setParameter('user', $user);

        if ($limit!='all') {
            $q->setMaxResults($limit);
        }

        return new Paginator($q->getQuery());
    }

    public function countInboxByPeriod(User $user,\DateTime $startDate, \DateTime $endDate) {

        $q = $this->createQueryBuilder('i')
            ->select('count(distinct i.ibxId) as total')
            ->where('i.toUsr = :user')
            ->andWhere('i.ibxToTrashDate IS NULL')
            ->andWhere('i.ibxToDeletedDate IS NULL')
            ->andWhere('i.ibxViewed=0')
            ->andWhere('i.ibxCreatedDate >= :startDate')
            ->andWhere('i.ibxCreatedDate < :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('user', $user);

        $count = $q->getQuery()->getOneOrNullResult();

        return $count?$count["total"]:0;
    }
    
}
