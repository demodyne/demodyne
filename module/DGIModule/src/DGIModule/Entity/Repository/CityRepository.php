<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CityRepository extends EntityRepository
{
    public function getCities($postalcode, $country) {
        $q = $this->createQueryBuilder('c')
                    ->where('c.cityPostalcode = :postalcode')
                    ->andWhere('c.country = :country')
                    ->orderBy('c.cityName')
                    ->setParameter('postalcode', $postalcode)
                    ->setParameter('country', $country);
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
