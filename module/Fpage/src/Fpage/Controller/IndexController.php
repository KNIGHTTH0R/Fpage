<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Fpage\Controller;

use Guzzle\Http\Client;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
//use Zend\Http\Request;
//use Zend\Http\Client;

class IndexController extends AbstractActionController
{
	private function getAccessToken(){
		$config = $this->getServiceLocator()->get('Config');
		return $config['fpageConf']['appid'].'|'.$config['fpageConf']['appsecret'];
	}
    public function indexAction()
    {
    	$config = $this->getServiceLocator()->get('Config');
    	$furl = $config['fpageConf']['graphurl'].$config['fpageConf']['pageid'];
    	$clientConf =  $config['fsocket'];
    //	echo $furl.'?access_token='.$this->getAccessToken();
    	//var_dump($clientConf);die;
    // Create a client and provide a base URL
   
     $client = new Client($furl);
     $client->setDefaultOption('query', array('access_token' => $this->getAccessToken()));
    $request = $client->get();
     $response = $request->send();
     
     $user = json_decode($response->getBody());
    // print_r($user);die;
        return new ViewModel(array('user'=>$user));
    }
}
