<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use DGIModule\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class NewsRepository extends EntityRepository
{
    /**
     * Count the published Proposals for the $city
     * @param User $user
     * @param string $level
     * @param $levels
     * @return mixed
     * @internal param \DGIModule\Entity\City $city
     */
    public function countNewNews(User $user, $level='city', $levels)
    {
        $q = $this->createQueryBuilder('n')
                    ->leftJoin('n.city', 'city')
                    ->select('count(distinct n.newsId) as newNews')
                    ->where('n.newsCreatedDate>=:date')
                    ->setParameter('date', $user->getUsrLastLoginDate())
                    ->andWhere('n.newsLevel = :level')
                    ->setParameter('level', $levels[$level]);
        if ($user && $user->getCity()) {
            if ($level=='city') {
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
            if ($level=='region') {
                $region = $user->getCity()->getRegion();
                $q->andWhere('city.region = :region ')
                ->setParameter('region', $region);
            }
            if ($level=='country') {
                $country = $user->getCountry();
                $q->andWhere('city.country = :country ')
                ->setParameter('country', $country);
            }
        }
        return $q->getQuery()->getOneOrNullResult();
    }
    
    public function getPagedNews(User $user, $level='city', $levels, $offset = 0, $limit = 10, $filter) {

        $q = $this->createQueryBuilder('n')
                  ->leftJoin('n.city', 'city')
                  ->where(($filter)?($filter==5?'n.newsType = :newsType OR n.newsType = 6 OR n.newsType = 7':'n.newsType=:newsType'):'n.newsType > :newsType')
                  ->orderBy('n.newsCreatedDate', 'desc')
                  ->setFirstResult($offset)
                  ->setParameter('newsType', $filter)
                  ->andWhere('n.newsLevel = :level')
                  ->setParameter('level', $levels[$level]);

          if ($level=='city') {
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
          if ($level=='region') {
              $region = $user->getCity()->getRegion();
              $q->andWhere('city.region = :region ')
              ->setParameter('region', $region);
          }
          if ($level=='country') {
              $country = $user->getCountry();
              $q->andWhere('city.country = :country ')
              ->setParameter('country', $country);
          }

          if ($filter==1) {
              $q
                  ->andWhere('p.propDeletedDate IS NULL');
          }
                  
          if ($limit!='all') {
              $q->setMaxResults($limit);
          }
                  
        $query = $q->getQuery();
    
        $paginator = new Paginator( $query );
    
        return $paginator;
    }
    
    public function getPartnerPagedNews($user, $offset = 0, $limit = 10, $sort, $order, $filter) {
        
        $q = $this->createQueryBuilder('n')
                    ->leftJoin('n.city', 'c')
                    ->leftJoin('n.prop', 'p')
                    ->where(($filter)?'n.newsType=:newsType':'n.newsType > :newsType')
                    ->andWhere('c.dep IN(:partnerDepartments)')
                    ->andWhere('p.cat  IN(:partnerCategories)')
                    ->orderBy('n.newsCreatedDate', 'desc')
                    ->setFirstResult($offset)
                    ->setMaxResults($limit)
                    ->setParameter('newsType', $filter)
                    ->setParameter('partnerDepartments', array_values($user->getPartner()->getDepartments()->toArray()))
                    ->setParameter('partnerCategories', array_values($user->getPartner()->getCategories()->toArray()));
    
        $paginator = new Paginator($q->getQuery());
    
        return $paginator;
    }
    
    public function getAdministrationPagedNews($admin, $offset = 0, $limit = 10, $sort, $order, $filter) {
    
        $q = $this->createQueryBuilder('n')
                ->leftJoin('n.city', 'c')
                ->leftJoin('n.prop', 'p')
                ->where(($filter)?'n.newsType=:newsType':'n.newsType > :newsType')
                ->orderBy('n.newsCreatedDate', 'desc')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->setParameter('newsType', $filter);
    
        if ($admin->getAdminLevel() == 3) { // if city level
            $q->andWhere('n.city = :city')->setParameter('city', $admin->getAdminCity());
        }
        
        // TODO: add region and country level
    
        $query = $q->getQuery();
    
        $paginator = new Paginator( $query );
    
        return $paginator;
    }

  
}
