<?php

namespace ShveiderDto\Plugins;

use ReflectionClass;
use ReflectionProperty;
use ShveiderDto\Attributes\ArrayOf;
use ShveiderDto\ShveiderDtoExpanderPluginsInterface;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\MethodGenerator;
use ShveiderDto\Model\Code\TraitGenerator;

class ArrayOfShveiderDtoExpanderPlugin implements ShveiderDtoExpanderPluginsInterface
{
    public function expand(
        ReflectionClass $reflectionClass,
        GenerateDTOConfig $config,
        TraitGenerator $traitGenerator
    ): TraitGenerator {
        foreach ($reflectionClass->getProperties() as $property) {
            $attributes = $property->getAttributes(ArrayOf::class);

            if (!empty($attributes)) {
                $this->expandByProperty($property, $traitGenerator);
            }
        }

        return $traitGenerator;
    }

    private function expandByProperty(ReflectionProperty $reflectionProperty, TraitGenerator $traitGenerator): void
    {
        $attributes = $reflectionProperty->getAttributes(ArrayOf::class);
        $attribute = $attributes[0];
        /** @var \ShveiderDto\Attributes\ArrayOf $instance */
        $instance = $attribute->newInstance();

        $this->expandGetAndSetMethods($reflectionProperty, $traitGenerator, $instance);

        if (!$instance->singular) {
            return;
        }

        $propertyName = $reflectionProperty->getName();
        $methodName = 'add' . ucfirst($instance->singular);

        $methodGenerator = new MethodGenerator($methodName, [$instance->type . ' $v'], 'static');
        $methodGenerator->insertRaw("\$this->$propertyName\[] = \$v;");
        $methodGenerator->insertRaw("return \$this;");

        $traitGenerator->addMethod($methodName, $methodGenerator);
    }

    private function expandGetAndSetMethods(ReflectionProperty $reflectionProperty, TraitGenerator $traitGenerator, ArrayOf $arrayOf): void
    {
        $traitGenerator
            ->getMethod('get' . ucfirst($reflectionProperty->getName()))
            ?->setPhpDocReturnType("array<$arrayOf->type>");
    }
}
