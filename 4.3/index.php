<?php
session_start();
$html = file_get_contents("form.html");
$cookieName = "session_store_cookie";
$session_id = session_id();
setcookie($cookieName, $session_id, time() + 10800, "/");
$html = str_replace('---session-id---', $session_id, $html);
echo $html;
session_regenerate_id(true);
?>
