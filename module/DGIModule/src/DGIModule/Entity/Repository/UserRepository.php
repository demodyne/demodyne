<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use DGIModule\Entity\User;
use Doctrine\ORM\Tools\Pagination\Paginator;
use DGIModule\Entity\Newsletter;

class UserRepository extends EntityRepository
{
    public function findProposalPartners($proposal, $categories, $keywords) {
        $q = $this->createQueryBuilder('u')
                  ->leftJoin('u.city', 'c')
                  ->leftJoin('u.partner', 'p')
                  ->leftJoin('p.categories', 'cat')
                  ->leftJoin('p.departments', 'dep')
                  ->orderBy('p.partName')
                  ->where('dep=:dep')
                  ->setParameter('dep', $proposal->getCity()->getDep());
                  
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
    
    public function getEventAttendees(\DGIModule\Entity\Event $event, $offset = 0, $limit = 10, $sort, $order) {
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
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->setParameter('event', $event);
    
        foreach ($sorts as $sort) {
            $q->addOrderBy($sort, $order);
        }
    
        $paginator = new Paginator( $q->getQuery() );
    
        return $paginator;
    }
    
    public function findCitizens(\DGIModule\Entity\Administration $admin, $level) {
    
        $levelValue = $admin->getAdminLevel();
       
        $q = $this->createQueryBuilder('u')
                ->leftJoin('u.city', 'city')
                ->where('u.usrDeleted=0')
                ->andWhere('p.propDeletedDate IS NULL')
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
}

