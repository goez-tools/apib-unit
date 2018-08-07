<?php

namespace Goez\ApibUnit\Apib;

/**
 * Class Resource
 * @package Goez\ApibUnit\Apib
 */
class Resource extends Element
{
    /**
     * @var string
     */
    protected $uriTemplate = '';

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * Resource constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        parent::__construct($data);
        foreach ((array) $data['actions'] as $actionData) {
            $actionData['uri'] = $this->uriTemplate;
            $actionData['parameters'] = array_merge($actionData['parameters'], $this->parameters);
            $this->appendElement(new Action($actionData));
        }
    }
}
