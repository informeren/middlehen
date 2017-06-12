<?php

use App\Controller\DefaultController;
use App\Controller\ProxyController;
use GuzzleHttp\Client;
use Middlehen\Options;
use Monolog\Handler\SyslogHandler;
use Silex\Application;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\TwigServiceProvider;

//use Symfony\Component\HttpFoundation\Request;
//Request::setTrustedProxies(['', '']);

// Default configuration. See README.md for a complete example.
$config = [
    'debug' => true,
    'environment' => 'development',
    'proxies' => [],
];

if (file_exists(__DIR__ . '/config.php')) {
    include __DIR__ . '/config.php';
}

$app = new Application($config);

// Register the Twig template engine service.
$app->register(new TwigServiceProvider(), [
    'twig.path' => __DIR__ . '/views',
]);

// Register the Monolog logging service and send all messages to the syslog.
$app->register(new MonologServiceProvider(), [
    'monolog.handler' => new SyslogHandler('middlehen'),
]);

// Register the HTTP client service.
$app['middlehen.client'] = function ($app) {
    $config = [
        'base_uri' => $app['middlehen.config']['base_uri'],
    ];
    return new Client($config);
};

// Register the query options service.
$app['middlehen.options'] = function ($app) {
    return new Options($app['middlehen.config']);
};

$app->mount('/', new DefaultController());
$app->mount('/proxy', new ProxyController());

return $app;
