<?php

/**
 * Validation class.
 * @author Viggo Lagerstedt Ekholm
 */
class Validate
{
    /**
     * Check to see if array has empty values.
     * @param array $required
     * @return bool
     */
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