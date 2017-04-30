<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Repository;

use Doctrine\ORM\EntityRepository;

class LanguageRepository extends EntityRepository
{
    public function getLanguages() {
        $q = $this->createQueryBuilder('l')
                    ->orderBy('l.langName');
        $languages = $q->getQuery()->getResult();

        return $languages;
    }

}
