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
}