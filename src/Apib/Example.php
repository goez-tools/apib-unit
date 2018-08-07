<?php

namespace Goez\ApibUnit\Apib;

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
     * @param \Goez\ApibUnit\Apib\Request[] $requests $requests
     * @param \Goez\ApibUnit\Apib\Response[] $responses
     */
    public function __construct(array $requests = [], array $responses = [])
    {
        $this->requests = $requests;
        $this->responses = $responses;
    }

    /**
     * @param \Goez\ApibUnit\Apib\Request[] $requests
     */
    public function setRequests(array $requests): void
    {
        $this->requests = $requests;
    }

    /**
     * @param \Goez\ApibUnit\Apib\Response[] $responses
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
