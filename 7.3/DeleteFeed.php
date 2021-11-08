<?php
require_once('FeedHandler.php');

if (isset($_POST["ID"])) {
    $ID = $_POST["ID"];
    $feedHandler = new FeedHandler();

    $feedHandler->deleteFeed($ID); //Delete
    header('location: index.php');
}