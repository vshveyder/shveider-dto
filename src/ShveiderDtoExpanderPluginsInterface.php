<?php

namespace ShveiderDto;

use ReflectionClass;
use ShveiderDto\Model\Code\AbstractDtoDtoClass;

interface ShveiderDtoExpanderPluginsInterface
{
    public function expand(
        ReflectionClass     $reflectionClass,
        GenerateDTOConfig   $config,
        AbstractDtoDtoClass $traitGenerator
    ): AbstractDtoDtoClass;
}
