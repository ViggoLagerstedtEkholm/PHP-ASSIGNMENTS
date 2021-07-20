<?php
$pieces = getPieces();
echo $pieces[0];

//Get the segments we want to modify.
function getPieces(){
  //Get the segments from the html file we want to modify.
  $html = file_get_contents("form.html");
  return explode("<!--===entries===-->", $html);
}

function print_entry($piece, $email, $index){
  //Replace and print.
  $temp = str_replace('---no---', $index, $piece);
  $temp = str_replace('---date---', $email->GetDate(), $temp);
  $temp = str_replace('---from---', $email->GetFromAddress(), $temp);
  $temp = str_replace('---subject---', $email->GetSubject(), $temp);
  $temp = str_replace('---body---', $email->GetBodyText(), $temp);

  echo $temp;
}

//Method will return true if all form fields have values.
function hasValues(){
  //Array of required fields to get emails.
  $required = array('username', 'password', 'mailserver_host', 'mailserver_port');
  //Check all required fields if they are missing values.
  foreach($required as $field){
    if(empty($_POST[$field])){
      return false;
    }
  }
  return true;
}

if(hasValues() && isset($_POST["push_button"]))
{
  $username = $_POST["username"];
  $password = $_POST["password"];
  $mailserver_host = $_POST["mailserver_host"];
  $mailserver_port = $_POST["mailserver_port"];

  require_once('./IMAPReader.php');
  require_once('./Email.php');

  $ImapReader = new IMAPReader($username, $password, $mailserver_host, $mailserver_port);

  if($ImapReader->GetMessageCount() === 0){
    echo "No emails on the server!";
  }else{
    echo "There are " . $ImapReader->GetMessageCount() . " messages \n";

      for($i = 0; $i < $ImapReader->GetMessageCount(); $i++){
      $email = $ImapReader->GetMessageAtIndex($i);
      $from_address = $email['header']->fromaddress;
      $date = $email['header']->date;
      $subject = $email['header']->subject;
      $body_text = $email['body_text'];

      print_entry($pieces[1], new Email($from_address, $date, $body_text, $subject), $i + 1);
    }
  }
  echo $pieces[2];

  $ImapReader->close();
}else{
  echo "Enter credentials/information in the form to display your emails.";
}
?>
