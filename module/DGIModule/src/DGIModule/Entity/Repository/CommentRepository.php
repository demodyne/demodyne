<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class CommentRepository extends EntityRepository
{
    
    public function getPagedComments($type, $UUID, $offset = 0, $limit = 5) {
    
        $q = $this->createQueryBuilder('c')
                    ->orderBy('c.comCreatedDate', 'desc')
                    ->setMaxResults($limit)
                    ->setFirstResult($offset)
                    ->setParameter('UUID', $UUID);

        if ($type=='proposal') {
            $q->leftJoin('c.prop', 'p')
              ->where('p.propUUID = :UUID');
        }
        elseif ($type=='program') {
            $q->leftJoin('c.prog', 'p')
                ->where('p.progUUID = :UUID');
        }
        elseif ($type=='article') {
            $q->leftJoin('c.article', 'a')
                ->where('a.articleUUID = :UUID');
        }

        return new Paginator( $q->getQuery() );
    }


    /**
     * @param string $type
     * @param string $UUID
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @return int
     */
    public function countCommentsByPeriod($type, $UUID, \DateTime $startDate, \DateTime $endDate) {

        $q = $this->createQueryBuilder('c')
                ->select('count(distinct c.comId) as total')
                ->andWhere('c.comCreatedDate >= :startDate')
                ->andWhere('c.comCreatedDate < :endDate')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate)
                ->setParameter('UUID', $UUID);

        if ($type=='proposal') {
            $q->leftJoin('c.prop', 'p')
                ->where('p.propUUID = :UUID');
        }
        elseif ($type=='program') {
            $q->leftJoin('c.prog', 'p')
                ->where('p.progUUID = :UUID');
        }

        $count = $q->getQuery()->getOneOrNullResult();

        return $count?$count["total"]:0;
    }

    
}
