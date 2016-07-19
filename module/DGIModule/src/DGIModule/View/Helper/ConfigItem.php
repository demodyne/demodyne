<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
 */

namespace DGIModule\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceManager; 

class ConfigItem extends AbstractHelper
{
    /**
     * Service Locator
     * @var ServiceManager
     */
    protected $serviceLocator;

    /**
     * __invoke
     *
     * @access public
     * @param  string
     * @return String
     */
    public function __invoke($value)
    {
        $config = $this->serviceLocator->get('config');
        $keys = explode('.', $value);
        foreach ($keys as $key) {
            if(isset($config[$key])) {
                $config = $config[$key];
                $returnValue = $config;
            }
        }
        if (isset($returnValue)) {
            return $returnValue;
        }
        return NULL;
    }

    /**
     * Setter for $serviceLocator
     * @param ServiceManager $serviceLocator
     */
    public function setServiceLocator(ServiceManager $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
    }
}
