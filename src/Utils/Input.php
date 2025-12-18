<?php

namespace Utils;

final class Input
{
    public static function positiveInt(array $source, string $key): ?int
    {
        if (!isset($source[$key])) {
            return null;
        }

        $value = filter_var($source[$key], FILTER_VALIDATE_INT);
        return ($value !== false && $value > 0) ? $value : null;
    }
}