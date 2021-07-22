<?php
header('Content-type: text/plain');
$URL = $_POST["url"];
$word = $_POST["word"];
$depth = $_POST["depth"];

$index = 1;
$sites_with_word = array();
$sites_without_word = array();

//Start the recusive call with 0 depth.
perform_search($URL, 0, $depth, $word, $index, $sites_with_word, $sites_without_word);

print_summary($word, $sites_with_word, $sites_without_word);

//Recursive function that will reach the base case when the given depth value is reached.
function perform_search($URL, $depthValue, $depth, $word, $index){
  //Base case
  if($depthValue == $depth){
    return;
  }

  echo "\nLevel: " . $depthValue + 1 . ", link: " . $index . "\n";

  //Read the website into a string.
  $html = @file_get_contents($URL);

  //Did we manage to read the URL?
  if($html !== false){
    //Haystack needle method strpos compares the whole html string to the word, we either found the word in the html or not.
    if(strpos($html, $word) !== false){
      echo "the word: " . $word . " exist on: " . $URL . "\n";
      global $sites_with_word;
      $sites_with_word[] = $URL;
    }else{
      echo "the word: " . $word . " does not exist on: " . $URL . "\n";
      global $sites_without_word;
      $sites_without_word[] = $URL;
    }

    //Parse the html file so that we can get all links from the HTML file.
    $dom = new DOMDocument;
    @$dom->loadHTML($html);
    $links = $dom->getElementsByTagName('a');
    $finalLinks = getAllLinks($links, $URL);
    //Find all unique URL's.
    $uniqueLinks = array_unique($finalLinks);

    echo "Det finns " . count($uniqueLinks) . " länkar på " . $URL . "\n\n";

    foreach($uniqueLinks as $finalLink){
      //Recursive call for each link in this website.
      perform_search($finalLink, $depthValue++, $depth, $word, ++$index);
    }
  }else{
    echo "<// WARNING: >\n";
    echo "Can't read URL! (This happened to me when I tried to reach LinkedIn, they don't allow access with file_get_contents())\n";
    echo "<// WARNING: >\n";
  }
}

//Method prints all websites with the word and all websites without.
function print_summary($word, $sites_with_word, $sites_without_word){
  echo "Sites with the word (" . count($sites_with_word) . ")\n";

  foreach($sites_with_word as $site){
    echo $site . "\n";
  }

  echo "\n\nSites without the word (" . count($sites_without_word) . ")\n";

  foreach($sites_without_word as $site){
    echo $site . "\n";
  }
}

//Gets all the links in the HTML file and returns an array with them.
function getAllLinks($links, $URL){
  $finalLinks = array();
  foreach ($links as $link)
  {
      $value = $link->getAttribute('href');
      $str = ltrim($value, '/');

      if(!str_starts_with($str, 'www')){
        if(str_starts_with($str, 'http') || str_starts_with($str, 'https'))
        {
          $finalLinks[] = $str;
        }
        else
        {
          $finalLinks[] = $URL . $str;
        }
      }
  }
  return $finalLinks;
}
