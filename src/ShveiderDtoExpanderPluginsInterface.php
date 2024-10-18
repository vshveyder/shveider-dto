<?php

namespace ShveiderDto;

use ReflectionClass;
use ShveiderDto\Model\Code\AbstractDtoClass;

interface ShveiderDtoExpanderPluginsInterface
{
    public function expand(
        ReflectionClass   $reflectionClass,
        GenerateDTOConfig $config,
        AbstractDtoClass  $abstractDtoObject
    ): AbstractDtoClass;
}
