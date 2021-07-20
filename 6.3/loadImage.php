<?php
$conn = connect_database();

if(isset($_GET['id']))
{
  //Get the id from the URL.
  $ID = $_GET['id'];

  //Select the image row from the current user. Limit this to 1 (even though only 1 post can have 1 image, this is just to be safe).
  $sql = "SELECT * FROM image WHERE userID = $ID LIMIT 1";
  $result = $conn->query($sql);

  if($result->num_rows > 0){
    while($row = $result->fetch_assoc())
    {
      $Image = $row["Image"];
      $Mime = $row["MimeType"];
      $User = $row["userID"];

      //Set the header.
      header('Content-Type: ' . $Mime);
      //Display image.
      echo $Image;
    }
  }else{
    //Update HTML document with empty text.
    echo "No entries found in database.";
  }
}

function connect_database(){
  return new mysqli('localhost', 'root', '', '6.3');
}


?>
