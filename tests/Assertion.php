<?php declare(strict_types=1);

namespace ShveiderDtoTest;

use ShveiderDto\AbstractTransfer;
use ShveiderDto\DataTransferObjectInterface;

readonly class Assertion
{
    public function __construct(private object|array $value, private bool $useMethod)
    {
    }

    public function property(string $property, string $assertion, mixed $equal = null): static
    {
        return $this->assert($property, $assertion, $equal);
    }

    public function propertyNotInitialized(string $objPath, string $property): static
    {
        $obj = $this->get($objPath);
        assert(is_object($obj));
        assert(
            (new \ReflectionObject($obj))->getProperty($property)->isInitialized($obj) === false,
            "$objPath.$property is not initiated"
        );

        return $this;
    }

    public function isArray(?string $property = null): static
    {
        return $this->assert($property ?: '$', 'is array');
    }

    public function
    propEqual(string $property, mixed $v): static
    {
        $this->assert($property, '=', $v);

        return $this;
    }

    public function transfer(?string $property = null): static
    {
        return $this->assert($property || $property === '0' ? $property : '$', 'transfer');
    }

    public function is(string $assertion, mixed $expected = null): static
    {
        return $this->assert('$', $assertion, $expected);
    }

    protected function assert(string $property, string $assertion, mixed $equal = null): static
    {
        return $this->assertValue($this->get($property), $assertion, $equal);
    }

    protected function assertValue(mixed $v, string $assertion, mixed $expected = null): static
    {
        assert(match ($assertion) {
            '=' => $v === $expected,
            '!=' => $v !== $expected,
            'is_a' => is_a($v, $expected),
            'count' => count($v) === $expected,
            'is array', 'array' => is_array($v),
            'null' => $v === null,
            'transfer' => is_a($v, DataTransferObjectInterface::class),
            default => false,
        }, 'Assert that value [' . print_r($v, true) . ']. ' . $assertion . ' ' . $expected);

        return $this;
    }

    protected function get(string $path): mixed
    {
        return $this->_get($this->value, explode('.', $path));
    }

    protected function _get(object|array $obj, array $properties): mixed
    {
        $property = array_shift($properties);
        $last = count($properties) === 0;

        if ($property === '$') {
            return $last ? $this->value : $this->_get($this->value, $properties);
        }

        if (is_array($obj)) {
            return $last ? $obj[$property] : $this->_get($obj[$property], $properties);
        }

        if (str_starts_with($property, '$') && strlen($property) > 2) {
            $property = ltrim($property, '$');

            return $last ? $obj->$property : $this->_get($obj->$property, $properties);
        }

        if ($this->useMethod) {
            $method = 'get' . ucfirst($property);

            return $last ? $obj->$method() : $this->_get($obj->$method(), $properties);
        }

        return $last ? $obj->$property : $this->_get($obj->$property, $properties);
    }
}
