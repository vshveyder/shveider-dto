<?php

namespace ShveiderDto\Plugins;

use ReflectionClass;
use ShveiderDto\AbstractConfigurableTransfer;
use ShveiderDto\AbstractReflectionTransfer;
use ShveiderDto\AbstractTransfer;
use ShveiderDto\Constants;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\AbstractDtoClass;
use ShveiderDto\ShveiderDtoExpanderPluginsInterface;
use ShveiderDto\AbstractCachedTransfer;

class RegisteredVarsShveiderDtoExpanderPlugin implements ShveiderDtoExpanderPluginsInterface
{
    public function expand(ReflectionClass $reflectionClass, GenerateDTOConfig $config, AbstractDtoClass $abstractDtoObject): AbstractDtoClass
    {
        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->isPrivate()) {
                continue;
            }

            if (in_array($property->getName(), $this->getSkippedProperties())) {
                continue;
            }

            $abstractDtoObject->addRegisteredVar($property->getName());

            if (!class_exists('\\' . $property->getType()->getName())) {
                continue;
            }

            $parentClass = get_parent_class('\\' . $property->getType()->getName());

            if (is_a($parentClass, AbstractTransfer::class) || in_array($parentClass, [AbstractTransfer::class, AbstractReflectionTransfer::class, AbstractCachedTransfer::class, AbstractConfigurableTransfer::class])) {
                $abstractDtoObject
                    ->addRegisteredTransfer($property->getName(), $property->getType()->getName());
            }

            if ($parentClass === \ArrayObject::class || $property->getType()->getName() === \ArrayObject::class) {
                $abstractDtoObject
                    ->addRegisteredArrayObject($property->getName(), $property->getType()->getName());
            }
        }

        return $abstractDtoObject;
    }

    protected function getSkippedProperties(): array
    {
        return Constants::SHARED_SKIPPED_PROPERTIES;
    }
}