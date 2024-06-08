<?php

namespace ShveiderDto\Plugins;

use ReflectionClass;
use ReflectionProperty;
use ShveiderDto\DataTransferObjectInterface;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\AbstractDtoDtoClass;
use ShveiderDto\Model\Code\Method;
use ShveiderDto\ShveiderDtoExpanderPluginsInterface;
use ShveiderDto\Traits\GetTypeTrait;

class GetSetMethodShveiderDtoExpanderPlugin implements ShveiderDtoExpanderPluginsInterface
{
    use GetTypeTrait;

    public function expand(
        ReflectionClass   $reflectionClass,
        GenerateDTOConfig $config,
        AbstractDtoDtoClass $traitGenerator
    ): AbstractDtoDtoClass {
        foreach ($reflectionClass->getProperties() as $property) {
            if ($property->isPrivate()) {
                continue;
            }

            if (in_array($property->getName(), $this->getSkippedProperties())) {
                continue;
            }

            $this->expandByPropertyGet($property, $traitGenerator);
            $this->expandByPropertySet($property, $traitGenerator);
        }

        return $traitGenerator;
    }

    protected function expandByPropertyGet(ReflectionProperty $reflectionProperty, AbstractDtoDtoClass $traitGenerator): void
    {
        $propertyName = $reflectionProperty->getName();
        $methodName = 'get' . ucfirst($propertyName);
        $type = $this->getPhpType($reflectionProperty);

        $methodGenerator = $this->createMethodGenerator($methodName, $type);
        $methodGenerator->insertRaw("return \$this->$propertyName;");

        $traitGenerator->addMethod($methodName, $methodGenerator);
    }

    protected function expandByPropertySet(ReflectionProperty $reflectionProperty, AbstractDtoDtoClass $traitGenerator): void
    {
        if ($reflectionProperty->isReadOnly()) {
            return;
        }

        $propertyName = $reflectionProperty->getName();
        $methodName = 'set' . ucfirst($propertyName);
        $type = $this->getPhpType($reflectionProperty);

        $methodGenerator = new Method($methodName, ["$type \$v"], 'static');
        $methodGenerator->insertRaw("\$this->__modified['$propertyName'] = true;");
        $methodGenerator->insertRaw("\$this->$propertyName = \$v;");
        $methodGenerator->insertRaw("return \$this;");

        $traitGenerator->addMethod($methodName, $methodGenerator);
    }

    protected function createMethodGenerator(string $methodName, string $type): Method
    {
        return new Method($methodName, [], $type);
    }

    protected function getSkippedProperties(): array
    {
        return DataTransferObjectInterface::SKIPPED_PROPERTIES;
    }
}
