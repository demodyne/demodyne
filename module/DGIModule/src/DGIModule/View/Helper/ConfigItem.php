<?php

namespace DGIModule\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceManager; 

/**
 * Returns total value (with tax)
 *
 */
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
        /*if(isset($config[$value])) {
            return $config[$value];
        }*/
        if (isset($returnValue)) {
            return $returnValue;
        }
        return NULL;
        // we could return a default value, or throw exception etc here
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