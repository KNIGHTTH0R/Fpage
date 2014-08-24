<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => array(
           
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'fpage' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/Fpage',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Fpage\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/Fpage/[:controller][/:action]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                        '__NAMESPACE__' => 'Fpage\Controller',
                        'controller'    => 'Albums',
                        'action'        => 'index',
                    ),
                        ),
                    ),
                ),
            ),
            'albums' => array(
            		'type'    => 'Literal',
            		'options' => array(
            				'route'    => '/Fpage/Albums',
            				'defaults' => array(
            						'__NAMESPACE__' => 'Fpage\Controller',
            						'controller'    => 'Albums',
            						'action'        => 'index',
            				),
            		),
            		'may_terminate' => true,
            		'child_routes' => array(
            				'default' => array(
            						'type'    => 'Segment',
            						'options' => array(
            								'route'    => '/[:action]/[:id]',
            								'constraints' => array(
            										'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            										'id'     => '[0-9_]*',
            								),
            								'defaults' => array(
            										'__NAMESPACE__' => 'Fpage\Controller',
            										'controller'    => 'Albums',
            										'action'        => 'index',
            								),
            						),
            				),
            		),
            ),
            
            'posts' => array(
            		'type'    => 'Literal',
            		'options' => array(
            				'route'    => '/Fpage/Posts',
            				'defaults' => array(
            						'__NAMESPACE__' => 'Fpage\Controller',
            						'controller'    => 'Posts',
            						'action'        => 'index',
            				),
            		),
            		'may_terminate' => true,
            		'child_routes' => array(
            				'default' => array(
            						'type'    => 'Segment',
            						'options' => array(
            								'route'    => '/[:action]/[:id]',
            								'constraints' => array(
            										'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            										'id'     => '[0-9]*',
            								),
            								'defaults' => array(
            										'__NAMESPACE__' => 'Fpage\Controller',
            										'controller'    => 'Posts',
            										'action'        => 'index',
            								),
            						),
            				),
            		),
            ),
            'videos' => array(
            		'type'    => 'Literal',
            		'options' => array(
            				'route'    => '/Fpage/Videos',
            				'defaults' => array(
            						'__NAMESPACE__' => 'Fpage\Controller',
            						'controller'    => 'Videos',
            						'action'        => 'index',
            				),
            		),
            		'may_terminate' => true,
            		'child_routes' => array(
            				'default' => array(
            						'type'    => 'Segment',
            						'options' => array(
            								'route'    => '/[:action]/[:id]',
            								'constraints' => array(
            										'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
            										'id'     => '[0-9]*',
            								),
            								'defaults' => array(
            										'__NAMESPACE__' => 'Fpage\Controller',
            										'controller'    => 'Videos',
            										'action'        => 'index',
            								),
            						),
            				),
            		),
            ),
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Fpage\Controller\Index' => 'Fpage\Controller\IndexController',
            'Fpage\Controller\Albums' => 'Fpage\Controller\AlbumsController',
            'Fpage\Controller\Posts' => 'Fpage\Controller\PostsController',
            'Fpage\Controller\Videos' => 'Fpage\Controller\VideosController'
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'fpage/index/index' => __DIR__ . '/../view/fpage/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
);
