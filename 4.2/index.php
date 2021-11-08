<?php
/**
 * This script creates a random session ID and creates a cookie with this session ID. After 3 hours the cookie expires.
 * @author Viggo Lagerstedt Ekholm
 */

try {
    $session_id = generateRandom();
    $html = file_get_contents("form.html");
    $cookieName = "session_store_cookie";
    setcookie($cookieName, $session_id, time() + 10800, "/"); //Create cookie with the session ID stored for 3 hours.
    $html = str_replace('---session-id---', $session_id, $html);
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
