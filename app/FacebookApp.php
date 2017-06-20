<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once (__DIR__ . "/../vendor/autoload.php");
require_once ("conf.php");

class FacebookApp
{
    private $fb;
    private $accessToken;
    private $helper;

    function __construct()
    {
        $this->fb = new Facebook\Facebook([
            'app_id' => '1925419977671868',
            'app_secret' => '14979d39cbf97457c300c23687a8fc1b',
            'default_graph_version' => 'v2.8',
        ]);

        $this->helper = $this->fb->getCanvasHelper();
    }

    public function actionFbAuth()
    {
        session_start();
        $helper = $this->fb->getRedirectLoginHelper();
        if (isset($_GET['state'])) {
            $helper->getPersistentDataHandler()->set('state', $_GET['state']);
        }
        try {
            if (isset($_SESSION['facebook_access_token'])) {
                $accessToken = $_SESSION['facebook_access_token'];
            } else {
                $accessToken = $helper->getAccessToken();
            }
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error:1 ' . $e->getMessage();
            var_dump($helper->getError());
            exit;
        }

        if (isset($accessToken)) {

            if (isset($_SESSION['facebook_access_token'])) {
                $this->fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
            } else {
                // getting short-lived access token
                $_SESSION['facebook_access_token'] = (string)$accessToken;
                // OAuth 2.0 client handler
                $oAuth2Client = $this->fb->getOAuth2Client();
                // Exchanges a short-lived access token for a long-lived one
                $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
                $_SESSION['facebook_access_token'] = (string)$longLivedAccessToken;
                // setting default access token to be used in script
                $this->fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
            }
            // redirect the user back to the same page if it has "code" GET variable
            $this->accessToken = $accessToken;

            if (isset($_GET['code'])) {
                header('Location: ./');
            }
        }
    }

    public function getLoginInfo()
    {
        try {
            $profile_request = $this->fb->get('/me?fields=name,first_name,last_name,email');
            $profile = $profile_request->getGraphNode()->asArray();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            session_destroy();
            // redirecting user back to app login page
            header("Location: ./");
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $image = 'https://graph.facebook.com/' . $profile['id'] . '/picture?width=200';
        echo "<img src='$image' /><br><br>";
        print_r($profile);
    }



    public function  postMassegeInTimeline()
    {
        try {
            $data = ['message' => 'testing...api  5.0.0.5'];
            $request = $this->fb->post('/me/feed', $data);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {

            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        // return created id
        echo "<pre>";
        var_dump($request->getGraphNode()->asArray());
    }

    public function uploadPhoto()
    {
        try {
        $data = ['source' => $this->fb->fileToUpload('111.jpg'),
        'message' => 'My file!',];
        $response = $this->fb->post('/me/photos', $data);
        }
        catch (Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }
        // return created id
        echo "<pre>";
        var_dump($response->getGraphNode()->asArray());
    }



    public function getAccessStatus()
    {
        if (!empty($this->accessToken)) {
            return true;
        }
    }

    public function getLogoutUrl()
    {
        session_destroy();
        $loginUrl =  $this->helper->getLogoutUrl('http://webdevelop.it-dev-lab.com/index.php');
        echo '<a href="' . $loginUrl . '">LogOut</a>';
    }

    public function getLoginUrl($permissions)
    {
        $this->helper = $this->fb->getRedirectLoginHelper();
        $permissions = ['email', 'publish_actions'];
        $loginUrl =  $this->helper->getLoginUrl('http://webdevelop.it-dev-lab.com/index.php', $permissions);
        echo '<a href="' . $loginUrl . '">Log in with Facebook!</a>';
    }
}




