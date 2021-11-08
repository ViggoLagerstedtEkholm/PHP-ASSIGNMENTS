<?php

/**
 * This script will replace the session id placeholder with randomly generated id.
 * @author Viggo Lagerstedt Ekholm
 */

try {
    $rand = generateRandom();
    $html = file_get_contents("form.html");
    $html = str_replace('---session-id---', $rand, $html);
    echo $html;
} catch (Exception $e) {
    echo "Failed to generate ID!";
}

/**
 * Generate random session ID
 * @throws Exception
 */
function generateRandom(): int
{
    return random_int(100000, 999999);
}
