<?php

class Validate
{
    public static function hasValues(array $required): bool
    {
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                return false;
            }
        }
        return true;
    }
}