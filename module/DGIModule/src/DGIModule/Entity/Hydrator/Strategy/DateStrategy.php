<?php 
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Hydrator\Strategy;

use DateTime;
use Zend\Stdlib\Hydrator\Strategy\DefaultStrategy;

class DateStrategy extends DefaultStrategy
{
    /**
     * {@inheritdoc}
     *
     * Convert a string value into a DateTime object
     */
    public function hydrate($value)
    {
        if (is_string($value)) {
            $value = \DateTime::createFromFormat('d/m/Y', $value);
            if (!$value) $value = null;
        }
        else {
            $value = null;
        }

        return $value;
    }
}