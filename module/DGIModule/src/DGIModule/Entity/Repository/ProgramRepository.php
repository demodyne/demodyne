<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use DGIModule\Entity\Program;
use DGIModule\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Zend\Session\Container;

class ProgramRepository extends EntityRepository
{
    public function countPrograms(User $user)
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
    
    public function getPagedPrograms(User $user, $offset = 0, $limit = 10, $sort, $order) {

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


    /**
     * @param User $user
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param $levels
     * @return array
     */
    public function countUserPeriodPrograms(User $user, \DateTime $startDate, \DateTime $endDate, $levels) {

        $q = $this->createQueryBuilder('p')
                    ->leftJoin('p.city', 'city')
                    ->select('sum(CASE WHEN (p.progLevel = :cityLevel) THEN 1 ELSE 0 END) as cityPrograms')
                    ->addSelect('sum(CASE WHEN (p.progLevel = :regionLevel) THEN 1 ELSE 0 END) as regionPrograms')
                    ->addSelect('sum(CASE WHEN (p.progLevel = :countryLevel) THEN 1 ELSE 0 END) as countryPrograms')
                    ->addselect('count(distinct p.progId) as totalPrograms')
                    ->where('p.progDeletedDate IS NULL')
                    ->andWhere('p.progCreatedDate >= :startDate')
                    ->andWhere('p.progCreatedDate < :endDate')
                    ->setParameter('cityLevel', $levels['city'])
                    ->setParameter('regionLevel', $levels['region'])
                    ->setParameter('countryLevel', $levels['country'])
                    ->setParameter('startDate', $startDate)
                    ->setParameter('endDate', $endDate);


        $city = $user->getCity();
        if ($city->getFullCity()) {
            $sql[0]= '(city = :city OR city.fullCity = :fullCity OR city.fullCity = :city OR city = :fullCity)';
            $q->setParameter('city', $city)
                ->setParameter('fullCity', $city->getFullCity());
        }
        else {
            $sql[0]= 'city = :city OR city.fullCity = :city';
            $q->setParameter('city', $city);
        }
        $sql[1]= 'city.region = :region ';
        $q->setParameter('region', $user->getCity()->getRegion());
        $sql[2]= 'city.country = :country ';
        $q->setParameter('country', $user->getCountry());

        $q->andWhere(implode(' OR ', $sql));

        $proposalCount = $q->getQuery()->getOneOrNullResult();

        if ($proposalCount) return
            [
                'city' => $proposalCount["cityPrograms"],
                'region' => $proposalCount["regionPrograms"],
                'country' => $proposalCount["countryPrograms"],
                'total' => $proposalCount["totalPrograms"],
            ];
        else return [
            'city' => 0,
            'region' => 0,
            'country' => 0,
            'total' => 0,
        ];
    }

    /**
     * @return array|Program[]
     */
    public function getProgramsWithCommentsByPeriod() {

        $q = $this->createQueryBuilder('p')
            ->leftJoin('p.comments', 'c')
            ->addSelect('sum(CASE WHEN (DATEDIFF(CURRENT_TIME(),c.comCreatedDate) <= 7) THEN 1 ELSE 0 END) as weeklyComments')
            ->addSelect('sum(CASE WHEN (DATEDIFF(CURRENT_TIME(),c.comCreatedDate) <= 1) THEN 1 ELSE 0 END) as dailyComments')
            ->groupBy('p.progId')
            ->having('weeklyComments > 0');

        return $q->getQuery()->getResult();
    }


}
