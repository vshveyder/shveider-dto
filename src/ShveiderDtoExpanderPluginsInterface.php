<?php

namespace ShveiderDto;

use ReflectionClass;
use ShveiderDto\Model\Code\DtoTrait;

interface ShveiderDtoExpanderPluginsInterface
{
    public function expand(
        ReflectionClass   $reflectionClass,
        GenerateDTOConfig $config,
        DtoTrait          $traitGenerator
    ): DtoTrait;
}
