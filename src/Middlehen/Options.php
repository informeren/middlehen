<?php

namespace Middlehen;

class Options
{
    protected $config;

    private $options;

    /**
     * Client constructor.
     *
     * @param array $config
     *   A proxy configuration array.
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->options = [
            'headers' => [
                'User-Agent' => 'MiddleHen/1.0 (+information.dk)',
            ],
        ];
    }

    /**
     * Get the finished options array.
     *
     * @return array
     *   An array for use in a HTTP request.
     */
    public function getOptions() {
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
        if (!empty($this->config['authentication']['query_parameters'])) {
            $query_parameters = array_merge($query_parameters, $this->config['authentication']['query_parameters']);
        }

        if (!empty($query_parameters)) {
            $this->options['query'] = $query_parameters;
        }
    }
}
