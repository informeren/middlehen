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
        $this->options = [];
    }

    public function getOptions() {
        return $this->options;
    }

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
