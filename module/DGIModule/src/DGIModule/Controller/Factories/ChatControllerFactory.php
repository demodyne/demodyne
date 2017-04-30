<?phpnamespace DGIModule\Controller\Factories;use DGIModule\Controller\ChatController;use Zend\ServiceManager\FactoryInterface;use Zend\ServiceManager\ServiceLocatorInterface;class ChatControllerFactory implements FactoryInterface{    /**     * @param ServiceLocatorInterface $serviceLocator     * @return ChatController     */    public function createService(ServiceLocatorInterface $serviceLocator)    {        $sl = $serviceLocator->getServicelocator();        return new ChatController(            $sl->get('Config'),            $sl->get('doctrine.entitymanager.orm_default'),            $sl->get('MvcTranslator'));    }	}