<?php declare(strict_types=1);

namespace ShveiderDto;

use AllowDynamicProperties;
use ReflectionObject;

/**
 * Transfer that use reflection object to grab properties.
 * With this class you can add private properties to your dto and use toArray as well.
 */
#[AllowDynamicProperties]
abstract class AbstractReflectionTransfer implements DataTransferObjectInterface
{
    public const SHARED_SKIPPED_PROPERTIES = ['__modified', '__reflection'];

    protected array $__modified = [];

    private ReflectionObject $__reflection;

    public function __construct()
    {
        $this->__reflection = new ReflectionObject($this);
    }

    public function fromArray(array $data): static
    {
        $properties = [];

        foreach ($this->__reflection->getProperties() as $property) {
            if ($property->isStatic() || in_array($property->getName(), static::SHARED_SKIPPED_PROPERTIES)) {
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
            if ($property->isStatic() || in_array($property->getName(), static::SHARED_SKIPPED_PROPERTIES)) {
                continue;
            }

            $name = $property->getName();
            $properties[$name] = $property->isInitialized($this) ? $property->getValue($this) : null;

            if ($properties[$name] && $recursive && is_a($properties[$name], DataTransferObjectInterface::class)) {
                $properties[$name] = $properties[$name]->toArray($recursive);
            }
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

            $name = $property->getName();

            if (!$this->__modified[$name]) {
                continue;
            }

            $properties[$name] = $property->isInitialized($this) ? $property->getValue($this) : null;

            if ($properties[$name] && $recursive && is_a($properties[$name], DataTransferObjectInterface::class)) {
                $properties[$name] = $properties[$name]->toArray($recursive);
            }
        }

        return $properties;
    }

    public function toJson(bool $pretty = false): string
    {
        return json_encode($this->toArray(true), $pretty ? JSON_PRETTY_PRINT : 0);
    }
}
