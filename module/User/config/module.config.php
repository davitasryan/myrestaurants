<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'User\Controller\Index' => 'User\Controller\IndexController',
			'User\Controller\Restaurant' => 'User\Controller\RestaurantController',
			'User\Controller\UserFavoriteRestaurant' => 'User\Controller\UserFavoriteRestaurantController',
			'User\Controller\RestaurantImages' => 'User\Controller\RestaurantImagesController',
        ),
    ),
	 
    'router' => array(
        'routes' => array(
            'user' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/user[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
			'userFavoriteRestaurant' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/userfavoriterestaurant[/:action][/:id][/:id2]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
						'id2'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\UserFavoriteRestaurant',
                        'action'     => 'index',
                    ),
                ),
            ),
			'restaurant' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/restaurant[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'User\Controller\Restaurant',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'user' => __DIR__ . '/../view',
        ),
    ),
 );