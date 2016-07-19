<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Zend\Session\Container;

class ProgramRepository extends EntityRepository
{
    public function countPrograms(\DGIModule\Entity\User $user)
    {
        $level = new Container('level');
        
        $q = $this->createQueryBuilder('p')
                ->leftJoin('p.city', 'city')
                ->leftJoin('p.programProposals', 'progprop')
                ->where('p.progDeletedDate IS NULL')
                ->having('count(progprop)>0')
                ->groupBy('p.progId')
                ->andWhere('p.progLevel = :levelValue')
                ->setParameter('levelValue', $level->levelValue);
        
        if ($level->level=='city') {
            $city = $user->getCity();
            if ($city->getFullCity()) {
                $q->andWhere('city = :city OR city.fullCity = :fullCity')
                  ->setParameter('city', $city)
                  ->setParameter('fullCity', $city->getFullCity());
            }
            else {
                $q->andWhere('p.city = :city')
                  ->setParameter('city', $city);
        
            }
        }
        if ($level->level=='region') {
            $region = $user->getCity()->getRegion();
            $q->andWhere('city.region = :region ')
            ->setParameter('region', $region);
        }
        if ($level->level=='country') {
            $country = $user->getCountry();
            $q->andWhere('city.country = :country ')
              ->setParameter('country', $country);
        }
        
        $programs = $q->getQuery()->getResult();
        
        return count($programs);
        
    }
    
    public function getUserPrograms($user) 
    {
        $q = $this->createQueryBuilder('p')
                  ->where('p.usr = :usr')
                  ->andWhere('p.progDeletedDate IS NULL')
                  ->addOrderBy('p.progLevel', 'DESC')
                  ->addOrderBy('p.progName', 'ASC')
                  ->setParameter('usr', $user);
        return $q->getQuery()->getResult();
    }
    
    public function searchProgramByName($user, $progName, $level) {
        $q = $this->createQueryBuilder('p')
                ->leftJoin('p.usr', 'u')
                ->where('p.usr != :user')
                ->andWhere('p.progName = :progName')
                ->andWhere('p.progDeletedDate IS NULL')
                ->setParameter('user', $user)
                ->setParameter('progName', $progName);
        if ($level=='city') {
            $q->andWhere('p.city = u.city');
        }
        elseif ($level=='region') {
            $q->leftJoin('p.city', 'pc')
              ->leftJoin('u.city', 'uc')
              ->andWhere('pc.region= uc.region');
        }
        elseif ($level=='country') {
            $q->leftJoin('p.city', 'pc')
              ->leftJoin('u.city', 'uc')
              ->andWhere('pc.country = uc.country');
        }
        return $q->getQuery()->getResult();
    }
    
    public function getPagedPrograms(\DGIModule\Entity\User $user, $offset = 0, $limit = 10, $sort, $order) {
    
        $level = new Container('level');
    
        $sorts = [];
        // variable tranformation
        switch ($sort) {
            case 'name':
                $sorts[] = 'p.progName';
                break;
            case 'owner':
                $sorts[] = 'u.usrName';
                break;
            case 'created_date':
                $sorts[] = 'createdDate';
                $sorts[] = 'p.progName';
                break;
            case 'saved_date':
                $sorts[] = 'savedDate';
                $sorts[] = 'p.progName';
                break;
        }
        
        $q = $this->createQueryBuilder('p')
                    ->leftJoin('p.usr', 'u')
                    ->leftJoin('p.city', 'city')
                    ->leftJoin('p.programProposals', 'progprop')
                    ->addSelect('dateformat(p.progCreatedDate,\'%Y/%m/%d\') AS HIDDEN createdDate')
                    ->addSelect('dateformat(p.progSavedDate,\'%Y/%m/%d\') AS HIDDEN savedDate')
                    ->where('p.progDeletedDate IS NULL')
                    ->having('count(progprop)>0')
                    ->groupBy('p.progId')
                    ->setFirstResult($offset)
                    ->setMaxResults($limit)
                    ->andWhere('p.progLevel = :levelValue')
                    ->setParameter('levelValue', $level->levelValue);
    
    
        
        if ($level->level=='city') {
            $city = $user->getCity();
            if ($city->getFullCity()) {
                $q->andWhere('city = :city OR city.fullCity = :fullCity')
                ->setParameter('city', $city)
                ->setParameter('fullCity', $city->getFullCity());
            }
            else {
                $q->andWhere('p.city = :city')
                ->setParameter('city', $city);
    
            }
        }
        if ($level->level=='region') {
            $region = $user->getCity()->getRegion();
            $q->andWhere('city.region = :region ')
            ->setParameter('region', $region);
        }
        if ($level->level=='country') {
            $country = $user->getCountry();
            $q->andWhere('city.country = :country ')
            ->setParameter('country', $country);
        }
    
    
        foreach ($sorts as $sort) {
            $q->addOrderBy($sort, $order);
        }
        $query = $q->getQuery();
        $paginator = new Paginator( $query );
        return $paginator;
    }

    
}
