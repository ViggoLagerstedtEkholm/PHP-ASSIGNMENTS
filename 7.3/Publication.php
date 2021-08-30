<?php

/**
 * Publication class.
 * @author Viggo Lagertedt Ekholm
 */
class Publication
{
    private $site_link;
    private $title;
    private $link;
    private $description;
    private $postDate;
    private $publishDate;

    function __construct($title, $link, $description, $postDate, $publishDate, $site_link)
    {
        $this->title = $title;
        $this->link = $link;
        $this->description = $description;
        $this->postDate = $postDate;
        $this->publishDate = $publishDate;
        $this->site_link = $site_link;
    }

    /**
     * @return mixed
     */
    public function getSiteLink()
    {
        return $this->site_link;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getPostDate()
    {
        return $this->postDate;
    }

    /**
     * @return mixed
     */
    public function getPublishDate()
    {
        return $this->publishDate;
    }

}