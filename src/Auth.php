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

        //depth to 2 because we don't need more/
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
            'state' => $this->json['state'],
            'code' => $this->json['code'],
            ],
            'headers' => [
                'Accept'     => 'application/json',
            ],
        ]);

        $response = json_decode($response->getBody());
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Allow-Origin: http://client.gitgraph.com');
        header('Set-Cookie: oauth='.$response->access_token.'; SameSite=Lax; Domain=.gitgraph.com');
    }
}
