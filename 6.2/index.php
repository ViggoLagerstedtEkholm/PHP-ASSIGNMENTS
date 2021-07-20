<?php

if(isset($_POST["push_button"]))
{
  $Name = $_POST["name"];
  $Email = $_POST["email"];
  $Website = $_POST["homepage"];
  $Comment = $_POST["comment"];

  $Name = strip_tags($Name);
  $Email = strip_tags($Email);
  $Website = strip_tags($Website);
  $Comment = strip_tags($Comment);

  insert($Name, $Email, $Website, $Comment);

  getUsers();
}else{
  getUsers();
}

//Class that will represent our user.
class User {
  private $ID;
  private $name;
  private $email;
  private $website;
  private $comment;
  private $date;
  private $time;

  function __construct($name, $email, $website, $comment, $date, $time) {
    $this->name = $name;
    $this->email = $email;
    $this->website = $website;
    $this->comment = $comment;
    $this->date = $date;
    $this->time = $time;
  }

  function get_ID(){
    return $this->ID;
  }

  function set_ID($ID){
    $this->ID = $ID;
  }

  function get_name(){
    return $this->name;
  }

  function get_email(){
    return $this->email;
  }

  function get_website(){
    return $this->website;
  }

  function get_comment(){
    return $this->comment;
  }

  function get_date(){
    return $this->date;
  }

  function get_time(){
    return $this->time;
  }
}

//Get users from the database.
function getUsers(){
  $conn = connect_database();

  if($conn->connect_error){
    die('Connection Failed: ' . $conn->connect_error);
  }else{
     $sql = "SELECT * FROM `user`";
     $result = $conn->query($sql);
     $pieces = getPieces();
     echo $pieces[0];

     if($result->num_rows > 0){
       //Update HTML document wthi the data.
       $i = 1;
       //Loop through all rows.
       while($row = $result->fetch_assoc())
       {
         $ID = $row["ID"];
         $Name = $row["Name"];
         $Email = $row["Email"];
         $Website = $row["Website"];
         $Comment = $row["Comment"];
         $Date = $row["Date"];
         $Time = $row["Time"];

         print_entry($pieces[1], new User($Name, $Email, $Website, $Comment, $Date, $Time), $i++);
       }
     }else{
       //Update HTML document with empty text.
       echo "No entries found in database.";
     }
     echo $pieces[2];
     $conn->close();
  }
}

//Print users.
function print_entry($piece, $user, $index){
  //Replace and print.
  $temp = str_replace('---no---', $index, $piece);
  $temp = str_replace('---time---', $user->get_date() . " " . $user->get_time(), $temp);
  $temp = str_replace('---name---', $user->get_name(), $temp);
  $temp = str_replace('---homepage---', $user->get_website(), $temp);
  $temp = str_replace('---email---', $user->get_email(), $temp);
  $temp = str_replace('---comment---', $user->get_comment(), $temp);
  echo $temp;
}

//Get the segments we want to modify.
function getPieces(){
  //Get the segments from the html file we want to modify.
  $html = file_get_contents("form.html");
  return explode("<!--===entries===-->", $html);
}

function insert($Name, $Email, $Website, $Comment){
  $conn = connect_database();

  if($conn->connect_error){
    die('Connection Failed: ' . $conn->connect_error);
  }else{
    //Use prepared statements to avoid SQL-injections.
    $stmt = $conn->prepare("insert into user(Name, Email, Website, Comment, Date, Time)
                            values (?,?,?,?,?,?)");

     //Gather the date and time.
     date_default_timezone_set("Europe/Stockholm");
     $Date = date("Y-m-d",time());
     $Time = date("h:i:s");
     //Bind the variables to the prepared statement.
     $stmt->bind_param("ssssss", $Name, $Email, $Website, $Comment, $Date, $Time);
     //Execute the query.
     $stmt->execute();

     $stmt->close();
     $conn->close();
  }
}

//Return a mysqli database instance. (I'm using the localhost database and not the DSV database server).
function connect_database(){
  return new mysqli('localhost', 'root', '', '6.2');
}
?>
