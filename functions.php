<?php

use Nddcoder\TinyURL\TinyURL;

function _404(string $uri): void
{
    header('Status: 404');
    echo 'Not found '.$uri;
}

function _400(string $message): void
{
    header('Status: 400');
    echo $message;
}

function handleRedirect(string $uri): void
{
    $tinyUrlId = str_replace('/r/', '', $uri);
    $targetUrl = TinyURL::preview("https://tinyurl.com/{$tinyUrlId}");

    if ($targetUrl) {
        redirect($targetUrl);
        return;
    }
    _404($uri);
}

function handleDynamicRedirect(): void
{
    $targetUrl = $_GET['u'];
    if (!filter_var($targetUrl, FILTER_VALIDATE_URL)) {
        _400('missing "u" param');
        return;
    }

    redirect($targetUrl);
}

function redirect($url)
{
    switch ($redirectStatus = getRedirectStatus()) {
        case 301:
        case 302:
            redirectStatus($redirectStatus, $url);
            break;
        default:
            redirectMeta($url);
    }
}

function getRedirectStatus(): string
{
    $redirectStatus = $_GET['s'] ?? 302;
    if (!in_array($redirectStatus, [301, 302, 'meta'])) {
        $redirectStatus = 302;
    }
    return $redirectStatus;
}

function redirectStatus(int $status, string $url): void
{
    header('Status: '.$status);
    header('Location: '.$url);
}

function redirectMeta(string $url): void
{
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="referrer" content="no-referrer">
        <title>Redirecting...</title>
        <meta http-equiv="refresh" content="0;url={$url}"/>
    </head>
    <body></body>
</html>
HTML;
}