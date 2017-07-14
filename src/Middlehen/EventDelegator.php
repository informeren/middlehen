<?php

namespace Middlehen;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * Class EventDelegator
 *
 *
 */
class EventDelegator implements EventSubscriberInterface
{
    private $app;
    private $serviceName;
    /**
     * @var DefaultEventListener
     */
    private $eventListener;

    public function __construct($app)
    {
        $this->app = $app;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
            KernelEvents::RESPONSE => 'onKernelResponse',
        ];
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($event->isMasterRequest()) {
            $urlParts = explode('/', ltrim($event->getRequest()->getPathInfo(), '/'));
            if (empty($urlParts[1]) || empty($this->app['proxies'][$urlParts[1]])) {
                // Short circuit the whole thing and stop here. 
                return new Response('Unknown proxy service', 400);
            }
            $this->serviceName = $urlParts[1];

            $config = $this->app['proxies'][$this->serviceName];
            $this->app['middlehen.config'] = $config;

            // Register the query options service.
            $this->app['middlehen.options'] = function () use ($config) {
                return new Options($config);
            };

            if (!empty($config['eventSubscriber'])) {
                $this->eventListener = new $config['eventSubscriber']($this->app);
            } else {
                $this->eventListener = new DefaultEventListener($this->app);
            }
        }
        $this->eventListener->onRequest($event);
    }

    /**
     * Callback for subscribed event.
     *
     * The response from parse.ly
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if ($this->eventListener) {
            $this->eventListener->onResponse($event);
        }
    }
}
