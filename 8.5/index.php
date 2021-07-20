<?php
$session_id = generateRandom();

function generateRandom() {
    return random_int(100000, 999999);
}

//Get the html file that we will be modifying.
$html = file_get_contents("example.html");
$cookieName = "session_store_cookie";
//Replace the ---session-id-secure--- with the actual $session_id.
$html = str_replace('---session-id-secure---', $session_id, $html);
//Create cookie with some attribtes.
//When you display the cookie in the browser the following message will follow: "Endast säkra anslutningar på samma webbplats", this means only secure connections (SSL) on the same website.
$arr_cookie_options = array (
                'expires' => time() + 10800, //3 hours
                'path' => '/',
                'domain' => 'localhost',
                'secure' => true, //Use secure (HTTPS)
                'httponly' => false,
                );
//Set the cookie name and value + the attributes in the array.
setcookie($cookieName, $session_id, $arr_cookie_options);
//Return the example.html
echo $html;
?>
