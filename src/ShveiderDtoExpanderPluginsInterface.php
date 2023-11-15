<?php

namespace ShveiderDto;

use ReflectionClass;
use ShveiderDto\Model\Code\TraitGenerator;

interface ShveiderDtoExpanderPluginsInterface
{
    public function expand(
        ReflectionClass $reflectionClass,
        GenerateDTOConfig $config,
        TraitGenerator $traitGenerator
    ): TraitGenerator;
}
