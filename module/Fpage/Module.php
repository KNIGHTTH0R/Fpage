<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Fpage;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;

class Module
{
	
    public function onRender(MvcEvent $e)
    {
    	$sm = $e->getController();

        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);


    }
public function onBootstrap(MvcEvent $e){
    $config =  $e->getApplication()->getServiceManager()->get('Config');
    //  print_r($config);
    $appid = $config['fpageConf']['appid'];
    $appsecret = $config['fpageConf']['appsecret'];
   // DIE($appid);
    FacebookSession::setDefaultApplication( $appid, $appsecret );

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
}
