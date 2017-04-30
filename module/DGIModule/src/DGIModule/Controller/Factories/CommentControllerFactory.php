<?phpnamespace DGIModule\Controller\Factories;use DGIModule\Controller\CommentController;use Zend\ServiceManager\FactoryInterface;use Zend\ServiceManager\ServiceLocatorInterface;class CommentControllerFactory implements FactoryInterface{    /**     * @param ServiceLocatorInterface $serviceLocator     * @return CommentController     */    public function createService(ServiceLocatorInterface $serviceLocator)    {        $sl = $serviceLocator->getServicelocator();        return new CommentController(            $sl->get('Config'),            $sl->get('doctrine.entitymanager.orm_default'),            $sl->get('MvcTranslator'));    }	}