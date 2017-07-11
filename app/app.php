<?php

use App\Controller\DefaultController;
use App\Controller\ProxyController;
use GuzzleHttp\Client;
use Middlehen\EventsForProxy;
use Middlehen\Options;
use Monolog\Handler\SyslogHandler;
use Mustache\Silex\Provider\MustacheServiceProvider;
use Silex\Application;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;

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

// Register the Monolog logging service and send all messages to the syslog.
$app->register(new MonologServiceProvider(), [
    'monolog.handler' => new SyslogHandler('middlehen'),
]);

// Register the Mustache template engine service.
$app->register(new MustacheServiceProvider, array(
    'mustache.path' => __DIR__ . '/views',
    'mustache.options' => array(
        'cache' => __DIR__ . '/cache/mustache',
    ),
));

// Register the HTTP client service.
$app['middlehen.client'] = function ($app) {
    $config = [
        'base_uri' => $app['middlehen.config']['base_uri'],
        'debug' => $app['debug'],
    ];
    return new Client($config);
};

// Register the query options service.
$app['middlehen.options'] = function ($app) {
    return new Options($app['middlehen.config']);
};

// Register the event subscribers.
$app['middlehen.events'] = function ($app) {
  return new EventsForProxy($app['middlehen.config']);
};

if (!empty($config['databases'])) {
  $app->register(new DoctrineServiceProvider(), [
    'dbs.options' => $config['databases'],
  ]);
}

$app->mount('/', new DefaultController());
$app->mount('/proxy', new ProxyController());

return $app;
