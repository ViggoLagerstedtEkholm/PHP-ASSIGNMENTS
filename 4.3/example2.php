<?php
header('Content-type: text/plain');

if(isset($_GET["name"])){
  echo "name: " . $_GET["name"] . "\n";
}

echo "session-id: " . $_COOKIE["session_store_cookie"] . "\n";

if(isset($_GET["button"])){
  echo "button = " . $_GET["button"];
}
