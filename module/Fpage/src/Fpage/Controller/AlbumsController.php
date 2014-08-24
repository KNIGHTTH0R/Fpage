<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the cano$clnical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Fpage\Controller;


use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphObject;

class AlbumsController extends AbstractActionController
{
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
        $fields = 'description,cover_photo,link,name,photos.fields(album,height,picture,width)';
        $fieldsArr = explode(',', $fields);
        $clientConf = $config['fsocket'];

        //print_r($config);
        // Create a client and provide a base URL
        /*    $client = new Client($furl.'/albums');
           $client->setDefaultOption('query',
                   array('fields' =>

           'description,cover_photo,link,name,photos.fields(album,height,picture,width)')
           );
           // Create a request with basic Auth
           $request = $client->get();
           // Send the request and get the response
           $response = $request->send();
           $albums = json_decode($response->getBody(true)); */
        $cache = $this->getCache();
        $key = 'album' . $config['fpageConf']['pageid'];
        $success = false;
        $result = $cache->getItem($key, $success);
        //	var_dump($result);
        //TODO get rid of success
        $success = false;
        $albums = array();
        if (!$success || !$result) {
            $config = $this->getServiceLocator()->get('Config');


            $session = new FacebookSession($_SESSION['fb_token']);

            $req = new FacebookRequest($session, 'GET', '/' . $config['fpageConf']['pageid'] . '/albums', array('fields' => $fields));
            $request = $req->execute();
// Get response as an array
            $albums = $request->getResponse();

            $cache->setItem($key, serialize($albums));
            //  $this->albums = $albums->data;
        } else {
            $albums = unserialize($result);
        }


        $viewModel = new ViewModel(array(
            'pageid' => $config['fpageConf']['pageid'],
            'facebookurl' => $this->facebookUrl,
            'albums' => $albums->data
        ));
        //$viewModel->albums = $albums->data;

        return $viewModel;
    }

    private function getAccessToken()
    {
        $config = $this->getServiceLocator()->get('Config');
        return $config['fpageConf']['appid'] . '|' . $config['fpageConf']['apsecret'];
    }

    public function albumPicturesAction()
    {


        $config = $this->getServiceLocator()->get('Config');

        $this->facebookUrl = $config['fpageConf']['graphurl'];

        $fields = 'description,name,photos.limit(200).fields(images,picture,link,place,height,id,width,name),location';

        $albumKey = $this->params('id');

        $furl = $this->facebookUrl . $albumKey;

        $clientConf = $config['fsocket'];

        $cache = $this->getCache();
        //	$cache->
        $key = 'album' . $albumKey . preg_replace("/[^a-zA-Z0-9]/", '', $fields);

        $success = false;

        $result = $cache->getItem($key, $success);

        $data = unserialize($result);
        $success = false;
        if (!$success || !$data) {

            $session = new FacebookSession($_SESSION['fb_token']);

            $req = new FacebookRequest($session, 'GET', '/' . $albumKey, array('fields' => $fields));
            $request = $req->execute();
            //    print_r($response->geBody());
            if ($request) {
                $pictures = $request->getResponse();

                $cache->setItem($key, serialize($pictures));
            }
            //  $this->albums = $albums->data;
        } else {
            $pictures = $data;
        }
        //	print_r($pictures);die;
        $viewModel = new ViewModel(
            array(
                'facebookurl' => $this->facebookUrl,
                'pictures' => $pictures->photos->data,
                'albumName' => $pictures->name,
                'pageid' => $config['fpageConf']['pageid']
            )
        );
        return $viewModel;
    }

    public function pictureDetailsAction()
    {


        $config = $this->getServiceLocator()->get('Config');

        $this->facebookUrl = $config['fpageConf']['graphurl'];

        $fields = 'album,name,id,images,link,source, picture,comments.limit(100)';

        $picKey = $this->params('id');

        $furl = $this->facebookUrl . $picKey;

        //	echo $furl;
        //$clientConf =  $config['fsocket'];
        //@var $cache
        $cache = $this->getCache();

        //	$cache->
        $key = 'picture' . $picKey . preg_replace("/[^a-zA-Z0-9]/", '', $fields);
        $success = '';
        $result = $cache->getItem($key, $success);
        $success = false;
        $data = unserialize($result);
        //var_dump($data);
        //var_dump($success);
        if (!$success || !$data) {
            $session = new FacebookSession($_SESSION['fb_token']);

            $req = new FacebookRequest($session, 'GET', '/' .$picKey , array('fields' => $fields));
            $request = $req->execute();
            //    print_r($response->geBody());
            if ($request) {
                $picture = $request->getResponse();

                $cache->setItem($key, serialize($picture));
            }
            //  $this->albums = $albums->data;
        } else {
            $picture = $data;
        }

        $picName = '';
        if (!empty($picture->name)) {

            $data = explode("\n", $picture->name);
            $data[0] = "<b>" . strtoupper($data[0]) . "</b><br/>";
            $picName = implode('', $data);
        }
        $viewModel = new ViewModel(
            array(
                'albumId' => $picture->album->id,
                'albumName' => $picture->album->name,
                'facebookurl' => $this->facebookUrl,
                'picture' => $picture,
                'comments' => $picture->comments->data,
                'name' => $picName,
                'images' => $picture->images,
                'pageid' => $config['fpageConf']['pageid']
            )
        );
        return $viewModel;
    }

    public function postsAction()
    {

        //	var_dump($supportedDatatypes);die;
        $config = $this->getServiceLocator()->get('Config');
        $this->facebookUrl = $config['fpageConf']['graphurl'];
        $furl = $config['fpageConf']['graphurl'] . '/' . $config['fpageConf']['pageid'];
        $fields = 'posts.fields(id,message,picture,link,icon,shares)';
        $clientConf = $config['fsocket'];

        //print_r($config);
        // Create a client and provide a base URL
        /*    $client = new Client($furl.'/albums');
         $client->setDefaultOption('query',
                 array('fields' =>

                         'description,cover_photo,link,name,photos.fields(album,height,picture,width)')
         );
        // Create a request with basic Auth
        $request = $client->get();
        // Send the request and get the response
        $response = $request->send();
        $albums = json_decode($response->getBody(true)); */
        $cache = $this->getCache();
        $key = 'posts' . $config['fpageConf']['pageid'];
        $success = false;
        $result = $cache->getItem($key, $success);
        $data = unserialize($result);
        //	var_dump($result);
        if (!$success || !$data) {

            $request = new Request();
            $request->setUri($furl . '/posts');
            $request->getQuery()->set('fields', $fields);

            //print_r($request->getQuery()->toString());die;
            //echo $furl.'/albums';
            $client = new  Client();


            $response = $client->dispatch($request);
            //    print_r($response->geBody());
            $posts = json_decode($response->getBody());
            $cache->setItem($key, serialize($posts));
            //  $this->albums = $albums->data;
        } else {
            $posts = unserialize($result);
        }
        $viewModel = new ViewModel(array(
            'pageid' => $config['fpageConf']['pageid'],
            'facebookurl' => $this->facebookUrl,
            'posts' => $posts->data
        ));
        //$viewModel->albums = $albums->data;

        return $viewModel;
    }
}
