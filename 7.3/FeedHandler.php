<?php

class FeedConfig{
  private $ID;
  private $URL;
  private $display_limit;

  function __construct($URL, $display_limit){
    $this->URL = $URL;
    $this->display_limit = $display_limit;
  }

  function SetID($ID){
    $this->ID = $ID;
  }

  function GetID(){
    return $this->ID;
  }

  function GetURL(){
    return $this->URL;
  }

  function GetLimit(){
    return $this->display_limit;
  }
}

class Database{
  private $conn;

  function __construct(){
    $this->conn = $this->connect_database();
  }

  function connect_database(){
    return new mysqli('localhost', 'root', '', '7.3');
  }

  //This method closes the database connection.
  function close(){
    $this->conn->close();
  }

  //This method returns the maximum ID from the feed table.
  function GetMaxID(){
    if($this->conn->connect_error){
      die('Connection Failed: ' . $this->conn->connect_error);
    }else{
       $sql = "SELECT MAX(ID) FROM feed";
       $result = $this->conn->query($sql)->fetch_assoc();
       return $result;
    }
    return 0;
  }

  //This method deletes a feed with a given ID.
  function DeleteFeed($ID){
    if($this->conn->connect_error){
        die('Connection Failed: ' . $conn->connect_error);
      }else{
        //Use prepared statements to avoid SQL-injections.
        $stmt = $this->conn->prepare("delete from feed where ID=?");
         $stmt->bind_param("i", $ID);
         $stmt->execute();
         $stmt->close();
      }
  }

  //This method creates a new feed with a specified feed configuration.
  function SetFeed($config){
    if($this->conn->connect_error){
        die('Connection Failed: ' . $conn->connect_error);
      }else{
        //Use prepared statements to avoid SQL-injections.
        $stmt = $this->conn->prepare("insert into feed(URL, displayCount)
                                values (?,?)");

         $URL = $config->GetURL();
         $Limit = $config->GetLimit();
         $stmt->bind_param("si", $URL, $Limit);
         $stmt->execute();
         $stmt->close();
      }
  }

  //This method returns an array of feed configurations.
  function GetFeeds(){
    $feeds = array();

    if($this->conn->connect_error){
      die('Connection Failed: ' . $this->conn->connect_error);
    }else{
       $sql = "SELECT * FROM feed";
       $result = $this->conn->query($sql);

       if($result->num_rows > 0){
         $i = 1;
         //Loop through all rows.
         while($row = $result->fetch_assoc())
         {
           $ID = $row["ID"];
           $URL = $row["URL"];
           $display_limit = $row["displayCount"];

           $config = new FeedConfig($URL, $display_limit);
           $config->SetID($ID);
           $feeds[] = $config;
           }
       }else{
         //Update HTML document with empty text.
         echo "\nNo entries found in database.";
       }
    }

    return $feeds;
  }
}
?>
