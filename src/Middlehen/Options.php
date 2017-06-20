<?php

namespace Middlehen;

class Options
{
    private $options;

    /**
     * Client constructor.
     *
     * @param array $config
     *   A proxy configuration array.
     */
    public function __construct($config)
    {
        $options = [
            'query' => [],
            'headers' => [
                'User-Agent' => 'MiddleHen/1.0 (+information.dk)',
            ],
        ];

        if (!empty($config['authentication']['query_parameters'])) {
            $options['query'] += $config['authentication']['query_parameters'];
        }

        if (!empty($config['authentication']['http_headers'])) {
            $options['headers'] += $config['authentication']['http_headers'];
        }

        $this->options = $options;
    }

    /**
     * Get the finished options array.
     *
     * @return array
     *   An array for use in a HTTP request.
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Add query parameters to the options array.
     *
     * Merges authentication parameters if they exist in the proxy configuration.
     *
     * @param array $query_parameters
     *   The query parameters to merge.
     */
    public function addQueryParameters($query_parameters)
    {
        if (!empty($query_parameters)) {
            $this->options['query'] += $query_parameters;
        }
    }
}
