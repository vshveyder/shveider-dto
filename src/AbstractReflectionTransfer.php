<?php

namespace ShveiderDto;

use ReflectionObject;

/**
 * Transfer that use reflection object to grab properties.
 * With this class you can add private properties to your dto and use toArray as well.
 */
abstract class AbstractReflectionTransfer implements DataTransferObjectInterface
{
    protected array $__modified = [];

    protected ReflectionObject $__reflection;

    public function __construct()
    {
        $this->__reflection = new ReflectionObject($this);
    }

    public function fromArray(array $data): static
    {
        $properties = [];

        foreach ($this->__reflection->getProperties() as $property) {
            if ($property->isStatic()) {
                continue;
            }

            if ($property->getName() === '__reflection') {
                continue;
            }

            if (in_array($property->getName(), static::SKIPPED_PROPERTIES)) {
                continue;
            }

            $properties[$property->getName()] = $property;
        }

        foreach (array_intersect_key($data, $properties) as $name => $value) {
            $this->__modified[$name] = true;
            $properties[$name]->setValue($this, $value);
        }

        return $this;
    }

    public function toArray(bool $recursive = false): array
    {
        $properties = [];

        foreach ($this->__reflection->getProperties() as $property) {
            if ($property->isStatic()) {
                continue;
            }

            if ($property->getName() === '__reflection') {
                continue;
            }

            if (in_array($property->getName(), static::SKIPPED_PROPERTIES)) {
                continue;
            }

            $properties[$property->getName()] = $property->isInitialized($this) ? $property->getValue($this) : null;
        }

        return $properties;
    }

    public function modifiedToArray(bool $recursive = false): array
    {
        $reflection = new ReflectionObject($this);

        $properties = [];

        foreach ($reflection->getProperties() as $property) {
            if ($property->isStatic()) {
                continue;
            }

            if (!$this->__modified[$property->getName()]) {
                continue;
            }

            $properties[$property->getName()] = $property->isInitialized($this) ? $property->getValue($this) : null;
        }

        return $properties;
    }

    public function toJson(bool $pretty = false): string
    {
        return json_encode($this->toArray(true), $pretty ? JSON_PRETTY_PRINT : 0);
    }
}
