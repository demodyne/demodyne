<?phpnamespace DGIModule\Controller\Factories;use DGIModule\Controller\SessionController;use Zend\ServiceManager\FactoryInterface;use Zend\ServiceManager\ServiceLocatorInterface;class SessionControllerFactory implements FactoryInterface{    /**     * @param ServiceLocatorInterface $serviceLocator     * @return SessionController     */    public function createService(ServiceLocatorInterface $serviceLocator)    {        $sl = $serviceLocator->getServicelocator();        return new SessionController(            $sl->get('Config'),            $sl->get('doctrine.entitymanager.orm_default'),            $sl->get('MvcTranslator'));    }	}