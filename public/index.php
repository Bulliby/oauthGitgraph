<?php

require('../vendor/autoload.php');

use Oauth\Auth;

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    return http_response_code(200);
} else if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    return http_response_code(405);
}

echo (new Auth())->getGithubToken();
