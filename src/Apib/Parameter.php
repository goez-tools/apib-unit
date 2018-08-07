<?php

namespace Goez\ApibUnit\Apib;

/**
 * Class Parameter
 * @package Goez\ApibUnit\Apib
 */
class Parameter extends Element
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $type = '';

    /**
     * @var string
     */
    protected $required = '';

    /**
     * @var string
     */
    protected $default = '';

    /**
     * @var string
     */
    protected $example = '';

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->example ?? $this->default ?? '';
    }
}
