<?php

namespace ShveiderDto\Plugins;

use ReflectionClass;
use ShveiderDto\AbstractReflectionTransfer;
use ShveiderDto\AbstractTransfer;
use ShveiderDto\DataTransferObjectInterface;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\DtoTrait;
use ShveiderDto\ShveiderDtoExpanderPluginsInterface;

class RegisteredVarsShveiderDtoExpanderPlugin implements ShveiderDtoExpanderPluginsInterface
{
    public function expand(ReflectionClass $reflectionClass, GenerateDTOConfig $config, DtoTrait $traitGenerator): DtoTrait
    {
        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->isPrivate()) {
                continue;
            }

            if (in_array($property->getName(), $this->getSkippedProperties())) {
                continue;
            }

            $traitGenerator->addRegisteredVar($property->getName());

            if (
                class_exists('\\' . $property->getType()->getName()) &&
                (get_parent_class('\\' . $property->getType()->getName()) === AbstractTransfer::class ||
                get_parent_class('\\' . $property->getType()->getName()) === AbstractReflectionTransfer::class)
            ) {
                $traitGenerator->addRegisteredTransfer($property->getName(), '\\' . $property->getType()->getName());
            }
        }

        return $traitGenerator;
    }

    protected function getSkippedProperties(): array
    {
        return DataTransferObjectInterface::SKIPPED_PROPERTIES;
    }
}