<?php
$rand = generateRandom();
$html = file_get_contents("form.html");
$html = str_replace('---session-id---', $rand, $html);
echo $html;

$rand = generateRandom();

function generateRandom() {
    return random_int(100000, 999999);
}


?>
