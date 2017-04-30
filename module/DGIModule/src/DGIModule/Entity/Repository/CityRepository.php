<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use DGIModule\Entity\Country;
use DGIModule\Entity\Region;
use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{

    public function getCities($postalcode, $country) {
        $q = $this->createQueryBuilder('c')
                    ->where('c.cityPostalcode = :postalcode')
                    ->andWhere('c.country = :country')
                    ->orderBy('c.cityName', 'asc')
                    ->setParameter('postalcode', $postalcode)
                    ->setParameter('country', $country);

        $cities = $q->getQuery()->getResult();

        return $cities;
    }
    
    public function searchCity($cityName, $regionName, $countryName) {
        $q = $this->createQueryBuilder('c')
                    ->leftJoin('c.country', 'country')
                    ->leftJoin('c.region', 'region')
                    ->leftJoin('c.department', 'dep')
                    ->where('c.cityName = :cityName')
                    ->andWhere('country.countryName = :countryName')
                    ->andWhere('region.regionName = :regionName OR dep.regionName=:regionName')
                    ->orderBy('c.cityName', 'asc')
                    ->setParameter('cityName', $cityName)
                    ->setParameter('regionName', $regionName)
                    ->setParameter('countryName', $countryName);
        $city = $q->setMaxResults(1)->getQuery()->getResult();

        return $city;
    }
    
    public function searchAllCities($search, Region $region, Country $country) {
        $q = $this->createQueryBuilder('c')
                    ->where('((c.cityName LIKE :searchLike AND (c.region = :region OR :region=0)) OR (c.cityPostalcode LIKE :searchLike )) AND (c.fullCity is null OR (c.fullCity is not null and c.districtCode != 0))')
                    ->andWhere('c.country = :country')
                    ->orderBy('c.cityName', 'asc')
                    ->addOrderBy('c.cityPostalcode', 'asc')
                    ->groupBy('c.cityId')
                    ->setParameter('searchLike', $search.'%')
                    ->setParameter('region', $region)
                    ->setParameter('country', $country)
                    ->setMaxResults(20);
        $cities = $q->getQuery()->getResult();

        return $cities;
    }

    public function browseCities() {
        $q = $this->createQueryBuilder('c')
                ->leftJoin('c.proposals', 'p')
                ->leftJoin('c.country', 'country')
                ->where('p.propPublished = 1')
                ->andWhere('p.propDeletedDate IS NULL')
                ->addOrderBy('country.countryName', 'ASC')
                ->addOrderBy('c.stateName', 'ASC')
                ->addOrderBy('c.cityName', 'ASC')
                ->groupBy('c');
        $cities = $q->getQuery()->getResult();

        return $cities;
    }
    
}
