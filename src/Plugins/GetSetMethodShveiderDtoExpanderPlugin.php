<?php

namespace ShveiderDto\Plugins;

use ReflectionClass;
use ReflectionProperty;
use ShveiderDto\ShveiderDtoExpanderPluginsInterface;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Helpers\GetTypeTrait;
use ShveiderDto\Model\Code\MethodGenerator;
use ShveiderDto\Model\Code\TraitGenerator;

class GetSetMethodShveiderDtoExpanderPlugin implements ShveiderDtoExpanderPluginsInterface
{
    use GetTypeTrait;

    public function expand(
        ReflectionClass $reflectionClass,
        GenerateDTOConfig $config,
        TraitGenerator $traitGenerator
    ): TraitGenerator {
        foreach ($reflectionClass->getProperties() as $property) {
            if (in_array($property->getName(), ['modified', 'registered_vars'])) {
                continue;
            }

            $this->expandByPropertyGet($property, $traitGenerator);
            $this->expandByPropertySet($property, $traitGenerator);
        }

        return $traitGenerator;
    }

    private function expandByPropertyGet(ReflectionProperty $reflectionProperty, TraitGenerator $traitGenerator): void
    {
        $propertyName = $reflectionProperty->getName();
        $methodName = 'get' . ucfirst($propertyName);
        $type = $this->getPhpType($reflectionProperty);

        $methodGenerator = new MethodGenerator($methodName, [], $type);
        $methodGenerator->insertRaw("return \$this->$propertyName;");

        $traitGenerator->addMethod($methodName, $methodGenerator);
    }

    private function expandByPropertySet(ReflectionProperty $reflectionProperty, TraitGenerator $traitGenerator): void
    {
        if ($reflectionProperty->isReadOnly()) {
            return;
        }

        $propertyName = $reflectionProperty->getName();
        $methodName = 'set' . ucfirst($propertyName);
        $type = $this->getPhpType($reflectionProperty);

        $methodGenerator = new MethodGenerator($methodName, ["$type \$v"], 'static');
        $methodGenerator->insertRaw("\$this->modified['$propertyName'] = true;");
        $methodGenerator->insertRaw("\$this->$propertyName = \$v;");
        $methodGenerator->insertRaw("return \$this;");

        $traitGenerator->addMethod($methodName, $methodGenerator);
    }
}
