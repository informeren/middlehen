![MiddleHen Mascot](https://raw.githubusercontent.com/informeren/middlehen/develop/assets/middlehen-400.png)

MiddleHen
=========

A simple REST proxy with authentication support.


Getting started
---------------

Use `composer` to install all dependencies:

    composer install

Create a `config.php` file in the `app` directory:

```
<?php

$config = [
    'debug' => true,
    'environment' => 'development',
    'proxies' => [
        'foo' => [
            'base_uri' => 'https://foo.example.com/v1/',
            'authentication' => [
                'http_headers' => [
                    'X-Foo-Auth' => '',
                ],
            ],
            'cache_control' => 'public, max-age=3600',
        ],
        'bar' => [
            'base_uri' => 'https://bar.example.com/v3/',
            'authentication' => [
                'query_parameters' => [
                    'baz' => '',
                    'bat' => '',
                ],
            ],
            'cache_control' => 'public, max-age=600',
            'eventSubscribers' => [
               "\MyNamespace\Thing\EventSubscriber\SomeSubscriber"
             ]
        ],
    ],
    'databases' => [
       'myNamedDb' => [
           'driver' => 'pdo_mysql',
           'host' => 'foo',
           'dbname' => 'foo',
           'user' => 'bar',
           'password' => 'foobar',
           'charset' => 'utf8',
        ]
    ]
];
```

Now you can go to the `htdocs` directory and run the following command to start a local web server.

    php -S 127.0.0.1:8000


Configuration
-------------

### Databases
Note that you can use multiple named databases. They are registered on the app so that they are available like this:
```
$app['dbs']['myNamedDb'];
```

### EventSubscribers
You can add as many event subscribers as needed on each proxy. They should implement `Symfony\Component\EventDispatcher\EventSubscriberInterface` and the constructor should expect a `Silex\Application` object.

Usage
-----

Use the `Accept` header to define the reponse format you are interested in. Support types are:

- `application/json`
- `text/html`

If you choose `text/html`, you must provide an `X-Mustache` header with the name of the template you would like to use. If you are using the `foo` proxy and the `bar` template, Middlehen will try to use the Mustache template located at `app/views/foo/bar.mustache`.


Testing
-------

To run the test suite simply run `phpunit` in the project root. This will create a coverage report in the terminal and a HTML report in the `report/` directory.


_Chicken mascot created by Mia Mottelson._
