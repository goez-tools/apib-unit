<?php

namespace Goez\ApibUnit;

use Goez\ApibUnit\Apib\Example;

class Endpoint
{
    /**
     * @var string
     */
    private $uriTemplate = '';

    /**
     * @var array
     */
    private $extra;

    /**
     * @var string
     */
    private $method = 'GET';

    /**
     * @var string
     */
    private $uri = '';

    /**
     * @var array
     */
    private $parameterKeys = [];

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var Example[]
     */
    private $examples = [];

    /**
     * @var array
     */
    private $pathParameters = [];

    /**
     * @var array
     */
    private $queryParameters = [];

    /**
     * Endpoint constructor.
     * @param string $uriTemplate
     */
    public function __construct(string $uriTemplate)
    {
        $this->parseUri($uriTemplate);
    }

    /**
     * @param $uriTemplate
     */
    private function parseUri($uriTemplate): void
    {
        $this->uriTemplate = $uriTemplate;
        $this->pathParameters = $this->findPathParameters($uriTemplate, '#\{([^\?\}]+)\}#');
        $this->queryParameters = $this->findPathParameters($uriTemplate, '#\{\?([^\}]+)\}#');
    }

    /**
     * @param $uri
     * @param $pattern
     * @return array
     */
    private function findPathParameters($uri, $pattern): array
    {
        $groupedParameters = [[]];
        preg_match_all($pattern, $uri, $matches);
        if (!empty($matches)) {
            $this->parameterKeys = array_merge($this->parameterKeys, $matches[0]);
            $flattenParametersArray = (array)$matches[1];
            foreach ($flattenParametersArray as $flattenParameters) {
                $groupedParameters[] = explode(',', $flattenParameters);
            }
            $groupedParameters = array_merge(...$groupedParameters);
        }
        return $groupedParameters;
    }

    /**
     * @param string $method
     * @return Endpoint
     */
    public function setMethod(string $method): Endpoint
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @param array $parameters
     * @return Endpoint
     */
    public function setParameters(array $parameters): Endpoint
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @param array $value
     */
    public function setExtra(array $value): void
    {
        $this->extra = $value;
    }

    /**
     * @param Example[] $examples
     */
    public function setExamples(array $examples)
    {
        $this->examples = $examples;

    }

    /**
     * @return string
     */
    public function getName(): string
    {
        $uriTemplate = $this->rebuildUriTemplate();
        return '[' . $this->method . ']' . $uriTemplate;
    }

    /**
     * @return array
     */
    public function getExtra(): array
    {
        return $this->extra;
    }

    /**
     * @return string
     */
    private function rebuildUriTemplate(): string
    {
        $uriTemplate = $this->uriTemplate;
        if ($this->method !== 'GET') {
            $uriTemplate = preg_replace('#\{\?([^\}]+)\}#', '', $this->uriTemplate);
        }
        return $uriTemplate;
    }

    /**
     * @return string
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        $this->rebuildUri();
        return $this->uri;
    }

    /**
     *
     */
    private function rebuildUri(): void
    {
        $mapping = $this->generateMapping();
        $search = array_keys($mapping);
        $replace = array_values($mapping);
        $this->uri = str_replace($search, $replace, $this->uriTemplate);
    }

    /**
     * @return array
     */
    private function generateMapping(): array
    {
        $mapping = $this->createMapping();
        $mapping = $this->generatePathParametersMapping($mapping);
        $mapping = $this->generateQueryParametersMapping($mapping);
        return $mapping;
    }

    /**
     * @return array
     */
    private function createMapping(): array
    {
        return array_fill_keys($this->parameterKeys, '');
    }

    /**
     * @param array $mapping
     * @return array
     */
    private function generatePathParametersMapping(array $mapping): array
    {
        foreach ($this->pathParameters as $parameterName) {
            $key = '{' . $parameterName . '}';
            if (array_key_exists($key, $mapping)) {
                $mapping[$key] = $this->parameters[$parameterName];
            }
        }
        return $mapping;
    }

    /**
     * @param $mapping
     * @return array
     */
    private function generateQueryParametersMapping(array $mapping): array
    {
        $queries = [];
        foreach ($this->queryParameters as $parameterName) {
            if (isset($this->parameters[$parameterName])) {
                $queries[$parameterName] = $this->parameters[$parameterName];
            }
        }
        $key = '{?' . implode(',', $this->queryParameters) . '}';
        if (array_key_exists($key, $mapping)) {
            $queryString = http_build_query($queries);
            $mapping[$key] = $queryString ? '?' . $queryString : '';
        }
        return $mapping;
    }

    /**
     * @return Example[]
     */
    public function getExamples(): array
    {
        return $this->examples;
    }
}
