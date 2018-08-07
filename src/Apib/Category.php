<?php

namespace Goez\ApibUnit\Apib;

/**
 * Class Category
 * @package Goez\ApibUnit\Apib
 */
class Category extends Element
{
    /**
     * @var array
     */
    protected $content = [];

    /**
     * Category constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
        foreach ((array) $data['content'] as $item) {
            $element = Element::createFrom($item);
            $this->appendElement($element);
        }
    }
}
