<?php
/**
 * This script reads the POST that the user provided and sanitizes the input.
 * I will be using the XAMPP local mysqli database server (See the database folder.)
 * @author Viggo Lagerstedt Ekholm
 */

require_once "UserHandler.php";
require_once "User.php";
require_once "Image.php";

$userHandler = new UserHandler();

if (isset($_POST["push_button"])) {
    $Name = $_POST["name"];
    $Email = $_POST["email"];
    $Website = $_POST["homepage"];
    $Comment = $_POST["comment"];

    //Strip tags to prevent code injections.
    $Name = strip_tags($Name);
    $Email = strip_tags($Email);
    $Website = strip_tags($Website);
    $Comment = strip_tags($Comment);

    $user = new User();
    $user->setName($Name);
    $user->setEmail($Email);
    $user->setWebsite($Website);
    $user->setComment($Comment);

    $image = new Image();
    $image->setImage($_FILES['file']);
    $image->setMime($_FILES['file']['type']);

    try {
        $userHandler->insert($user, $image);
    } catch (Throwable $e) {
        echo "Thrown insert error!";
    }
}

$userHandler->get();
