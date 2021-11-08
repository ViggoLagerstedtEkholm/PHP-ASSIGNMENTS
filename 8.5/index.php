<?php
/**
 * @author Viggo Lagerstedt Elholm
 */
try {
    $session_id = generateRandom();
    //Get the html file that we will be modifying.
    $html = file_get_contents("example.html");
    $cookieName = "session_store_cookie";
    //Replace the ---session-id-secure--- with the actual $session_id.
    $html = str_replace('---session-id-secure---', $session_id, $html);
    //Create cookie with some attributes.
    //When you display the cookie in the browser the following message will follow: "Endast säkra anslutningar på samma webbplats", this means only secure connections (SSL) on the same website.
    $arr_cookie_options = array (
        'expires' => time() + 10800, //3 hours
        'path' => '/',
        'domain' => 'localhost',
        'secure' => true, //Use secure (HTTPS), this will make sure it only works over a secure HTTPS connection.
        'httponly' => false,
    );
    //Set the cookie name and value + the attributes in the array.
    setcookie($cookieName, $session_id, $arr_cookie_options);
    //Return the example.html
    echo $html;
} catch (Exception $e) {
}

/**
 * Generate random int
 * @throws Exception
 */
function generateRandom(): int
{
    return random_int(100000, 999999);
}
