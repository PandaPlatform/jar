<?php

/*
 * This file is part of the Panda Jar Package.
 *
 * (c) Ioannis Papikas <papikas.ioan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Panda\Jar\Model;

use Exception;
use ReflectionClass;
use ReflectionProperty;
use Throwable;

/**
 * Class BaseModel
 * @package Panda\Jar\Model
 */
abstract class BaseModel
{
    /**
     * @param string $name
     * @param mixed  $default
     *
     * @return mixed
     */
    public function getPropertyByName($name, $default = null)
    {
        try {
            return $this->$name;
        } catch (Throwable $ex) {
            return $default;
        }
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function setPropertyByName($name, $value)
    {
        try {
            $this->$name = $value;
        } catch (Throwable $ex) {
        }
    }

    /**
     * @param array $array
     *
     * @return $this
     */
    public function loadFromArray($array = [])
    {
        foreach ($array as $name => $value) {
            $this->setPropertyByName($name, $value);
        }

        return $this;
    }

    /**
     * Convert the current object to array
     *
     * @param int $filter It can be configured using the ReflectionProperty constants
     *
     * @return array
     */
    public function toArray($filter = ReflectionProperty::IS_PROTECTED)
    {
        $objectArray = [];

        // Get all properties
        $ref = new ReflectionClass($this);
        $properties = $ref->getProperties($filter);
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            if (isset($this->$propertyName)) {
                $objectArray[$propertyName] = $this->$propertyName;
            }
        }

        return $objectArray;
    }

    /**
     * @param $name
     * @param $value
     *
     * @throws Exception
     */
    public function __set($name, $value)
    {
        throw new Exception(static::class . ' does not have a property with the name [' . $name . ']');
    }
}
