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
                $config = $app['proxies'][$service];

                /** @var $client \GuzzleHttp\Client */
                $client = $app['client'];

                $query = $request->query->all();
                if (!empty($config['authentication']['query_parameters'])) {
                    $query = array_merge($query, $config['authentication']['query_parameters']);
                }

                $options = [
                    'query' => $query,
                ];

                $uri = $config['base_url'] . $endpoint;

                $response = $client->request($request->getMethod(), $uri, $options);

                $headers = [
                    'Cache-Control' => $config['cache_control'], // Only for GET requests
                    'Content-Type' => $response->getHeader('Content-Type'),
                ];

                return new Response($response->getBody(), $response->getStatusCode(), $headers);
            })
            ->assert('endpoint', '.*')
            ->before(function (Request $request) use ($app) {
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
