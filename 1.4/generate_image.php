<?php
/**
 * This script will dynamically generate a png file and display the current time and date.
 * After 5 seconds the website will refresh. Look in index.html.
 * @author Viggo Lagerstedt Ekholm
 */

//Get the time and date using the default timezone of Stockholm.
date_default_timezone_set("Europe/Stockholm");
$Date = date("Y-m-d", time());
$Time = date("h:i:s");

$name = "date_and_time.png";

// imagecreate(WIDTH, HEIGHT)
$img = imagecreate(300, 80);

// imagecolorallocate(IMAGE, RED, GREEN, BLUE)
$background = imagecolorallocate($img, 51, 119, 255);
$text_color = imagecolorallocate($img, 255, 255, 255);

//Draw the background.
imagefilledrectangle($img, 0, 0, 150, 80, $background);

//Draw the text using the image and font size/text/color.
imagestring($img, 5, 10, 10, $Date . " " . $Time, $text_color);

//Save the image.
imagepng($img, $name);
imagedestroy($img); //Free image from memory.

//Display the image to the client using image/png and read the file from the server with the given $name variable.
header("Content-Type: image/png");
header("Content-Length: " . filesize($name));
readfile($name);
