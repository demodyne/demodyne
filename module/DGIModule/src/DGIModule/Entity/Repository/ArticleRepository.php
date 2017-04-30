<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use DGIModule\Entity\Administration;
use DGIModule\Entity\Country;
use DGIModule\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ArticleRepository extends EntityRepository
{
    public function countAdminNewsletters(Administration  $admin)
    {
        $q = $this->createQueryBuilder('n')
                        ->select('count(distinct n.nlId) as total')
                        ->andWhere('n.admin = :admin')
                        ->setParameter('admin', $admin);
        return $q->getQuery()->getOneOrNullResult();
    }
    
    
    public function getPagedArticles(Country $country, $tag = 0, $offset = 0, $limit = 10, $published = true) {

        $q = $this->createQueryBuilder('a')
                ->where('a.country = :country')
                ->setFirstResult($offset)
                ->setMaxResults($limit)
                ->setParameter('country', $country);

        if ($published) {
            $q->andWhere('a.articlePublishedDate IS NOT NULL');
        }
        else {
            $q->andWhere('a.articlePublishedDate IS NULL');
        }

        if ($tag) {
            $q->andWhere('a.articleCategory=:tag')
              ->setParameter('tag', $tag);
        }

        $query = $q->getQuery();
    
        $paginator = new Paginator( $query );
    
        return $paginator;
    }

    public function getFeaturedArticles(Country  $country) {

        $q = $this->createQueryBuilder('a')
            ->where('a.country = :country')
            ->andWhere('a.articlePublishedDate IS NOT NULL')
            ->andWhere('a.articleFeatured=1')
            ->setParameter('country', $country);

        return $q->getQuery()->getResult();
    }

    public function getPeriodArticles(User $user, \DateTime $startDate, \DateTime $endDate) {


        $q = $this->createQueryBuilder('a')
            ->where('a.articlePublishedDate IS NOT NULL')
            ->andWhere('a.articlePublishedDate >= :startDate')
            ->andWhere('a.articlePublishedDate < :endDate')
            ->andWhere('a.country=:country')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->setParameter('country', $user->getCountry())
            ->orderBy('a.articlePublishedDate', 'desc');


        return $q->getQuery()->getResult();
    }
    
}
