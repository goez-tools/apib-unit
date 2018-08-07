<?php

namespace Goez\ApibUnit\Apib;

use Goez\ApibUnit\Traits\Elements;

/**
 * Class Element
 * @package Goez\ApibUnit\Apib
 */
abstract class Element
{
    use Elements;

    /**
     * Element constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * @param $data
     * @return Element|null
     */
    public static function createFrom($data): ?Element
    {
        $type = $data['element'] ?? '';

        switch($type) {
            case 'category':
                $class = Category::class;
                break;
            case 'resource':
                $class = Resource::class;
                break;
            default:
                return null;
        }

        return new $class($data);
    }
}
