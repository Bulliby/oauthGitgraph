<?php

require 'vendor/autoload.php';

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

    public function __construct($client_id, $client_secret, $redirect_uri)
    {
        if (!isset($_GET['code']) || !isset($_GET['state']))
            throw new \Exception('The code or state must be provided by query string $_GET["code"]');

        $this->code = $_GET['code'];
        $this->state = $_GET['state'];
        $this->client_id = $client_id;
        $this->redirect_uri = $redirect_uri; 
        $this->client_secret = $client_secret;

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

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$git = new Github('3c47a9a8faf9b82f5634', getenv('CLIENT_SECRET'), 'http://gitgraph');
$git->getGithubToken();


echo $git->getToken();
