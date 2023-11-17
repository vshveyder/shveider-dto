<?php

namespace ShveiderDto\Plugins;

use ReflectionClass;
use ReflectionProperty;
use ShveiderDto\AbstractTransfer;
use ShveiderDto\Attributes\ArrayOf;
use ShveiderDto\Helpers\GetTypeTrait;
use ShveiderDto\ShveiderDtoExpanderPluginsInterface;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\Method;
use ShveiderDto\Model\Code\DtoTrait;

class ArrayOfShveiderDtoExpanderPlugin implements ShveiderDtoExpanderPluginsInterface
{
    use GetTypeTrait;

    public function expand(
        ReflectionClass   $reflectionClass,
        GenerateDTOConfig $config,
        DtoTrait          $traitGenerator
    ): DtoTrait {
        foreach ($reflectionClass->getProperties() as $property) {
            $attributes = $property->getAttributes(ArrayOf::class);

            if (!empty($attributes)) {
                $this->expandByProperty($property, $traitGenerator);
            }
        }

        return $traitGenerator;
    }

    protected function expandByProperty(ReflectionProperty $reflectionProperty, DtoTrait $traitGenerator): void
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

        $methodGenerator = $this->createMethodGenerator($methodName, $instance);
        $methodGenerator->insertRaw("\$this->__modified['$propertyName'] = true;");
        $methodGenerator->insertRaw("\$this->{$propertyName}[] = \$v;");
        $methodGenerator->insertRaw("return \$this;");

        $traitGenerator->addMethod($methodName, $methodGenerator);
    }

    protected function expandGetAndSetMethods(ReflectionProperty $reflectionProperty, DtoTrait $traitGenerator, ArrayOf $arrayOf): void
    {
        $type = $this->getTypeFromAttributeString($arrayOf->type);

        if (is_a($type, AbstractTransfer::class, true)) {
            $traitGenerator->addRegisteredArrayTransfer($reflectionProperty->getName(), $type);
        }

        $traitGenerator
            ->getMethod('get' . ucfirst($reflectionProperty->getName()))
            ?->setPhpDocReturnType("array<$type>");
    }

    protected function createMethodGenerator(string $methodName, ArrayOf $instance): Method
    {
        return new Method($methodName, [$this->getTypeFromAttributeString($instance->type) . ' $v'], 'static');
    }
}
