<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace DGIModule\View\Helper;

use Zend\Mvc\MvcEvent;
use Zend\Stdlib\RequestInterface;
use Zend\View\Helper\AbstractHelper;

class Params extends AbstractHelper
{
    protected $request;

    protected $event;

    public function __construct(RequestInterface $request, MvcEvent $event)
    {
        $this->request = $request;
        $this->event = $event;
    }

    public function fromPost($param = null, $default = null)
    {
        if ($param === null)
        {
            return $this->request->getPost($param, $default)->toArray();
        }

        return $this->request->getPost($param, $default);
    }

    public function fromRoute($param = null, $default = null)
    {
        if ($param === null)
        {
            return $this->event->getRouteMatch()->getParams();
        }

        if (!$this->event->getRouteMatch()) return null;
        
        return $this->event->getRouteMatch()->getParam($param, $default);
    }
}