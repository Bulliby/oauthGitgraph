<?php

require('../vendor/autoload.php');

use GuzzleHttp\Client;
use Oauth\Exceptions\OauthGithubException;

class Github
{
    private $client;

    public function __construct()
    {
        if (
            !isset($_ENV['CLIENT_ID']) ||
            !isset($_ENV['CLIENT_SECRET'])  ||
            !isset($_ENV['CALLBACK_URL'])
        ) {
            throw new OauthGithubException('Some ENV parameters are missing from your Docker run');
        }

        if (!isset($_POST['code']) || !isset($_POST['state']))
            throw new OauthGithubException('The Post parameters code or state are not set');

        $this->client = new GuzzleHttp\Client();

        return $this;
    }

    public function getGithubToken()
    {
         $response = $this->client->request('POST', 'https://github.com/login/oauth/access_token', [
            'form_params' => [
            'client_id' => $_ENV['CLIENT_ID'],
            'client_secret' => $_ENV['CLIENT_SECRET'],
            'redirect_uri' => $_ENV['CALLBACK_URL'],
            'state' => $_POST['state'],
            'code' => $_POST['code'],
            ],
            'headers' => [
                'Accept'     => 'application/json',
            ],
        ]);

        return $response->getBody();
    }
}

echo (new Github())->getGithubToken();
