<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Fpage\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;

class IndexController extends AbstractActionController
{
	
    public function indexAction()
    {
     
    	$config = $this->getServiceLocator()->get('Config');
      //  print_r($config);
        $appid = $config['fpageConf']['appid'];
        $appsecret = $config['fpageConf']['appsecret'];

      
      
        $session = new FacebookSession( $_SESSION['fb_token'] );
        $request = (new FacebookRequest( $session, 'GET','/'. $config['fpageConf']['pageid']))->execute();

// Get response as an array
        $user = $request->getResponse();

        //print_r($user);die;
    // $user = json_decode($response->getBody());
    // print_r($user);die;
        return new ViewModel(array('user'=> $user));
    }
}
