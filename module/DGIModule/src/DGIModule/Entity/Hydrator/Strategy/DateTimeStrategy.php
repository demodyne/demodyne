<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2017 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\Entity\Hydrator\Strategy;

use Zend\Hydrator\Strategy\DefaultStrategy;

class DateTimeStrategy extends DefaultStrategy
{
    /**
     * {@inheritdoc}
     *
     * Convert a string value into a DateTime object
     */
    public function hydrate($value)
    {
        if (is_string($value)) {
            $value = \DateTime::createFromFormat('d/m/Y H:i', $value);
            if (!$value) $value = null;
        }
        else {
            $value = null;
        }

        return $value;
    }
}