<?php
$pieces = getPieces("<!--===entries===-->");
$inputs = getPieces("<!--===forms===-->");

//Specify imports.
require_once('./Publication.php');
require_once('./FeedHandler.php');
require_once('./Validate.php');

//Create database instance.
$database = new Database;

//Get maximum ID from datbase.
$MAXID = $database->GetMaxID();

//Check if the user clicked "Remove" on any feeds.
for($i = 0; $i <= $MAXID["MAX(ID)"]; $i++){
    if(isset($_POST[$i])){
        //Delete the feed if it matches the ID of the clicked feed.
        $database->DeleteFeed($i);
    }
}

echo $inputs[0];

$required = array('URL', 'display_limit');
//Check if we have a feed URL and a display limit, if this is true the user wanted to add a new feed. Else just print the current RSS feeds.
if(hasValues($required)){
  $URL = $_POST["URL"];
  $display_limit = $_POST["display_limit"];

  //Insert new feed.
  $database->SetFeed(new FeedConfig($URL, $display_limit));
  //Print.
  print_feeds($database, $inputs, $pieces);
}
else{
  print_feeds($database, $inputs, $pieces);
}

//Thus method will print everything inside the feed.
function print_feeds($database, $inputs, $pieces){
  //Get the feeds.
  $feeds = $database->GetFeeds();
  //Make sure we received atleast 1 or more feeds.
  if(count($feeds) > 0){
    $index = 0;

    //<form>
    echo $inputs[1];

    //This will loop for every feed in the database, this is where we can see feed and the display limit and remove button. (do this for all feeds).
    foreach($feeds as $config){
      $entry = str_replace('---URL---', $config->GetURL(), $inputs[2]);
      $entry = str_replace('---display_index---', $config->GetLimit(), $entry);
      $entry = str_replace('---ID---', $config->GetID(), $entry);
      $entry = str_replace('---SHOWCASE_INDEX---', $index + 1, $entry);
      echo $entry;
      $index++;
    }

    //</form>
    echo $inputs[3];

    //Print the contents of all the RSS feeds.
    foreach($feeds as $config){
      loadRSS($config->GetURL(), $pieces, $config->GetLimit());
    }
  }
}

//This method takes a URL and a display limit and will load that specific RSS feed and display all content.
function loadRSS($URL, $pieces, $display_limit){
  $IsValidUrl = false;

  //Can we read the XML?
  if(@simplexml_load_file($URL)){
   $feed = simplexml_load_file($URL);
  }else{
   $IsValidUrl = true;
   echo "Invalid RSS feed URL.";
  }

  if(!empty($feed)){
    //Get all information from the XML tags.

     $site = $feed->channel->title;
     $sitelink = $feed->channel->link;

     $site_title = getPieces("<!--===Title===-->");
     $entry = str_replace('Test', $site . " (" .$display_limit . ")", $site_title[1]);
     echo $entry;

     $index = 0;
     foreach ($feed->channel->item as $item)
     {
       if(!($index >= $display_limit)){
         $title = $item->title;
         $link = $item->link;
         $description = strip_tags($item->description);
         $postDate = $item->pubDate;
         $pubDate = date('D, d M Y',strtotime($postDate));

         //Print the content on a new row.
         print_entry($pieces[1], new Publication($title, $link, $description, $postDate, $pubDate, $sitelink));
       }else{
         break;
       }
      $index++;
    }
  }
  else{
    if(!$IsValidUrl){
      echo "<h2>No item found</h2>";
    }
  }
}

function getPieces($target){
  //Get the segments from the html file we want to modify.
  $html = file_get_contents("index.html");
  return explode($target, $html);
}

function print_entry($piece, $feed){
  //Replace and print.
  $temp = str_replace('---title---', $feed->GetTitle(), $piece);
  $temp = str_replace('---link---', $feed->GetSiteLink(), $temp);
  $temp = str_replace('---description---', $feed->GetDescription(), $temp);
  $temp = str_replace('---post_date---', $feed->GetPostDate(), $temp);
  $temp = str_replace('---publish_date---', $feed->GetPublishDate(), $temp);

  echo $temp;
}

//Close database connection.
$database->close();
?>
