# OAuth Gitgraph

OAuth Gitgraph permit to connect you with **OAuth** to the **Github API**.

## How create your OAuth Apps

`Your github profile > Settings > Developer settings > OAuth Apps > New OAuth App`

## Environment

This application read 3 **ENV** variables :

```php
    $_ENV['CLIENT_ID'];
    $_ENV['CLIENT_SECRET'];
    $_ENV['CALLBACK_URL'];
```

## Cookies

This application set two cookies `name` and `token` which authenticate you with the **Github Api**. The domain chosen for the cookies is the one of `$_ENV['CALLBACK_URL']`

## Docker

I created a docker **php81**who permit to run a server who server this application.
