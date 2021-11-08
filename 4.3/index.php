<?php
/**
 * This script uses the built in session tools.
 * I save the generated session id in a cookie.
 * @author Viggo Lagerstedt Ekholm
 */
session_start(); //Start the session.
session_regenerate_id(); //Regenerate the session ID.

$html = file_get_contents("form.html");
$cookieName = "session_store_cookie";
$session_id = session_id(); //Get the generated session ID.
setcookie($cookieName, $session_id, time() + 10800, "/"); //Create a cookie with the generated session ID that expires in 3 hours.
$html = str_replace('---session-id---', $session_id, $html);
echo $html;
