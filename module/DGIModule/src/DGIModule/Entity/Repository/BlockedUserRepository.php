<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use DGIModule\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\Entity;

class BlockedUserRepository extends EntityRepository
{

    /**
     * Get the count of administration banners
     * @param User $user
     * @param $uuid
     * @return Entity|null
     */
    public function getBlockedUser(User $user, $uuid)
    {
        $q = $this->createQueryBuilder('b')
                        ->where('b.usr=:user')
                        ->andWhere('b.entityUUID=:uuid')
                        ->setParameter('user', $user)
                        ->setParameter('uuid', $uuid);
        return $q->getQuery()->getOneOrNullResult();
    }
    

    
}
