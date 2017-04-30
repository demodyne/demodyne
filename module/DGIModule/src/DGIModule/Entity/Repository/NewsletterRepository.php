<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use DGIModule\Entity\Administration;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class NewsletterRepository extends EntityRepository
{
    public function countAdminNewsletters(Administration  $admin)
    {
        $q = $this->createQueryBuilder('n')
                        ->select('count(distinct n.nlId) as total')
                        ->andWhere('n.admin = :admin')
                        ->setParameter('admin', $admin);
        return $q->getQuery()->getOneOrNullResult();
    }
    
    
    public function getAdminPagedNewsletters(Administration  $admin, $offset = 0, $limit = 10, $sort, $order) {
        $sorts = [];
        // variable tranformation
        switch ($sort) {
            case 'name':
                $sorts[] = 'n.nlName';
                break;
            case 'status':
                $sorts[] = 'n.nlIsSent';
                $sorts[] = 'n.nlName';
                break;
            case 'created_date':
                $sorts[] = 'createdDate';
                $sorts[] = 'n.nlName';
                break;
            
        }
        
        $q = $this->createQueryBuilder('n')
                ->addSelect('dateformat(n.nlCreatedDate,\'%Y/%m/%d\') AS HIDDEN createdDate')
                ->where('n.admin = :admin')
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

}
