<?php

namespace ShveiderDto\Plugins;

use ReflectionClass;
use ReflectionProperty;
use ShveiderDto\ShveiderDtoExpanderPluginsInterface;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Helpers\GetTypeTrait;
use ShveiderDto\Model\Code\Method;
use ShveiderDto\Model\Code\DtoTrait;

class GetSetMethodShveiderDtoExpanderPlugin implements ShveiderDtoExpanderPluginsInterface
{
    use GetTypeTrait;

    public function expand(
        ReflectionClass   $reflectionClass,
        GenerateDTOConfig $config,
        DtoTrait          $traitGenerator
    ): DtoTrait {
        foreach ($reflectionClass->getProperties() as $property) {
            if (in_array($property->getName(), ['__modified', '__registered_vars', '__registered_transfers'])) {
                continue;
            }

            $this->expandByPropertyGet($property, $traitGenerator);
            $this->expandByPropertySet($property, $traitGenerator);
        }

        return $traitGenerator;
    }

    protected function expandByPropertyGet(ReflectionProperty $reflectionProperty, DtoTrait $traitGenerator): void
    {
        $propertyName = $reflectionProperty->getName();
        $methodName = 'get' . ucfirst($propertyName);
        $type = $this->getPhpType($reflectionProperty);

        $methodGenerator = $this->createMethodGenerator($methodName, $type);
        $methodGenerator->insertRaw("return \$this->$propertyName;");

        $traitGenerator->addMethod($methodName, $methodGenerator);
    }

    protected function expandByPropertySet(ReflectionProperty $reflectionProperty, DtoTrait $traitGenerator): void
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
}
