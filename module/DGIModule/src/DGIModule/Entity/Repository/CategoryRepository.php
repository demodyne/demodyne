<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class CategoryRepository extends EntityRepository
{
    public function getAllCategories()
    {
        $q = $this->createQueryBuilder('c')
                    ->where('c.catId != 0')
                    ->orderBy('c.catCat')
                    ->addOrderBy('c.catName');
        return $q->getQuery()->getResult();
    }
    
    public function getMainCategories($user, $level='city')
    {
        $q = $this->createQueryBuilder('c')
                    ->where('c.catCat IS NULL')
                    ->andWhere('c.country=:country')
                    ->andWhere('c.cat'.ucfirst($level).'=1')
                    ->addOrderBy('c.catName')
                    ->setParameter('country', $user->getCountry());
        return $q->getQuery()->getResult();
    }
    
    public function getMainCategoriesCountry($country)
    {
        $q = $this->createQueryBuilder('c')
                    ->where('c.catCat IS NULL')
                    ->andWhere('c.country=:country')
                    ->addOrderBy('c.catName')
                    ->setParameter('country', $country);
        return $q->getQuery()->getResult();
    }
    
    public function getSubCategories($mainCategoryId, $level='City')
    {
        $mainCategory = $this->findOneBy(array('catId' => $mainCategoryId));
        $q = $this->createQueryBuilder('c')
                ->where('c.catCat = :mainCategory')
                ->andWhere('c.cat'.ucfirst($level).'=1')
                ->addOrderBy('c.catName')
                ->setParameter('mainCategory', $mainCategoryId);
        return $q->getQuery()->getResult();
    }
    
    public function getSubCategoriesArray($mainCategoryId)
    {
        $mainCategory = $this->findOneBy(array('catId' => $mainCategoryId));
        $q = $this->createQueryBuilder('c')
            ->where('c.catCat = :mainCategory')
            ->addOrderBy('c.catName')
            ->setParameter('mainCategory', $mainCategoryId);
        $categories = $q->getQuery()->getResult();
        $catList = array();
        
        foreach ($categories as $category) {
            $catList["name"][] = $category->getCatName();
            $catList["id"][] = $category->getCatId();
        
        }
        
        return $catList;
    }
    
    
    
    
}
