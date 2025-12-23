<?php

namespace Goapptiv\Pulse;

class Utils
{
    /**
     * Map variables
     */
    public static function mapVariables($arr1, $arr2)
    {
        $result = [];
        foreach ($arr1 as $key => $value) {
            if (isset($arr2[$value])) {
                $result[$key] = $arr2[$value];
            }
        }
        return $result;
    }
}