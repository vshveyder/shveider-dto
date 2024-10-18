<?php declare(strict_types=1);

namespace ShveiderDto\Traits;

use ReflectionProperty;

trait GetTypeTrait
{
    public function getPhpType(ReflectionProperty $reflectionProperty): string
    {
        $type = $reflectionProperty->getType()->getName();

        if (class_exists('\\' . $reflectionProperty->getType()->getName())) {
            $type = '\\' . $reflectionProperty->getType()->getName();
        }

        if (interface_exists('\\' . $reflectionProperty->getType()->getName())) {
            $type = '\\' . ltrim($reflectionProperty->getType()->getName(), '\\');
        }

        if ($reflectionProperty->getType()->allowsNull() && $reflectionProperty->getType()->getName() !== 'mixed') {
            $type = '?' . $type;
        }

        return $type;
    }

    public function getTypeFromAttributeString(string $type): string
    {
        if (str_contains($type, '\\') || class_exists('\\' . $type)) {
            if (class_exists('\\' . trim($type, '\\'))) {
                $type = '\\' . trim($type, '\\');
            }

            return $type;
        }

        return $type;
    }
}