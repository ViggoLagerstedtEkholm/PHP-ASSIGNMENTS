<?php
/**
 *  This script iterates over the POST and GET arrays and prints the HTTP header type followed by the key to value pair.
 *  @author Viggo Lagerstedt Ekholm
 */

//Set return header as text/plain.
header('Content-type: text/plain');

//Loop through the GET array and echo the HTTP header type followed by the key to value. (POST)
foreach ($_POST as $key => $value) {
    echo "METHOD TYPE: POST \n";
    echo $key . ": " . $value . "\n";
    echo "\n";
}

//Loop through the GET array and echo the HTTP header type followed by the key to value. (GET)
foreach ($_GET as $key => $value) {
    echo "METHOD TYPE: GET \n";
    echo $key . ": " . $value . "\n";
    echo "\n";
}
