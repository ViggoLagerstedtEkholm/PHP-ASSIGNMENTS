<?php
//Declare username and password that the basic authentication should use.
$preset_username = "test";
$preset_password = "12345";

//Get the entered username and password from the $_SERVER global array.
$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];

//Check if the preset username and password match the username and password from the $_SERVER variables.
if (!(($preset_username == $user) && ($preset_password == $pass))) {
  //Show the challange form using WWW-authenticate, here we use "basic realm = "Example".
  header('WWW-Authenticate: Basic realm = "Example"');
  //If the challange failed we reach this header, it will redirect the user with the status code of 401 Unauthorized.
  header('HTTP/1.0 401 Unauthorized');
  //If all else fails we will simply show the user that they are not authorized.
  die ("Not authorized");
}

//If we arrive here that means we succesfully matched the preset username and password with $_SERVER['PHP_AUTH_USER'] and $_SERVER['PHP_AUTH_PW'].
//This means we did not enter the if statement on line 11, we are authorized and we simply print the $user string.
echo "<p>You athenticated as: $user </p>";
