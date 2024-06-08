<?php

namespace ShveiderDto\Plugins;

use ReflectionClass;
use ShveiderDto\AbstractReflectionTransfer;
use ShveiderDto\AbstractTransfer;
use ShveiderDto\DataTransferObjectInterface;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\AbstractDtoDtoClass;
use ShveiderDto\ShveiderDtoExpanderPluginsInterface;
use ShveiderDto\SVTransfer;

class RegisteredVarsShveiderDtoExpanderPlugin implements ShveiderDtoExpanderPluginsInterface
{
    public function expand(ReflectionClass $reflectionClass, GenerateDTOConfig $config, AbstractDtoDtoClass $traitGenerator): AbstractDtoDtoClass
    {
        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->isPrivate()) {
                continue;
            }

            if (in_array($property->getName(), $this->getSkippedProperties())) {
                continue;
            }

            $traitGenerator->addRegisteredVar($property->getName());

            if (!class_exists('\\' . $property->getType()->getName())) {
                continue;
            }

            $parentClass = get_parent_class('\\' . $property->getType()->getName());

            if (in_array($parentClass, [AbstractTransfer::class, AbstractReflectionTransfer::class, SVTransfer::class])) {
                $traitGenerator
                    ->addRegisteredTransfer($property->getName(), '\\' . $property->getType()->getName());
            }

            if ($parentClass === \ArrayObject::class || $property->getType()->getName() === \ArrayObject::class) {
                $traitGenerator
                    ->addRegisteredArrayObject($property->getName(), '\\' . $property->getType()->getName());
            }
        }

        return $traitGenerator;
    }

    protected function getSkippedProperties(): array
    {
        return DataTransferObjectInterface::SKIPPED_PROPERTIES;
    }
}