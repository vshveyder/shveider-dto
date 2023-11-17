<?php

namespace ShveiderDto\Plugins;

use ReflectionClass;
use ReflectionProperty;
use ShveiderDto\Attributes\Validate;
use ShveiderDto\ShveiderDtoExpanderPluginsInterface;
use ShveiderDto\GenerateDTOConfig;
use ShveiderDto\Model\Code\DtoTrait;

/**
 * NOT IMPLEMENTED
 */
class ValidateShveiderDtoExpanderPlugin implements ShveiderDtoExpanderPluginsInterface
{
    public function expand(
        ReflectionClass   $reflectionClass,
        GenerateDTOConfig $config,
        DtoTrait          $traitGenerator
    ): DtoTrait {
        $matches = [];

        foreach ($reflectionClass->getProperties() as $property) {
            $attributes = $property->getAttributes(Validate::class);

            if (!empty($attributes)) {
                $matches[] = $this->getRulesByProperty($property, $traitGenerator);
            }
        }

        return $traitGenerator;
    }

    private function getRulesByProperty(ReflectionProperty $property, DtoTrait $traitGenerator): string
    {
        /** @var \ShveiderDto\Attributes\Validate $instance */
        $instance = $property->getAttributes(Validate::class)[0];
        $propertyName = $property->getName();
        $rules = [];

        if ($instance->required === true) {
            $rules[] = '!empty($v)';
        }

        if ($instance->minLength !== null) {
            $rules[] = $property->getType() === 'array'
                ? 'count($v) >= ' . $instance->minLength
                : 'strlen($v) >= ' . $instance->minLength;
        }

        if ($instance->maxLength !== null) {
            $rules[] = $property->getType()
                ? 'count($v) <= ' . $instance->maxLength
                : 'strlen($v) <= ' . $instance->maxLength;
        }

        return "'$propertyName' => " . implode('&&', $rules);
    }

    private function generateValidateMethod(string $rules): string
    {
        return <<<PHP
    public function isValidFromArray(array \$data): bool { \$isValid = true; foreach (\$data as \$key => \$v) {\$isValid = \$isValid && match (\$key) { $rules default => true };}return \$isValid;}
PHP;
    }
}