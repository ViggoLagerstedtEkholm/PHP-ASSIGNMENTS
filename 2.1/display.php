<?php
/**
 * We set the header as text/plain and iterate over the GET array and extract the key -> value pair.
 * @author Viggo Lagerstedt Ekholm
 */

header('Content-type: text/plain');

foreach($_GET as $key => $value){
    echo $key . ": " . $value . "\n";
}
