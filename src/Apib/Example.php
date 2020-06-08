<?php

namespace Goez\ApibUnit\Apib;

/**
 * Class Example
 */
class Example
{
    /**
     * @var Request[]
     */
    protected $requests = [];

    /**
     * @var Response[]
     */
    protected $responses = [];

    /**
     * Example constructor.
     *
     * @param Request[]  $requests  $requests
     * @param Response[] $responses
     */
    public function __construct(array $requests = [], array $responses = [])
    {
        $this->requests = $requests;
        $this->responses = $responses;
    }

    /**
     * @param Request[] $requests
     */
    public function setRequests(array $requests): void
    {
        $this->requests = $requests;
    }

    /**
     * @param Response[] $responses
     */
    public function setResponses(array $responses): void
    {
        $this->responses = $responses;
    }

    /**
     * @return array|Request[]
     */
    public function getRequests(): array
    {
        return $this->requests;
    }

    /**
     * @return array|Response[]
     */
    public function getResponses(): array
    {
        return $this->responses;
    }
}
