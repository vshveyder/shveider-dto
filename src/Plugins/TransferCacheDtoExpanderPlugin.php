<?php declare(strict_types=1);

namespace ShveiderDto\Plugins;

use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionProperty;
use ShveiderDto\Attributes\TransferCache;
use ShveiderDto\Attributes\ValueWithConstruct;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\AbstractDtoClass;
use ShveiderDto\ShveiderDtoExpanderPluginsInterface;

class TransferCacheDtoExpanderPlugin implements ShveiderDtoExpanderPluginsInterface
{
    public function expand(ReflectionClass $reflectionClass, GenerateDTOConfig $config, AbstractDtoClass $abstractDtoObject): AbstractDtoClass
    {
        $attributes = $reflectionClass->getAttributes(TransferCache::class);

        if (!empty($attributes)) {
            $attribute = $attributes[0];
            $abstractDtoObject->setCacheClass($attribute->newInstance()->name);
        }

        return $abstractDtoObject;
    }
}