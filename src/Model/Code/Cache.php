<?php

namespace ShveiderDto\Model\Code;

class Cache extends AbstractDtoClass
{
    private string $class;

    public function setClass(string $class): void
    {
        $this->class = ltrim($class, '\\');
    }

    public function __toString(): string
    {
        $registeredVarsList = $this->mapArrayToString($this->registeredVars, function ($name, $value) {
            return $this->formatValue($value);
        });

        $transfers = $this->mapArrayToString($this->registeredTransfers, function ($name, $namespace) {
            $name = $this->formatValue($name);
            $namespace = $this->formatValue($namespace);

            return "$name => $namespace";
        });

        $arrayTransfers = $this->mapArrayToString($this->registeredArrayTransfers, function ($name, $namespace) {
            $name = $this->formatValue($name);
            $namespace = $this->formatValue($namespace);

            return "$name => $namespace";
        });

        $valuesWithConstruct = $this->mapArrayToString($this->registeredValuesWithConstruct, function ($name, $values) {
            $name = $this->formatValue($name);

            return "$name=>[" . implode(",", array_map([$this, 'formatValue'], $values)) . "]";
        });

        $class = $this->formatValue($this->class);

        return "$class => [[$registeredVarsList], [$transfers], [$arrayTransfers], [$valuesWithConstruct]]";
    }

    public function addMethod(string $name, Method $method): void
    {
    }

    public function formatValue(string $value): string
    {
        return "'$value'";
    }
}
