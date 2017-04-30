<?phpnamespace DGIModule\Controller\Factories;use DGIModule\Controller\CategoryController;use Zend\ServiceManager\FactoryInterface;use Zend\ServiceManager\ServiceLocatorInterface;class CategoryControllerFactory implements FactoryInterface{    /**     * @param ServiceLocatorInterface $serviceLocator     * @return CategoryController     */    public function createService(ServiceLocatorInterface $serviceLocator)    {        $sl = $serviceLocator->getServicelocator();        return new CategoryController(            $sl->get('Config'),            $sl->get('doctrine.entitymanager.orm_default'),            $sl->get('MvcTranslator'));    }	}