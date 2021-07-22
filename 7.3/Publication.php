<?php
class Publication{
  private $site_link;
  private $title;
  private $link;
  private $description;
  private $postDate;
  private $publishDate;

  function __construct($title, $link, $description, $postDate, $publishDate, $site_link){
    $this->title = $title;
    $this->link = $link;
    $this->description = $description;
    $this->postDate = $postDate;
    $this->publishDate = $publishDate;
    $this->site_link = $site_link;
  }

  function GetTitle(){
    return $this->title;
  }

  function GetLink(){
    return $this->link;
  }

  function GetDescription(){
    return $this->description;
  }

  function GetPostDate(){
    return $this->postDate;
  }

  function GetPublishDate(){
    return $this->publishDate;
  }

  function GetSiteLink(){
    return $this->site_link;
  }
}
?>
