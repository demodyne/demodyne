<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use DGIModule\Entity\Administration;
use DGIModule\Entity\Event;
use DGIModule\Entity\Proposal;
use Doctrine\ORM\EntityRepository;

use DGIModule\Entity\User;
use Doctrine\ORM\Tools\Pagination\Paginator;
use DGIModule\Entity\Newsletter;

class UserRepository extends EntityRepository
{
    public function findProposalPartners(Proposal $proposal, $categories, $keywords) {
        $q = $this->createQueryBuilder('u')
                  ->leftJoin('u.city', 'c')
                  ->leftJoin('u.partner', 'p')
                  ->leftJoin('p.categories', 'cat')
                  ->leftJoin('p.departments', 'dep')
                  ->orderBy('p.partName')
                  //->where('c=:city')
                  ->where('dep=:dep')
                  ->andWhere('u.usrDeletedDate IS NULL')
                  ->setParameter('dep', $proposal->getCity()->getDep());

        $allCategories = [];
        if ($categories) {
            foreach ($categories as $category) {
                $allCategories[] = -$category;
                // add other categories if all categories are choosen 
                if ($category<0) {
                    $categories[] = -$category;
                }
            }
            $q->andWhere('cat IN(:categories) OR cat.catCat IN(:allCategories)')
              ->setParameter('categories', $categories)
              ->setParameter('allCategories', $allCategories);
        }
        
        if ($keywords) {
            $q->andWhere('REGEXP(p.partName, :regexp) = true OR REGEXP(p.partActivity, :regexp) = true OR REGEXP(p.partPresentation, :regexp) = true')
              ->setParameter('regexp', implode('|', $keywords));
        }
            
        $partners = $q->getQuery()->getResult();
        return $partners;
    }
    
    public function findNewsletterPartners(Newsletter $nl) {
        $q = $this->createQueryBuilder('u')
                ->leftJoin('u.partner', 'p')
                ->leftJoin('p.categories', 'cat')
                ->leftJoin('p.departments', 'dep')
                ->orderBy('p.partName')
                //->where('c=:city')
                ->where('dep=:dep')
                ->andWhere('u.usrDeleted=0')
                ->setParameter('dep', $nl->getCity()->getDep());
    
        if ($nl->getCategories()) {
            $q->andWhere('cat.catCat IN(:allCategories)')
              ->setParameter('allCategories', $nl->getCategories());
        }

        $partners = $q->getQuery()->getResult();
        return $partners;
    }
    
    public function getPagedContacts(User $user, $offset = 0, $limit = 10, $sort, $order) {
        $sorts = [];
        // variable tranformation
        switch ($sort) {
            case 'name':
                $sorts[] = 'c.usrName';
                break;
            case 'category':
                $sorts[] = 'cc.catName';
                $sorts[] = 'c.catName';
                $sorts[] = 'p.propName';
                break;
            case 'user':
                $sorts[] = 'u.usrName';
                break;
            case 'status':
                $sorts[] = 'p.propStatus';
                $sorts[] = 'p.propName';
                break;
            case 'published-date':
                $sorts[] = 'p.propPublishedDate';
                break;
            case 'vote':
                $sorts[] = 'p.propName'; //@todo
                break;
        }
         
        $q = $this->createQueryBuilder('c')
                        ->leftJoin('c.contactForUsers', 'u')
                        ->where('u = :user')
                        ->andWhere('c != :user')
                        ->andWhere('c.usrDeletedDate IS NULL')
                        ->setFirstResult($offset)
                        ->setMaxResults($limit)
                        ->setParameter('user', $user);
    
        foreach ($sorts as $sort) {
            $q->addOrderBy($sort, $order);
        }
    
        $paginator = new Paginator( $q->getQuery() );
    
        return $paginator;
    }
    
    public function getContacts($user, $contact) {
        $q = $this->createQueryBuilder('c')
            ->leftJoin('c.contactForUsers', 'u')
            ->where('u = :user')
            ->andWhere('c.usrDeletedDate IS NULL')
            ->setParameter('user', $user)
            ->andWhere('REGEXP(c.usrName, :regexp) = true')
            ->setParameter('regexp', $contact);
    
    
        $contacts = $q->getQuery()->getResult();
        return $contacts;
    }
    
    public function getEventAttendees(Event $event, $offset = 0, $limit = 10, $sort, $order) {
        $sorts = [];
        // variable tranformation
        switch ($sort) {
            case 'name':
                $sorts[] = 'u.usrName';
                break;
            case 'type':
                $sorts[] = 'isAdmin';
                $sorts[] = 'isPartner';
                $sorts[] = 'u.usrName';
                break;
            default:
                $sorts[] = 'u.usrName';
        }
    
        $q = $this->createQueryBuilder('u')
                ->leftJoin('u.attendantForEvents', 'e')
                ->addSelect('IFELSE(u.admin IS NULL, 1, 0) AS HIDDEN isAdmin')
                ->addSelect('IFELSE(u.partner IS NULL, 0, 1) AS HIDDEN isPartner')
                ->where('e = :event')
            ->andWhere('u.usrDeletedDate IS NULL')
            ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->setParameter('event', $event);
    
        foreach ($sorts as $sort) {
            $q->addOrderBy($sort, $order);
        }
    
        $paginator = new Paginator( $q->getQuery() );
    
        return $paginator;
    }
    
    public function getEventInvitations(Event $event, $offset = 0, $limit = null, $sort='name', $order='asc') {
        $sorts = [];
        // variable tranformation
        switch ($sort) {
            case 'name':
                $sorts[] = 'u.usrName';
                break;
            case 'type':
                $sorts[] = 'isAdmin';
                $sorts[] = 'isPartner';
                $sorts[] = 'u.usrName';
                break;
            default:
                $sorts[] = 'u.usrName';
        }
    
        $q = $this->createQueryBuilder('u')
                  ->leftJoin('u.invitedForEvents', 'e')
                  ->addSelect('IFELSE(u.admin IS NULL, 1, 0) AS HIDDEN isAdmin')
                  ->addSelect('IFELSE(u.partner IS NULL, 0, 1) AS HIDDEN isPartner')
                  ->where('e = :event')
            ->andWhere('u.usrDeletedDate IS NULL')
            ->setFirstResult($offset)
                  
                  ->setParameter('event', $event);
    
        if ($limit) {
            $q->setMaxResults($limit);
        }
                  
        foreach ($sorts as $sort) {
            $q->addOrderBy($sort, $order);
        }
    
        $paginator = new Paginator( $q->getQuery() );
    
        return $paginator;
    }

    /** @todo see attendees if no invitation invitations => make a union all with 2 selects
     * @param Event $event
     * @param int $offset
     * @param null $limit
     * @param string $sort
     * @param string $order
     * @return Paginator
     */
    public function getEventInvitationsAndAttendees(Event $event, $offset = 0, $limit = null, $sort='name', $order='asc') {
        $sorts = [];
        // variable tranformation
        switch ($sort) {
            case 'name':
                $sorts[] = 'u.usrName';
                break;
            case 'type':
                $sorts[] = 'isAdmin';
                $sorts[] = 'isPartner';
                $sorts[] = 'u.usrName';
                break;
            default:
                $sorts[] = 'u.usrName';
        }
    
        $q = $this->createQueryBuilder('u')
                    ->leftJoin('u.attendantForEvents', 'a')
                    ->leftJoin('u.invitedForEvents', 'i')
                    ->addSelect('IFELSE(u.admin IS NULL, 1, 0) AS HIDDEN isAdmin')
                    ->addSelect('IFELSE(u.partner IS NULL, 0, 1) AS HIDDEN isPartner')
                    ->where('i = :event OR a = :event')
            ->andWhere('u.usrDeletedDate IS NULL')
            ->groupBy('u.usrId')
                    ->setFirstResult($offset)
                    ->setParameter('event', $event);
    
        if ($limit) {
            $q->setMaxResults($limit);
        }
    
        foreach ($sorts as $sort) {
            $q->addOrderBy($sort, $order);
        }
    
        $paginator = new Paginator( $q->getQuery() );
    
        return $paginator;
    }
    
    public function findCitizens(Administration $admin, $level) {
    
        $levelValue = $admin->getAdminLevel();
    
       
        $q = $this->createQueryBuilder('u')
                ->leftJoin('u.city', 'city')
                ->where('u.usrDeleted=0')
                ->andWhere('u.usrDeletedDate IS NULL')
                ->groupBy('u.usrId');
    
        if ($levelValue==$level['city']) {
            $city = $admin->getAdminCity();
            if ($city->getFullCity()) {
                $q->andWhere('city = :city OR city = :fullCity OR (city.districtCode = :districtCode AND city.fullCity=:fullCity) OR (p.propFullCity = 1 AND city.fullCity = :fullCity)')
                ->setParameter('city', $city)
                ->setParameter('districtCode', $city->getDistrictCode())
                ->setParameter('fullCity', $city->getFullCity());
            }
            else {
                $q->andWhere('city = :city OR city.fullCity = :city')
                  ->setParameter('city', $city);
            }
        }
        if ($levelValue==$level['region']) {
            $region = $admin->getAdminRegion();
            $q->andWhere('city.region = :region ')
            ->setParameter('region', $region);
        }
        if ($levelValue==$level['country']) {
            $country = $admin->getUser()->getCountry();
            $q->andWhere('u.country = :country ')
              ->setParameter('country', $country);
        }
    
        return  $q->getQuery()->getResult();
    }
    
    public function searchUsers($searchTerm, $limit) {

        $q = $this->createQueryBuilder('u')
                ->where('u.usrDeleted=0')
                ->andWhere('u.usrDeletedDate IS NULL')
                ->andWhere('u.city is not null')
                ->andWhere('REGEXP(u.usrName, :st) = true')
                ->setParameter('st', $searchTerm)
                ->setMaxResults($limit)
                ->groupBy('u.usrId');

        return  $q->getQuery()->getResult();
    }

    public function searchUsersLevelName($searchTerm, $limit) {

        $q = $this->createQueryBuilder('u')
                 ->leftJoin('u.city', 'city')
                ->leftJoin('city.region', 'region')
                ->leftJoin('city.country', 'country')
                ->where('u.usrDeleted=0')
                ->andWhere('u.usrDeletedDate IS NULL')
                ->andWhere('u.city is not null')
                ->andWhere('REGEXP(u.usrName, :st) = true OR REGEXP(city.cityName, :st) = true OR REGEXP(region.regionName, :st) = true OR REGEXP(country.countryName, :st) = true')
                ->setParameter('st', $searchTerm)
                ->setMaxResults($limit)
                ->groupBy('u.usrId');

        return  $q->getQuery()->getResult();
    }

    public function countUsersLevel(User $user, $level, $levels)
    {
        $q = $this->createQueryBuilder('u')
                    ->select('count(distinct u.usrId) as total')
                    ->leftJoin('u.city', 'city')
                    ->where('u!=:user')
                    ->andWhere('u.usrDeletedDate IS NULL')
                    ->setParameter('user', $user);
        
        if ($level==$levels['city']) {
            $q->andWhere('u.city=:city')
                ->setParameter('city', $user->getCity());
        }
        elseif ($level==$levels['region']) {
            $q->andWhere('city.region=:region')
                ->setParameter('region', $user->getCity()->getRegion());
        }
        elseif ($level==$levels['country']) {
            $q->andWhere('u.country=:country')
                ->setParameter('country', $user->getCountry());
        }

        return $q->getQuery()->getOneOrNullResult();
    }
    
    public function getUsersLevel(User $user, $level, $levels)
    {
        $q = $this->createQueryBuilder('u')
                ->leftJoin('u.city', 'city')
                ->where('u!=:user')
                ->andWhere('u.usrDeletedDate IS NULL')
                ->setParameter('user', $user);
    
        if ($level==$levels['city']) {
            $city = $user->getCity();
            if ($city->getFullCity()) {
                $q->andWhere('city = :city OR city.fullCity = :fullCity OR city.fullCity = :city OR city = :fullCity')
                  ->setParameter('city', $city)
                  ->setParameter('fullCity', $city->getFullCity());
            }
            else {
                $q->andWhere('city = :city OR city.fullCity = :city')
                  ->setParameter('city', $city);
            }
        }
        elseif ($level==$levels['region']) {
            $q->andWhere('city.region=:region')
            ->setParameter('region', $user->getCity()->getRegion());
        }
        elseif ($level==$levels['country']) {
            $q->andWhere('u.country=:country')
            ->setParameter('country', $user->getCountry());
        }

        return $q->getQuery()->getResult();
    }

    public function getPeriodUsers(\DateTime $startDate, \DateTime $endDate, $offset = 0, $limit = 'all', $sort = 'date', $order = 'desc') {

        $sorts = [];
        // variable tranformation
        switch ($sort) {
            case 'name':
                $sorts[] = 'u.usrName';
                break;
            case 'level':
                $sorts[] = 'country.countryName';
                $sorts[] = 'region.regionName';
                $sorts[] = 'city.cityName';
                $sorts[] = 'u.usrName';
                break;
            case 'date':
                $sorts[] = 'registrationDate';
                $sorts[] = 'u.usrName';
                break;
        }
        $q = $this->createQueryBuilder('u')
                ->leftJoin('u.city', 'city')
                ->leftJoin('city.region', 'region')
                ->leftJoin('city.country', 'country')
                ->addSelect('dateformat(u.usrRegistrationDate,\'%Y/%m/%d/%H/%i/%s\') AS HIDDEN registrationDate')
                ->where('u.usrDeletedDate IS NULL')
                ->andWhere('u.usrRegistrationDate >= :startDate')
                ->andWhere('u.usrRegistrationDate < :endDate')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate)
                ->groupBy('u.usrId')
                ->setFirstResult($offset);

        if ($limit!='all') {
            $q->setMaxResults($limit);
        }

        foreach ($sorts as $sort) {
            $q->addOrderBy($sort, $order);
        }

        $query = $q->getQuery();
        $paginator = new Paginator( $query );
        return $paginator;
    }

    public function countPeriodUsers(\DateTime $startDate, \DateTime $endDate) {

        $q = $this->createQueryBuilder('u')
            ->select('count(distinct u.usrId) as total')
            ->where('u.usrDeletedDate IS NULL')
            ->andWhere('u.usrRegistrationDate >= :startDate')
            ->andWhere('u.usrRegistrationDate < :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate);

        $total = $q->getQuery()->getOneOrNullResult();
        return $total['total'];
    }

    public function getUsersWithUnreadMessagesByPeriod() {

        $q = $this->createQueryBuilder('u')
            ->leftJoin('u.receivedMessages', 'i')
            ->addSelect('sum(CASE WHEN (DATEDIFF(CURRENT_TIME(),i.ibxCreatedDate) <= 7) THEN 1 ELSE 0 END) as weeklyMessages')
            ->addSelect('sum(CASE WHEN (DATEDIFF(CURRENT_TIME(),i.ibxCreatedDate) <= 1) THEN 1 ELSE 0 END) as dailyMessages')
            ->where('i.ibxToTrashDate IS NULL')
            ->andWhere('i.ibxToDeletedDate IS NULL')
            ->andWhere('i.ibxViewed=0')
            ->andWhere('u.usrDeletedDate IS NULL')
            ->groupBy('u.usrId')
            ->having('weeklyMessages > 0');

        return $q->getQuery()->getResult();
    }
}

