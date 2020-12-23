<?php

require __DIR__.'/../bootstrap.php';

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);

if (str_starts_with($uri, '/r/')) {
    handleRedirect($uri);
    return;
}

_404($uri);
