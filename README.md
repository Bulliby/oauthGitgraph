# OAuth Gitgraph

OAuth Gitgraph permit to connect you with **OAuth** to the **Github API**.

## How create your OAuth Apps

`Your github profile > Settings > Developer settings > OAuth Apps > New OAuth App`

## Environment variables

This application read 3 **SERVER** variables :

```php
    $_SERVER['CLIENT_ID'];
    $_SERVER['CLIENT_SECRET'];
    $_SERVER['CALLBACK_URL'];
```

## Cookies

This application set two cookies `name` and `token` which authenticate you with the **Github Api**. The domain chosen for the cookies is the one of `$_SERVER['CALLBACK_URL']`
