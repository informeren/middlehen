<?php

namespace App\Tests;

use Silex\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testFrontPage()
    {
        $client = $this->createClient();
        $crawler = $client->request('GET', '/');


        $this->assertTrue($client->getResponse()->isOk());
        $this->assertCount(1, $crawler->filter('img[src="/img/middlehen.png"]'));
    }

    public function createApplication()
    {
        $app = require __DIR__ . '/../../../app/app.php';
        $app['debug'] = true;
        unset($app['exception_handler']);

        return $app;
    }
}
