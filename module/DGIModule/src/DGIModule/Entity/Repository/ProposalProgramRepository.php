<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ProposalProgramRepository extends EntityRepository
{

    public function getProgramPagedProposals($program, $offset = 0, $limit = 10, $sort, $order) {
        $sorts = [];
        // variable tranformation
        switch ($sort) {
            case 'name':
                $sorts[] = 'prop.propName';
                break;
            case 'category':
                $sorts[] = 'cc.catName';
                $sorts[] = 'ps.sortPosition';
                break;
            case 'user':
                $sorts[] = 'u.usrName';
                break;
            case 'priority':
                $sorts[] = 'ps.sortPosition';
                break;
            case 'published-date':
                $sorts[] = 'prop.propPublishedDate';
                break;
        }
         
        $q = $this->createQueryBuilder('ps')
                ->leftJoin('ps.prop', 'prop')
                ->leftJoin('prop.usr', 'u')
                ->leftJoin('ps.prog', 'prog')
                ->leftJoin('prop.cat', 'c')
                ->leftJoin('c.catCat', 'cc')
                ->where('prog = :program')
                ->andWhere('prop.propDeletedDate IS NULL')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->setParameter('program', $program);
                      
        foreach ($sorts as $sort) {
            $q->addOrderBy($sort, $order);
        }
    
        $paginator = new Paginator( $q->getQuery() );
    
        return $paginator;
    }
    
    
    
}
