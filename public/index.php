<?php

require('../vendor/autoload.php');

use Oauth\Auth;
use Oauth\Exceptions\OauthGithubException;

if (
    !isset($_ENV['CLIENT_ID']) ||
    !isset($_ENV['CLIENT_SECRET'])  ||
    !isset($_ENV['CALLBACK_URL'])
) {
    throw new OauthGithubException('Some ENV parameters are missing from your Docker run');
}

header('Access-Control-Allow-Origin: ' . $_ENV['CALLBACK_URL']);
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    return http_response_code(200);
} else if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    return http_response_code(405);
}

(new Auth())->getGithubToken();
