<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$request = Illuminate\Http\Request::create('/search/live?q=runner', 'GET');
$res = $app->handle($request);
echo "Live Search Status: " . $res->getStatusCode() . "\n";
echo "Live Search Body: " . substr($res->getContent(), 0, 500) . "...\n\n";

$request2 = Illuminate\Http\Request::create('/search?q=runner', 'GET');
$res2 = $app->handle($request2);
echo "Search Page Status: " . $res2->getStatusCode() . "\n";
