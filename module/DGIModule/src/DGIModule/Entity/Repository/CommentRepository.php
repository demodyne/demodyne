<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CommentRepository extends EntityRepository
{
    
    public function getPagedComments($type, $UUID, $offset = 0, $limit = 5) {
    
        $q = $this->createQueryBuilder('c')
                    ->leftJoin($type=='proposal'?'c.prop':'c.prog', 'p')
                    ->where($type=='proposal'?'p.propUUID = :UUID':'p.progUUID = :UUID')
                    ->orderBy('c.comCreatedDate', 'desc')
                    ->setMaxResults($limit)
                    ->setFirstResult($offset)
                    ->setParameter('UUID', $UUID);

        $paginator = new Paginator( $q->getQuery() );
    
        return $paginator;
    }
    
}
