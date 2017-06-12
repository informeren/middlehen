<?php

namespace App\Controller;

use Silex\Application;
use Silex\Api\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        /** @var $controllers \Silex\ControllerCollection */
        $controllers = $app['controllers_factory'];

        $controllers
            ->match('/{service}/{endpoint}', function (Request $request, $service, $endpoint) use ($app) {
                $app['middlehen.config'] = $app['proxies'][$service];

                /** @var $client \GuzzleHttp\Client */
                $client = $app['middlehen.client'];
                $options = $app['middlehen.options'];

                $options->addQueryParameters($request->query->all());

                $response = $client->request($request->getMethod(), $endpoint, $options->getOptions());

                $headers = [
                    'Cache-Control' => $app['middlehen.config']['cache_control'],
                    'Content-Type' => $response->getHeader('Content-Type'),
                ];

                return new Response($response->getBody(), $response->getStatusCode(), $headers);
            })
            ->assert('endpoint', '.*')
            ->before(function (Request $request, Application $app) {
                // TODO: Only allow proxy requests from trusted IPs
                $path = substr($request->getPathInfo(), 1);
                list(, $service, ) = explode('/', $path);
                if (empty($app['proxies'][$service])) {
                    return new Response('Unknown service', 400);
                }
                return null;
            });

        return $controllers;
    }
}
