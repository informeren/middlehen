<?php

namespace Middlehen;

/**
 * Class Events.
 */
class EventsForProxy {

  /**
   * Event subscribers for a proxy.
   * @var array
   */
  private $eventSubscribers = [];

  /**
   * Event constructor.
   *
   * @param array $proxyConfig
   *   A proxy configuration array.
   */
  public function __construct(array $proxyConfig)
  {
    if (!empty($proxyConfig['eventSubscribers']) && is_array($proxyConfig['eventSubscribers'])) {
      $this->eventSubscribers = $proxyConfig['eventSubscribers'];
    }
  }

  /**
   * Check if the proxy has event subscribers.
   *
   * @return bool
   *   True if there is one or more event subscribers for the proxy.
   */
  public function hasSubscribers() {
    return !empty($this->eventSubscribers);
  }

  /**
   * Get event subscribers for the proxy.
   * @return array
   */
  public function getEventSubscribers() {
    return $this->eventSubscribers;
  }

}
