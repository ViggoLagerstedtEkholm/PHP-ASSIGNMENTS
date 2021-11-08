<?php
/**
 * This script will print the name of the global variable followed by the value.
 * @author Viggo Lagerstedt Ekholm
 */

header('Content-type: text/plain');
//Create array with both SERVER and ENV variables, we will use these to loop the contents.
$globalVariables = array(
    '$_SERVER' => $_SERVER, '$_ENV' => $_ENV,
);

//Loop every global variable array and list all the variables.
foreach ($globalVariables as $globalVariableName => $global) {
    //Print the type of global variable.
    echo $globalVariableName . "\n\n";

    //Go through all attributes in the global variable.
    foreach ($global as $key => $value) {
        echo $key . ':  ' . $value . "\n";
    }
}
