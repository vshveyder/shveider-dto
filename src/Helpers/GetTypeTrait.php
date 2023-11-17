<?php

namespace ShveiderDto\Helpers;

trait GetTypeTrait
{
    public function getPhpType(\ReflectionProperty $reflectionProperty): string
    {
        $type = $reflectionProperty->getType()->getName();

        if (class_exists('\\' . $reflectionProperty->getType()->getName())) {
            $type = '\\' . $reflectionProperty->getType()->getName();
        }

        if ($reflectionProperty->getType()->allowsNull()) {
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