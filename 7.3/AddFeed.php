<?php
require_once('FeedHandler.php');
require_once('Validate.php');
require_once('FeedConfig.php');

$feedHandler = new Feedhandler();

$required = array('URL', 'display_limit');

if (Validate::hasValues($required)) {
    $URL = $_POST["URL"];
    $display_limit = $_POST["display_limit"];

    $feedConfig = new FeedConfig();
    $feedConfig->setDisplayLimit($display_limit);
    $feedConfig->setURL($URL);

    $feedHandler->addFeed($feedConfig); //Add
    header('location: index.php');
}