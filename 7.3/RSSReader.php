<?php
require_once('Publication.php');
require_once('FeedHandler.php');
require_once('FeedConfig.php');

$feedHandler = new Feedhandler();

/**
 * Get the segments we want to modify.
 * @return false|string[]
 */
function getPieces()
{
    $html = file_get_contents("index.html");
    return explode("<!--===entries===-->", $html);
}

echo getPieces()[0];

//Print feed links and remove button.
foreach($feedHandler->GetSavedFeedConfigs() as $config){
    $temp = str_replace('---URL---', $config->getURL(), getPieces()[1]);
    $temp = str_replace('---display_index---', $config->getDisplayLimit(), $temp);
    echo str_replace('---ID---', $config->getID(), $temp);
}

//Print feed content
foreach($feedHandler->getFeeds() as $feed){
    echo str_replace('---Title---', $feed["title"], getPieces()[2]);

    foreach($feed["publications"] as $publication){
        $temp = str_replace('---title---', $publication->getTitle(), getPieces()[3]);
        $temp = str_replace('---link---', $publication->getLink(), $temp);
        $temp = str_replace('---description---', $publication->getDescription(), $temp);
        $temp = str_replace('---post_date---', $publication->getPostDate(), $temp);
        echo str_replace('---publish_date---', $publication->getPublishDate(), $temp);
    }
}

echo getPieces()[4];


