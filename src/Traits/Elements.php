<?php

namespace Goez\ApibUnit\Traits;

use Goez\ApibUnit\Apib\Element;

trait Elements
{
    /**
     * @var array
     */
    protected $elements = [];

    /**
     * @return array
     */
    public function getEndpoints(): array
    {
        $endpoints = [[]];
        foreach ($this->elements as $element) {
            /* @var Element $element */
            $endpoints[] = $element->getEndpoints();
        }

        return array_merge(...$endpoints);
    }

    /**
     * @param Element $element
     */
    public function appendElement(?Element $element): void
    {
        if (null !== $element) {
            $this->elements[] = $element;
        }
    }
}
