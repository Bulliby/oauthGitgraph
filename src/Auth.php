<?php

namespace Oauth;

use GuzzleHttp\Client;
use Oauth\Exceptions\OauthGithubException;

class Auth
{
    private $client;
    private $json;

    public function __construct()
    {

        if (
            !isset($_ENV['CLIENT_ID']) ||
            !isset($_ENV['CLIENT_SECRET'])  ||
            !isset($_ENV['CALLBACK_URL'])
        ) {
            throw new OauthGithubException('Some ENV parameters are missing from your Docker run');
        }

        /**
         * The Content-Type of axios request is application/json and
         * php://input is a way to read it in the request. Symfony request
         * package must handle it the same way (i think). To have the
         * $_POST variable populated by axios request you must provide
         * the 'application/x-www-form-urlencoded' Content-Type request header
         * who is default in form tags
         */
        //Limit to 100 characters to avoid large request
        $json = file_get_contents('php://input', false, null, 0, 100);

        if ($json === false) {
            throw new OauthGithubException('Can\'t read data from request');
        }

        //depth to 2 because we don't need more
        $json = json_decode($json, true, 2);

        if (is_null($json)) {
            throw new OauthGithubException('The json is invalid');
        }

        if (!isset($json['code']) || !isset($json['state']))
            throw new OauthGithubException('The json parameters code or state are not set');

        $this->client = new Client();
        $this->json = $json;

        return $this;
    }

    public function getGithubToken()
    {
         $response = $this->client->request('POST', 'https://github.com/login/oauth/access_token', [
            'form_params' => [
            'client_id' => $_ENV['CLIENT_ID'],
            'client_secret' => $_ENV['CLIENT_SECRET'],
            'redirect_uri' => $_ENV['CALLBACK_URL'],
            'code' => $this->json['code'],
            'state' => $this->json['state'],
            ],
            'headers' => [
                'Accept'     => 'application/json',
            ],
        ]);

        $responseToken = json_decode($response->getBody());
        setcookie('oauth', $responseToken->access_token, time()+24*3600, "/", "gitgraph.com");

        $response = $this->client->request('GET', 'https://api.github.com/user', ['auth' => [null, $responseToken->access_token]]);
        $responseToken = json_decode($response->getBody());
        setcookie('name', $responseToken->login, time()+24*3600, "/", "gitgraph.com");

        header('Access-Control-Allow-Origin: http://client.gitgraph.com');
        header('Access-Control-Allow-Headers: Content-Type');
        header('Access-Control-Allow-Credentials: true');
    }
}
