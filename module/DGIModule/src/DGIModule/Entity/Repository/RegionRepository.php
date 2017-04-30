<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class RegionRepository extends EntityRepository
{
    
    public function getAllRegions($country)
    {
        $q = $this->createQueryBuilder('r')
                    ->where('r.country=:country')
                    ->orderBy('r.regionName')
                    ->setParameter('country', $country);
        return $q->getQuery()->getResult();
    }
    
    public function getAllRegionsCountryId($countryId)
    {
        $q = $this->createQueryBuilder('r')
                    ->leftJoin('r.country', 'c')
                    ->where('c.countryId=:country')
                    ->orderBy('r.regionName')
                    ->setParameter('country', $countryId);
        return $q->getQuery()->getResult();
    }

    public function getAllRegionsCountryCode($countryCode)
    {
        $q = $this->createQueryBuilder('r')
            ->leftJoin('r.country', 'c')
            ->where('c.countryCode=:countryCode')
            ->orderBy('r.regionName')
            ->setParameter('countryCode', $countryCode);
        return $q->getQuery()->getResult();
    }
    
    
    
}
