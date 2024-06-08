<?php

namespace ShveiderDto\Plugins;

use ReflectionClass;
use ReflectionProperty;
use ShveiderDto\AbstractTransfer;
use ShveiderDto\Attributes\ArrayOf;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\AbstractDtoDtoClass;
use ShveiderDto\Model\Code\Method;
use ShveiderDto\ShveiderDtoExpanderPluginsInterface;
use ShveiderDto\Traits\GetTypeTrait;

class ArrayOfShveiderDtoExpanderPlugin implements ShveiderDtoExpanderPluginsInterface
{
    use GetTypeTrait;

    public function expand(
        ReflectionClass   $reflectionClass,
        GenerateDTOConfig $config,
        AbstractDtoDtoClass $traitGenerator
    ): AbstractDtoDtoClass {
        foreach ($reflectionClass->getProperties() as $property) {
            $attributes = $property->getAttributes(ArrayOf::class);

            if (!empty($attributes)) {
                $this->expandByProperty($property, $traitGenerator);
            }
        }

        return $traitGenerator;
    }

    protected function expandByProperty(ReflectionProperty $reflectionProperty, AbstractDtoDtoClass $dtoTrait): void
    {
        $attributes = $reflectionProperty->getAttributes(ArrayOf::class);
        $attribute = $attributes[0];
        /** @var \ShveiderDto\Attributes\ArrayOf $instance */
        $instance = $attribute->newInstance();

        $this->expandGetAndSetMethods($reflectionProperty, $dtoTrait, $instance);

        if (!$instance->singular) {
            return;
        }

        $propertyName = $reflectionProperty->getName();

        if (!$instance->associative) {
            $this->addDefaultMethods($instance, $propertyName, $dtoTrait);

            return;
        }

        $this->addAssociativeMethods($instance, $propertyName, $dtoTrait);
    }

    protected function addDefaultMethods(ArrayOf $instance, string $propertyName, AbstractDtoDtoClass $traitGenerator): void
    {
        $methodName = 'add' . ucfirst($instance->singular);
        $methodGenerator = $this->createMethod($methodName, [$this->getTypeFromAttributeString($instance->type) . ' $v'], 'static');
        $methodGenerator->insertRaw("\$this->__modified['$propertyName'] = true;");
        $methodGenerator->insertRaw("\$this->{$propertyName}[] = \$v;");
        $methodGenerator->insertRaw("return \$this;");

        $traitGenerator->addMethod($methodName, $methodGenerator);
    }

    protected function addAssociativeMethods(ArrayOf $instance, string $propertyName, AbstractDtoDtoClass $dtoTrait): void
    {
        $singular = $instance->singular ? ucfirst($instance->singular) : 'One' . ucfirst($propertyName);

        $methodName = 'add' . $singular;
        $method = $this->createMethod($methodName, ['string $key', $this->getTypeFromAttributeString($instance->type) . ' $v'], 'static');
        $method->insertRaw("\$this->__modified['$propertyName'] = true;");
        $method->insertRaw("\$this->{$propertyName}[\$key] = \$v;");
        $method->insertRaw("return \$this;");
        $dtoTrait->addMethod($methodName, $method);

        $getMethodName = 'get' . $singular;
        $dtoTrait->addMethod(
            $getMethodName,
            $this->createMethod($getMethodName, ['string $key'], $this->getTypeFromAttributeString($instance->type))
                ->insertRaw("return \$this->{$propertyName}[\$key];")
        );

        $hasMethodName = 'has' . $singular;
        $dtoTrait->addMethod(
            $hasMethodName,
            $this->createMethod($hasMethodName, ['string $key'], 'bool')
                ->insertRaw("return isset(\$this->{$propertyName}[\$key]);")
        );
    }

    protected function expandGetAndSetMethods(ReflectionProperty $reflectionProperty, AbstractDtoDtoClass $traitGenerator, ArrayOf $arrayOf): void
    {
        $type = $this->getTypeFromAttributeString($arrayOf->type);

        if (is_a($type, AbstractTransfer::class, true)) {
            $traitGenerator->addRegisteredArrayTransfer($reflectionProperty->getName(), $type);
        }

        if ($type === 'self') {
            $traitGenerator->addRegisteredArrayTransfer($reflectionProperty->getName(), '\\' . ltrim($reflectionProperty->getDeclaringClass()->getName(), '\\'));
        }

        $traitGenerator
            ->getMethod('get' . ucfirst($reflectionProperty->getName()))
            ?->setPhpDocReturnType("array<$type>");
    }

    protected function createMethod(string $methodName, array $params, string $returnType): Method
    {
        return new Method($methodName, $params, $returnType);
    }
}
