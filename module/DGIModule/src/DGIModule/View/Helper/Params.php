<?php
/**
 * @link      https://github.com/demodyne/demodyne
 * @copyright Copyright (c) 2015-2016 Demodyne (https://www.demodyne.org)
 * @license   http://www.gnu.org/licenses/agpl.html GNU Affero General Public License
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
