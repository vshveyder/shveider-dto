<?php

namespace ShveiderDto\Plugins;

use ReflectionClass;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\TraitGenerator;
use ShveiderDto\ShveiderDtoExpanderPluginsInterface;

class RegisteredVarsShveiderDtoExpanderPlugin implements ShveiderDtoExpanderPluginsInterface
{

    public function expand(ReflectionClass $reflectionClass, GenerateDTOConfig $config, TraitGenerator $traitGenerator): TraitGenerator
    {
        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->getName() === 'modified') {
                continue;
            }

            if ($property->getName() === 'registered_vars') {
                continue;
            }

            $traitGenerator->addRegisteredVar($property->getName());
        }

        return $traitGenerator;
    }
}