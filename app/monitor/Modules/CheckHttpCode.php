<?php

namespace Monitor\Modules;

class CheckHttpCode
{
    /**
     * Match $compared to code
     *
     * @param $code
     * @param $matched
     * @return bool
     */
    public static function match(string $compared, string $code)
    {
        if ($code === $compared) {
            return true;
        }
        return false;
    }
}