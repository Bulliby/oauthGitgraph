<?php

require '../vendor/autoload.php';

use GuzzleHttp\Client;

class Github
{
    private $code;
    private $state;
    private $client_secret;
    private $client_id;
    private $redirect_uri;
    private $client;
    private $token;

    public function __construct()
    {
        if (!isset($_GET['code']) || !isset($_GET['state']) || !isset($_GET['env']))
            throw new \Exception('Some query paramters are missing');

        $this->code = $_GET['code'];
        $this->state = $_GET['state'];
        $this->redirect_uri = ($_GET['env'] == 'development') ? getenv('DEV_GIT_GRAPH_URL') : getenv('PROD_GIT_GRAPH_URL'); 
        $this->client_id = ($_GET['env'] == 'development') ? getenv('DEV_CLIENT_ID') : getenv('PROD_CLIENT_ID'); 
        $this->client_secret = ($_GET['env'] == 'development') ? getenv('DEV_CLIENT_SECRET') : getenv('PROD_CLIENT_SECRET'); 

        //TODO: make no sesnse?
        //$dotenv->required('DATABASE_DSN')->notEmpty();
        if ($this->client_secret == false)
            throw new \Exception("The var env CLIENT_SECRET is not set");

        $this->client = new GuzzleHttp\Client();
    }

    public function getGithubToken() 
    {
        $response = $this->client->request('POST', 'https://github.com/login/oauth/access_token', [
            'form_params' => [
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect_uri' => $this->redirect_uri,
            'state' => $this->state,
            'code' => $this->code
            ],
            'headers' => [
                'Accept'     => 'application/json',
            ]
        ]);
        
        $this->token = $response->getBody();
    }

    public function getToken()
    {
        return $this->token;
    }
}

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/..');
$dotenv->load();

$git = new Github();
$git->getGithubToken();


echo $git->getToken();
