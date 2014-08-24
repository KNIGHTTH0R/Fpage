<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequestException;
use Facebook\FacebookSession;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $config = $this->getServiceLocator()->get('Config');
        $appid = $config['fpageConf']['appid'];
        $appsecret = $config['fpageConf']['appsecret'];

        FacebookSession::setDefaultApplication( $appid, $appsecret );

        $redirectUrl='http://fpage.hidrasoft.com/';
        $redirectUrl2 = 'http://www.hidrasoft.com/demos/Fpage/public/Albums';
        $helper = new FacebookRedirectLoginHelper($redirectUrl, $appid, $appsecret);
        if ( isset( $_SESSION ) && isset( $_SESSION['fb_token'] ) ) {

// Create new session from saved access_token
            $session = new FacebookSession( $_SESSION['fb_token'] );

// Validate the access_token to make sure it's still valid
            try {
                if ( ! $session->validate() ) {
                    $session = null;
                }
            } catch ( \Exception $e ) {
// Catch any exceptions
                $session = null;
            }
        } else {

// No session exists
            try {
                $session = $helper->getSessionFromRedirect();
            } catch( FacebookRequestException $ex ) {

// When Facebook returns an error
            } catch( \Exception $ex ) {

// When validation fails or other local issues
                echo $ex->getMessage();
            }
        }

// Check if a session exists
        if ( isset( $session ) ) {

// Save the session
            $_SESSION['fb_token'] = $session->getToken();

// Create session using saved token or the new one we generated at login
            $session = new FacebookSession( $session->getToken() );

// Create the logout URL (logout page should destroy the session)
            $logoutURL = $helper->getLogoutUrl( $session, 'http://yourdomain.com/logout' );
            $this->redirect()->toRoute('Fpage');
        } else {
// No session

// Requested permissions - optional
            $permissions = array(
                'email',
                'user_location',
                'user_birthday'
            );

// Get login URL
            $loginUrl = $helper->getLoginUrl($permissions);
            return new ViewModel(
                array('loginUrl' => $loginUrl)

            );
        }

    }
}
