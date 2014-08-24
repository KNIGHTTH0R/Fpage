<?php
/**
 * Created by PhpStorm.
 * User: hidran
 * Date: 8/24/14
 * Time: 12:41 PM
 */

namespace Fpage\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Zend\Http\Client;
use Zend\Http\Request;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;

class VideosController  extends AbstractActionController{
    //TODO
    // put a cache factory

    private function getCache()
    {
        $cache = \Zend\Cache\StorageFactory::factory(
            array(
                'adapter' => array(
                    'name' => 'filesystem'
                ),
                'plugins' => array(
                    // Don't throw exceptions on cache errors
                    'exception_handler' => array(
                        'throw_exceptions' => false
                    ),
                )
            )
        );
        return $cache;

    }
    public function indexAction()
    {

        //	var_dump($supportedDatatypes);die;
        $config = $this->getServiceLocator()->get('Config');

        $this->facebookUrl = $config['fpageConf']['graphurl'];

        $furl = $config['fpageConf']['graphurl'] . '/' . $config['fpageConf']['pageid'];

        $fields = 'format, description,picture,source,embed_html,from,comments.limit(50)';
        $clientConf = $config['fsocket'];

        $cache = $this->getCache();
        $key = 'video' . $config['fpageConf']['pageid'];

        $result = $cache->getItem($key, $success);
        $success = false;
        //	var_dump($result);
        if (!$success || !$result) {

            $session = new FacebookSession($_SESSION['fb_token']);
            $req = new FacebookRequest($session, 'GET', '/' .$config['fpageConf']['pageid'].'/videos' , array('fields' => $fields));
            $request = $req->execute();
            //    print_r($response->geBody());
            if ($request) {
                $videos = $request->getResponse();

                $cache->setItem($key, serialize($videos));
            }
            //  $this->albums = $albums->data;
        } else {
            $videos = unserialize($result);
        }
        $viewModel = new ViewModel(array(
            'pageid' => $config['fpageConf']['pageid'],
            'facebookurl' => $this->facebookUrl,
            'videos' => $videos->data
        ));
        //$viewModel->albums = $albums->data;

        return $viewModel;
    }


} 