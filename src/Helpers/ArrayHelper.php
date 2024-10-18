<?php

namespace ShveiderDto\Helpers;

class ArrayHelper
{
    public static function shiftMulti(array &$array, array $values): array
    {
        $res = [];

        foreach ($values as $value) {
            if (isset($array[$value])) {
                $res[$value] = $array[$value];
                unset($array[$value]);
            }
        }

        return $res;
    }
}