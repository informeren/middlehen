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

        /** @noinspection PhpUnusedParameterInspection */
        $controllers
            ->match('/{service}/{endpoint}', function (Request $request, $service, $endpoint) use ($app) {
                if (empty($app['proxies'][$service])) {
                    return new Response('Unknown service', 400);
                }

                $app['middlehen.config'] = $app['proxies'][$service];

                $options = $app['middlehen.options'];
                $options->addQueryParameters($request->query->all());

                /** @var $client \GuzzleHttp\Client */
                $client = $app['middlehen.client'];
                $response = $client->request($request->getMethod(), $endpoint, $options->getOptions());

                $status_code = $response->getStatusCode();
                if ($template = $request->headers->get('x-mustache')) {
                    $template = $service . '/' . $template . '.mustache';
                    $data = json_decode($response->getBody());
                    $headers['Content-Type'] = 'text/html';
                    $headers['Access-Control-Allow-Origin'] = '*';
                    $headers['Access-Control-Allow-Methods'] = 'GET';
                    $headers['Access-Control-Allow-Headers'] = 'X-Mustache, X-Requested-With, Content-Type';
                    return new Response($app['mustache']->render($template, $data), $status_code, $headers);
                } else {
                    $headers['Content-Type'] = $response->getHeader('Content-Type');
                    $headers['Access-Control-Allow-Origin'] = '*';
                    $headers['Access-Control-Allow-Methods'] = 'GET';
                    $headers['Access-Control-Allow-Headers'] = 'X-Mustache, X-Requested-With, Content-Type';
                    return new Response($response->getBody(), $status_code, $headers);
                }
            })
            ->assert('endpoint', '.*')
            ->after(function (Request $request, Response $response, Application $app) {
                $response->headers->set('Cache-Control', $app['middlehen.config']['cache_control']);
                $response->headers->set('Vary', 'Accept');
            });

        return $controllers;
    }
}
