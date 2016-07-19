<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CountryRepository extends EntityRepository
{
    public function getAllCountries()
    {
        $q = $this->createQueryBuilder('c')
                    ->where('c.countryId != 0')
                    ->andWhere('c.countryActivated=1')
                    ->orderBy('c.countryName');
        return $q->getQuery()->getResult();
    }
    
}
