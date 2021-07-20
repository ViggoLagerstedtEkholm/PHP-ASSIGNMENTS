<?php
$globalVariables = array(
    '$_SERVER' => $_SERVER, '$_ENV' => $_ENV,
);

//Fetch the HTML page.
$html = file_get_contents("display.html");
//Use explode to create 3 segmented pieces that we can use to populate our table.
$html_pieces = explode("<!--===xxx===-->", $html);

//First piece will be the table header.
echo $html_pieces[0];

//Loop through all the global variables.
foreach ($globalVariables as $globalVariableName => $global) {
    foreach ($global as $key => $value) {
        //Create temporary variable.
        //We first replace all ---name--- with the key.
        //We then use this newly created string to replace the ---value---.
        //What we have left is 1 row that contains ---name--- -> ---value--- mapping.
         $temp = str_replace('---name---', $key, $html_pieces[1]);
         echo str_replace('---value---', $value, $temp);
    }
}

//Finally print the end of the table.
echo $html_pieces[2];
