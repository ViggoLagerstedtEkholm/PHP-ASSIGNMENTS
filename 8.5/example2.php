<?php
header('Content-type: text/plain');

if(isset($_GET["name"])){
  echo "name: " . $_GET["name"] . "\n";
}

//Check if the session_store_cookie is set, display it if it exists.
if(isset($_COOKIE["session_store_cookie"])) {
  echo "session-id: " . $_COOKIE["session_store_cookie"] . "\n";
}

if(isset($_GET["button"])){
  echo "button = " . $_GET["button"];
}
