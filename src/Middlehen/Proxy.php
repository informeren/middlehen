<?php

namespace Middlehen;

class Proxy
{
    /**
     * A GuzzleHTTP client.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    protected $config;

    private $method;
    private $relative_uri;

    /**
     * Client constructor.
     *
     * @param \GuzzleHttp\Client $client
     *   A Guzzle client object.
     * @param array $config
     *   A proxy configuration array.
     */
    public function __construct($client, $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function setRelativeUri($relative_uri)
    {
        $this->relative_uri = $relative_uri;
    }
}
