<?php
require_once('Database.php');

/**
 * Feed handler class.
 * @author Viggo Lagertedt Ekholm
 */
class FeedHandler extends Database
{
    /**
     * Get all the feeds.
     * @return array
     */
    public function getFeeds(): array
    {
        $loadedData = array();
        foreach ($this->GetSavedFeedConfigs() as $config) {
            $loadedData[] = $this->loadRSS($config);
        }

        return $loadedData;
    }

    /**
     * Load a given feed configuration.
     * @param FeedConfig $config
     * @return array
     */
    private function loadRSS(FeedConfig $config): array
    {
        $publicationArray = array();
        $feedTitle = "NOT FOUND";

        if (@simplexml_load_file($config->getURL())) {
            $feed = simplexml_load_file($config->getURL());
        }

        if (!empty($feed)) {
            $feedTitle = $feed->channel->title ?? "Not set!";
            $URL = $feed->channel->link ?? "Not set!";

            $index = 0;
            foreach ($feed->channel->item as $item) {
                if (!($index >= $config->getDisplayLimit())) {
                    $title = $item->title ?? "Not set!";
                    $link = $item->link ?? "Not set!";
                    $description = strip_tags($item->description);
                    $postDate = $item->pubDate;
                    $pubDate = date('D, d M Y', strtotime($postDate));

                    $publicationArray[] = new Publication($title, $link, $description, $postDate, $pubDate, $URL);
                } else {
                    break;
                }
                $index++;
            }
        }

        return [
            "publications" => $publicationArray,
            "title" => $feedTitle
        ];
    }

    /**
     * Get all feed configurations.
     * @return array
     */
    public function GetSavedFeedConfigs(): array
    {
        $feeds = array();

        $sql = "SELECT * FROM feed";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $ID = $row["ID"];
                $URL = $row["URL"];
                $display_limit = $row["displayCount"];

                $feedConfig = new FeedConfig();
                $feedConfig->setDisplayLimit($display_limit);
                $feedConfig->setURL($URL);
                $feedConfig->setID($ID);

                $feeds[] = $feedConfig;
            }
        }

        return $feeds;
    }

    /**
     * Add feed to database.
     * @param FeedConfig $config
     */
    public function addFeed(FeedConfig $config)
    {
        $sql = "insert into feed(URL, displayCount) values (?,?)";
        $stmt = $this->conn->prepare($sql);

        $URL = $config->GetURL();
        $Limit = $config->getDisplayLimit();
        $stmt->bind_param("si", $URL, $Limit);
        $stmt->execute();
        $stmt->close();
    }

    /**
     * Delete feed from database.
     * @param $ID
     */
    public function deleteFeed($ID)
    {
        $stmt = $this->conn->prepare("delete from feed where ID=?");
        $stmt->bind_param("i", $ID);
        $stmt->execute();
        $stmt->close();
    }
}