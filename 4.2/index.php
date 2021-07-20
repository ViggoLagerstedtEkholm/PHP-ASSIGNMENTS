<?php
$session_id = generateRandom();

function generateRandom() {
    return random_int(100000, 999999);
}

$html = file_get_contents("form.html");
$cookieName = "session_store_cookie";
setcookie($cookieName, $session_id, time() + 10800, "/");
$html = str_replace('---session-id---', $session_id, $html);
echo $html;


?>
