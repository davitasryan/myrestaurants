<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\Adapter\DbTable as DbAuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Session\Container;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
		
		$serviceManager = $e->getApplication()->getServiceManager();
        
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'boforeDispatch'
        ), 100);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array(
            $this,
            'afterDispatch'
        ), -100);
		
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
	
	function boforeDispatch(MvcEvent $event){
       
        $request = $event->getRequest();
        $response = $event->getResponse();
        $target = $event->getTarget ();
        
        /* Offline pages not needed authentication */  
        $whiteList = array (
			'Auth\Controller\Index-login',
			'User\Controller\Index-add',
		);
        
        $requestUri = $request->getRequestUri();
        $controller = $event->getRouteMatch ()->getParam ( 'controller' );
        $action = $event->getRouteMatch ()->getParam ( 'action' );
        
        $requestedResourse = $controller . "-" . $action;
        
        $session = new Container('User');
        
		
		$auth = new AuthenticationService();
		$storage = $auth->getStorage();
		$identity = (array)$auth->getIdentity();
		//var_dump( $auth->hasIdentity() , $identity["id"]);
		
        if ($auth->hasIdentity()) {
            if ($requestedResourse == 'Auth\Controller\Index-login' || in_array ( $requestedResourse, $whiteList )) {
                $url = '/user/index';
                $response->setHeaders ( $response->getHeaders ()->addHeaderLine ( 'Location', $url ) );
                $response->setStatusCode ( 302 );
            }
        }else{
            if ($requestedResourse != 'Auth\Controller\Index-login' && ! in_array ( $requestedResourse, $whiteList )) {
                 $url = '/auth/index/login';                
                $response->setHeaders ( $response->getHeaders ()->addHeaderLine ( 'Location', $url ) );
                $response->setStatusCode ( 302 ); 
            }
            $response->sendHeaders ();
        }
        
       //print "Called before any controller action called. Do any operation.";
    }
    
    function afterDispatch(MvcEvent $event){
        //print "Called after any controller action called. Do any operation.";
    }
	
	public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'AuthService' => function ($serviceManager) {
                    $adapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $dbAuthAdapter = new DbAuthAdapter ( $adapter, 'user', 'email', 'password' );
                    	
                    $auth = new AuthenticationService();
                    $auth->setAdapter ( $dbAuthAdapter );
                    return $auth;
                }
            ),
        );
    }
	
}
