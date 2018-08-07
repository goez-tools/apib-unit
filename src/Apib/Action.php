<?php

namespace Goez\ApibUnit\Apib;
use Goez\ApibUnit\Endpoint;

/**
 * Class Action
 * @package Goez\ApibUnit\Apib
 */
class Action extends Element
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $uri = '';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string
     */
    protected $method = 'GET';

    /**
     * @var Example[];
     */
    protected $examples = [];

    /**
     * @var string
     */
    protected $schema = '';

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->uri = $data['attributes']['uriTemplate'] ?: $this->uri;
        $this->method = $data['method'];
        $this->rebuildParameters();

        $examples = [];
        foreach ((array) $data['examples'] as $example) {
            $requests = [];
            $responses = [];

            foreach ((array) $example['requests'] as $request) {
                $request['method'] = $this->method;
                $requests[] = new Request($request);
            }

            foreach ((array) $example['responses'] as $response) {
                $responses[] = new Response($response);
            }

            $examples[] = new Example($requests, $responses);
        }

        $this->examples = $examples;
    }

    private function rebuildParameters(): void
    {
        $parameters = [];
        foreach ((array) $this->parameters as $parameterData) {
            $parameters[$parameterData['name']] = (new Parameter($parameterData))->getValue();
        }
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function getEndpoints(): array
    {
        $endpoint = new Endpoint($this->uri);
        $endpoint
            ->setMethod($this->method)
            ->setParameters($this->parameters)
            ->setExamples($this->examples);

        return [ $endpoint ];
    }
}
