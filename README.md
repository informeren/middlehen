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
            'endpoint' => 'https://foo.example.com/v1/',
            'authentication' => [
                'http_headers' => [
                    'X-Foo-Auth' => '',
                ],
            ],
        ],
        'bar' => [
            'endpoint' => 'https://bar.example.com/v3/',
            'authentication' => [
                'query_parameters' => [
                    'baz' => '',
                    'bat' => '',
                ],
            ],
        ],
    ],
];
```

Now you can go to the `htdocs` directory and run the following command to start a local web server.

    php -S 127.0.0.1:8000


Testing
-------

To run the test suite simply run `phpunit` in the project root. This will create a coverage report in the terminal and a HTML report in the `report/` directory.


_Chicken mascot created by Mia Mottelson._
