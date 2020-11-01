<?php

require('../vendor/autoload.php');

use Oauth\Auth;

#TODO configurable
header('Access-Control-Allow-Origin: https://client.gitgraph.wellsguillaume.fr');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    return http_response_code(200);
} else if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    return http_response_code(405);
}

(new Auth())->getGithubToken();
