<?php

namespace Middlehen;

use Silex\Application;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class DefaultEventListener
{

    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function onRequest(GetResponseEvent $event)
    {
        // Override if you want stuff to happen.
    }

    public function onResponse(FilterResponseEvent $event)
    {
        // Override if you want stuff to happen.
    }
}
