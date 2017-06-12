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

    /**
     * Client constructor.
     *
     * @param \GuzzleHttp\Client $client
     *   A Guzzle client object.
     */
    public function __construct($client)
    {
        $this->client = $client;
    }
}
