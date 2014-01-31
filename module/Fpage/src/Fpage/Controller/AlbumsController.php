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

class AlbumsController extends AbstractActionController
{
    public function indexAction()
    {
    	$config = $this->getServiceLocator()->get('Config');
    	$furl = $config['fpageConf']['graphurl'].'/'.$config['fpageConf']['pageid'];
    	
    	//print_r($config);
    // Create a client and provide a base URL
    $client = new Client($furl.'/albums');
    $client->setDefaultOption('query', 
    		array('fields' => 
    				
    'description,cover_photo,link,name,photos.fields(album,height,picture,width)')
    );
    // Create a request with basic Auth
    $request = $client->get();
    // Send the request and get the response
    $response = $request->send();
    $albums = json_decode($response->getBody(true));
    	
        return new ViewModel(array('albums'=>$albums->data));
    }
}
