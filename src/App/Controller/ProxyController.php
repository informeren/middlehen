<?php

namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class ProxyController implements ControllerProviderInterface
{
    /**
     * @param Application $app
     *
     * @return \Silex\ControllerCollection
     *
     * @codeCoverageIgnore
     */
    public function connect(Application $app)
    {
        /* @var $controllers \Silex\ControllerCollection */
        $controllers = $app['controllers_factory'];

        // Generic controller for GET requests.
        $controllers->get('/{endpoint}', function (Application $app, $endpoint) {
            $proxies = $app['proxies'];
            return "Endpoint: $endpoint";
        })->assert('endpoint', '.*');

        return $controllers;
    }
}
