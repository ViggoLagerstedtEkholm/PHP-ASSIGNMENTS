<?php
/**
 * The result of my search will not match 1-1 to the example, this is probably because we use different ways of matching
 * the keyword in the html page.
 *
 * For example: the word Stockholm in a HTML page will not match with stockholm because of the capital S.
 * Make sure you have this in mind when testing the search. I assumed I could modify this requirement myself,
 * i just want to be clear.
 *
 * It can take a while to load all results. The recommended depth is 2, more than this and you will need to wait for quite a while...
 *
 * I developed my program to do haystack and needle matches (https://www.php.net/manual/en/function.str-contains.php).
 * I use a recursive algorithm.
 * @author Viggo Lagerstedt Ekholm
 */

header('Content-type: text/plain');

echo "Sökspindel";

$URL = $_POST["url"];
$word = $_POST["word"];
$target_depth = $_POST["depth"];

$index = 1;
$total_links = 0;
$start_depth = 0;
$sites_with_word = array();
$sites_without_word = array();

//Start the recursive call with 0 depth.
perform_search($URL, $start_depth, $target_depth, $word, $index);
print_summary($word, $sites_with_word, $sites_without_word);

/**
 * Recursive function that will reach the base case when the given depth value is reached.
 * @param string $URL
 * @param int $depthValue
 * @param int $target_depth
 * @param string $word
 * @param int $index
 */
function perform_search(string $URL, int $depthValue, int $target_depth, string $word, int $index)
{
    //Base case
    if ($depthValue == $target_depth) {
        return;
    }

    echo "\nLevel: " . ($depthValue + 1) . ", link: " . $index . "\n";

    //Read the website into a string.
    $html = @file_get_contents($URL);

    //Did we manage to read the URL?
    if ($html !== false) {
        //Check if the given HTML file contains given word. (Haystack and needle).
        if (str_contains($html, $word)) {
            echo " Y: " . $URL . "\n";
            global $sites_with_word;
            $sites_with_word[] = $URL;
        } else {
            echo " N: " . $URL . "\n";
            global $sites_without_word;
            $sites_without_word[] = $URL;
        }

        //Parse the html file so that we can get all links from the HTML file.
        $dom = new DOMDocument;
        @$dom->loadHTML($html);
        $links = $dom->getElementsByTagName('a');

        $foundLinks = getAllLinks($links, $URL);
        //Find all unique URL's.
        $uniqueLinks = array_unique($foundLinks);

        echo " Det finns " . count($uniqueLinks) . " länkar på " . $URL;

        //Loop through every link and perform a recursive call.
        foreach ($uniqueLinks as $finalLink) {
            //Check if we should echo all the links in this current HTML file.
            if (isset($_POST["display_links"]) && $_POST["display_links"] == "Yes") {
                echo $finalLink . "\n";
            }
            //Recursive call for each link in this website.
            perform_search($finalLink, $depthValue + 1, $target_depth, $word, ++$index);
        }
    } else {
        echo "<// WARNING: >\n";
        echo "Can't read URL! (This happened to me when I tried to reach LinkedIn, they don't allow access with file_get_contents())\n";
        echo "<// WARNING: >";
    }
}

/**
 * Method prints all websites with the word and all websites without.
 * @param string $word
 * @param array $sites_with_word
 * @param array $sites_without_word
 */
function print_summary(string $word, array $sites_with_word, array $sites_without_word)
{
    echo "\n";
    echo "\nResults \n";
    echo "\nStatus of word: " . $word . "\n";
    echo "Sites with the word (" . count($sites_with_word) . ")\n";

    foreach ($sites_with_word as $site) {
        echo $site . "\n";
    }

    echo "\n\nSites without the word (" . count($sites_without_word) . ")\n";

    foreach ($sites_without_word as $site) {
        echo $site . "\n";
    }
}


/**
 * Gets all the links in the HTML file and returns an array with them.
 * @param DOMNodeList $links
 * @param string $URL
 * @return array
 */
function getAllLinks(DOMNodeList $links, string $URL): array
{
    $finalLinks = array();
    foreach ($links as $link) {
        $value = $link->getAttribute('href');
        $str = ltrim($value, '/');

        if (!str_starts_with($str, 'www')) {
            if (str_starts_with($str, 'http') || str_starts_with($str, 'https')) {
                $finalLinks[] = $str;
            } else {
                $finalLinks[] = $URL . $str;
            }
        }
    }
    return $finalLinks;
}
