<?php

namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;

class DefaultController implements ControllerProviderInterface
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
        $controllers = $app['controllers_factory'];

        // Controller for the dashboard frontpage.
        $controllers->get('/', function (Application $app) {
            /** @var $app \Mustache_Engine[] */
            return $app['mustache']->render('index');
        });

        return $controllers;
    }
}
