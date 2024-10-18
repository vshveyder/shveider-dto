<?php declare(strict_types=1);

namespace ShveiderDto\Plugins;

use ReflectionAttribute;
use ReflectionClass;
use ShveiderDto\Attributes\TransferCache;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\AbstractDtoClass;
use ShveiderDto\ShveiderDtoExpanderPluginsInterface;

class TransferCacheDtoExpanderPlugin implements ShveiderDtoExpanderPluginsInterface
{
    public function expand(ReflectionClass $reflectionClass, GenerateDTOConfig $config, AbstractDtoClass $abstractDtoObject): AbstractDtoClass
    {
        if ($attribute = $this->findWithParentClass($reflectionClass)) {
            $abstractDtoObject->setCacheClass($attribute->newInstance()->name);
        }

        return $abstractDtoObject;
    }

    public function findWithParentClass(ReflectionClass $class): ?ReflectionAttribute
    {
        $attributes = $class->getAttributes(TransferCache::class);

        if (!empty($attributes)) {
            return $attributes[0];
        }

        if ($class->getParentClass()) {
            return $this->findWithParentClass($class->getParentClass());
        }

        return null;
    }
}