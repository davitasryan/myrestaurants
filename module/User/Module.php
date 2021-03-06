<?php
namespace User;

 use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
 use Zend\ModuleManager\Feature\ConfigProviderInterface;
 use User\Model\User;
 use User\Model\UserTable;
 use Zend\Db\ResultSet\ResultSet;
 use Zend\Db\TableGateway\TableGateway;
 use User\Model\Restaurant;
 use User\Model\RestaurantTable;
 use User\Model\RestaurantImages;
 use User\Model\RestaurantImagesTable;
 use User\Model\UserFavoriteRestaurant;
 use User\Model\UserFavoriteRestaurantTable;
 use User\Model\UserFavoriteRestaurantMapper;


class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
             'Zend\Loader\ClassMapAutoloader' => array(
                 __DIR__ . '/autoload_classmap.php',
             ),
             'Zend\Loader\StandardAutoloader' => array(
                 'namespaces' => array(
                     __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                 ),
             ),
         );
    }
	
	function getServiceConfig()
    {
        return array(
            'factories' => array(
                'User\Model\UserTable' =>  function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('user', $dbAdapter, null, $resultSetPrototype);
                },
				'User\Model\RestaurantTable' =>  function($sm) {
                    $tableGateway = $sm->get('RestaurantTableGateway');
                    $table = new RestaurantTable($tableGateway);
                    return $table;
                },
                'RestaurantTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Restaurant());
                    return new TableGateway('restaurant', $dbAdapter, null, $resultSetPrototype);
                },
				'User\Model\UserFavoriteRestaurantTable' =>  function($sm) {
                    $tableGateway = $sm->get('UserFavoriteRestaurantTableGateway');
                    $table = new UserFavoriteRestaurantTable($tableGateway);
                    return $table;
                },
                'UserFavoriteRestaurantTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new UserFavoriteRestaurant());
                    return new TableGateway('userFavoriteRestaurant', $dbAdapter, null, $resultSetPrototype);
                },
				'User\Model\RestaurantImagesTable' =>  function($sm) {
                    $tableGateway = $sm->get('RestaurantImagesTableGateway');
                    $table = new RestaurantImagesTable($tableGateway);
                    return $table;
                },
                'RestaurantImagesTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new RestaurantImages());
                    return new TableGateway('restaurantImages', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }

}
