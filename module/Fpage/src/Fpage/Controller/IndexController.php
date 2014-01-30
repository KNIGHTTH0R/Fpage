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

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
    	
    // Create a client and provide a base URL
    $client = new Client('https://graph.facebook.com/270939905654');
    // Create a request with basic Auth
    $request = $client->get();
    // Send the request and get the response
    $response = $request->send();
    $user = json_decode($response->getBody(true));
    	
        return new ViewModel(array('user'=>$user));
    }
}
