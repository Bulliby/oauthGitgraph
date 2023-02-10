<?php

require('../vendor/autoload.php');

use Oauth\Auth;
use Oauth\Exceptions\OauthGithubException;

if (
    !isset($_SERVER['CLIENT_ID']) ||
    !isset($_SERVER['CLIENT_SECRET'])  ||
    !isset($_SERVER['CALLBACK_URL'])
) {
    throw new OauthGithubException('Some ENV parameters are missing from your Docker run');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST' && $_SERVER['REQUEST_METHOD'] != 'OPTIONS') {
    return http_response_code(405);
}

(new Auth())->getGithubToken();
