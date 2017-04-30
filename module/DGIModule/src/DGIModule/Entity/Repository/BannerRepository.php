<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use DGIModule\Entity\Administration;
use DGIModule\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class BannerRepository extends EntityRepository
{

    /**
     * Get the count of administration banners
     * @param Administration $admin
     * @return mixed
     */
    public function countBanners(Administration $admin)
    {
        $q = $this->createQueryBuilder('b')
                        ->select('count(distinct b.bannerId) as total')
                        ->where('b.admin = :admin')
                        ->setParameter('admin', $admin);
        return $q->getQuery()->getOneOrNullResult();
    }
    
    public function countInactiveBanners(Administration $admin)
    {
        $q = $this->createQueryBuilder('b')
                    ->select('count(distinct b.bannerId) as total')
                    ->where('b.admin = :admin')
                    ->andWhere('b.bannerPublished=0')
                    ->setParameter('admin', $admin);
        return $q->getQuery()->getOneOrNullResult();
    }
    
    public function countActiveBanners(Administration $admin)
    {
        $q = $this->createQueryBuilder('b')
                    ->select('count(distinct b.bannerId) as total')
                    ->where('b.admin = :admin')
                    ->andWhere('b.bannerPublished = 1')
                    ->setParameter('admin', $admin);
        return $q->getQuery()->getOneOrNullResult();
    }

    public function getAdminInactiveBanners(Administration  $admin, $offset = 0, $limit = 10, $sort, $order) {
        $sorts = [];
        // variable tranformation
        switch ($sort) {
            case 'name':
                $sorts[] = 'b.bannerName';
                break;
            default:
                $sorts[] = 'b.bannerName';
        }
        
        $q = $this->createQueryBuilder('b')
                ->where('b.admin = :admin')
                ->andWhere('b.bannerPublished=0')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->setParameter('admin', $admin);
        
        foreach ($sorts as $sort) {
            $q->addOrderBy($sort, $order);
        }
    
        $query = $q->getQuery();
    
        $paginator = new Paginator( $query );
    
        return $paginator;
    }
    
    public function getAdminActiveBanners(Administration  $admin) {
        $q = $this->createQueryBuilder('b')
                ->where('b.admin = :admin')
                ->andWhere('b.bannerPublished = 1')
                ->addOrderBy('b.bannerPosition', 'ASC')
                ->setParameter('admin', $admin);
    
    
        return $q->getQuery()->getResult();
    }
    
    public function getActiveBanners(User  $user, $level='city', $levels) {
    
    
        $q = $this->createQueryBuilder('b')
                    ->leftJoin('b.city', 'city')
                    ->where('b.bannerPublished = 1')
                    ->andWhere('b.bannerLevel = :level')
                    ->addOrderBy('b.bannerPosition', 'ASC')
                    ->setParameter('level', $levels[$level]);
        
        if ($level=='city') {
            $city = $user->getCity();
            if ($city->getFullCity()) {
                $q->andWhere('city = :city OR city = :fullCity OR (city.districtCode = :districtCode AND city.fullCity=:fullCity) OR (b.bannerFullCity = 1 AND city.fullCity = :fullCity)')
                  ->setParameter('city', $city)
                  ->setParameter('districtCode', $city->getDistrictCode())
                  ->setParameter('fullCity', $city->getFullCity());
            }
            else {
                $q->andWhere('city = :city')
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
                    
    
        return $q->getQuery()->getResult();
    }
    
    
}
