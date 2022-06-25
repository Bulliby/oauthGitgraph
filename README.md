# OAuth Gitgraph

OAuth Gitgraph permit to connect you with **OAuth** to the **Github API**.

## How create your OAuth Apps

`Your github profile > Settings > Developer settings > OAuth Apps > New OAuth App`

## Environment variables

This application read 3 **ENV** variables :

```php
    $_ENV['CLIENT_ID'];
    $_ENV['CLIENT_SECRET'];
    $_ENV['CALLBACK_URL'];
```

Used in accordance with this [docker](https://github.com/Bulliby/docker/blob/master/oauthGithub/README.md)

## Cookies

This application set two cookies `name` and `token` which authenticate you with the **Github Api**. The domain chosen for the cookies is the one of `$_ENV['CALLBACK_URL']`
