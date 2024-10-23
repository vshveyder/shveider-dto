<?php declare(strict_types=1);

namespace ShveiderDto;

use BadMethodCallException;

abstract class AbstractCastDynamicTransfer extends AbstractCastTransfer
{
    private array $__methods = [];

    public function __call(string $name, array $arguments)
    {
        if (empty($this->__methods)) {
            foreach ($this->getClassVars() as $classVar) {
                $this->__methods['get']['get' . ucfirst($classVar)] = $classVar;
                $this->__methods['set']['set' . ucfirst($classVar)] = $classVar;
            }
        }

        if (isset($this->__methods['get'][$name])) {
            $propertyName = $this->__methods['get'][$name];

            return $this->$propertyName;
        }

        if (isset($this->__methods['set'][$name])) {
            $propertyName = $this->__methods['get'][$name];
            $value = $arguments[0] ?? null;

            $this->modify($propertyName)->$propertyName =
                is_array($value) ? $this->getValueFromArray($value, $name) : $value;

            return $this;
        }

        throw new BadMethodCallException($name);
    }
}
