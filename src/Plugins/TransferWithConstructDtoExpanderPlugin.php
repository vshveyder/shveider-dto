<?php declare(strict_types=1);

namespace ShveiderDto\Plugins;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use ShveiderDto\AbstractTransfer;
use ShveiderDto\Attributes\ValueWithConstruct;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\AbstractDtoClass;
use ShveiderDto\ShveiderDtoExpanderPluginsInterface;

class TransferWithConstructDtoExpanderPlugin implements ShveiderDtoExpanderPluginsInterface
{
    public function expand(ReflectionClass $reflectionClass, GenerateDTOConfig $config, AbstractDtoClass $abstractDtoObject): AbstractDtoClass
    {
        foreach ($reflectionClass->getProperties() as $property) {
            $attributes = $property->getAttributes(ValueWithConstruct::class);

            if (!empty($attributes)) {
                $abstractDtoObject->addRegisteredValueWithConstruct(ltrim($property->getName(), '\\'), $this->getConstructorParams($property));
            }
        }

        return $abstractDtoObject;
    }

    protected function getConstructorParams(ReflectionProperty $property): array
    {
        $type = $property->getType();
        if (!$type instanceof ReflectionNamedType) {
            return [];
        }

        $className = $type->getName();

        if (class_exists($className)) {
            $reflectionClass = new ReflectionClass($className);
            $constructor = $reflectionClass->getConstructor();

            if ($constructor) {
                return $this->isDataTransferObject($reflectionClass)
                    ? array_map(fn (ReflectionParameter $param) => $param->getName(), $constructor->getParameters())
                    : array_merge(
                        [ltrim($reflectionClass->getName(), '\\')],
                        array_map(fn (ReflectionParameter $param) => $param->getName(), $constructor->getParameters())
                    );
            }
        }

        return [];
    }

    protected function isDataTransferObject(ReflectionClass $reflectionClass): bool
    {
        $parent = $reflectionClass->getParentClass();

        if (!$parent) {
            return false;
        }

        if ($parent->getName() === AbstractTransfer::class) {
            return true;
        }

        if (is_a($parent, ReflectionClass::class)) {
            return $this->isDataTransferObject($parent);
        }

        return false;
    }
}